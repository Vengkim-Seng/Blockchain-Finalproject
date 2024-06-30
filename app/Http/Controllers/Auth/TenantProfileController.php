<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Tenant;

class TenantProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:tenants');
    }

    public function show()
    {
        $tenant = Auth::guard('tenants')->user();
        return view('tenant.profile', compact('tenant'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048', // 2MB Max
        ]);

        if ($request->hasFile('profile_picture')) {
            $filename = $request->profile_picture->store('tenant_profile_pictures', 'public');
            $tenant = Auth::guard('tenants')->user();

            // Insert a new record with the updated profile picture
            $newTenant = $this->createNewTenantRecord($tenant, ['profile_picture' => $filename]);

            return redirect()->back()->with('success', 'Profile picture updated successfully!');
        }

        return redirect()->back()->with('error', 'There was an error uploading the image.');
    }

    public function updateField(Request $request)
    {
        \Log::info('Updating field: ' . $request->field . ' with value: ' . $request->value); // Laravel logging

        $tenant = Auth::guard('tenants')->user();
        $field = $request->field;
        $value = $request->value;

        if (isset($tenant->{$field})) {
            // Insert a new record with the updated field value
            $newTenant = $this->createNewTenantRecord($tenant, [$field => $value]);

            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field specified.']);
    }

    public function showChangePasswordForm()
    {
        $tenant = Auth::guard('tenants')->user();
        return view('tenant.change-password', compact('tenant'));
    }

    public function changePassword(Request $request)
    {
        $tenant = Auth::guard('tenants')->user();

        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:5|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->input('old_password'), $tenant->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect'])->withInput();
        }

        // Insert a new record with the updated password
        $newTenant = $this->createNewTenantRecord($tenant, ['password' => Hash::make($request->input('new_password'))]);

        session()->flash('success', 'Password changed successfully.');

        return redirect()->route('tenant.profile');
    }

    private function createNewTenantRecord($tenant, $updates)
    {
        // Get the latest version of the tenant record
        $lastTenant = Tenant::where('tenant_name', $tenant->tenant_name)
            ->orderBy('version', 'desc')
            ->first();

        $newTenant = new Tenant;
        $newTenant->landlord_id = $lastTenant->landlord_id;
        $newTenant->tenant_name = $lastTenant->tenant_name;
        $newTenant->email = $lastTenant->email;
        $newTenant->password = $lastTenant->password;
        $newTenant->contact_info = $lastTenant->contact_info;
        $newTenant->profile_picture = $lastTenant->profile_picture;
        $newTenant->status = "UPDATE";
        $newTenant->version = $lastTenant->version + 1;
        $newTenant->previous_record_id = $lastTenant->tenant_id;
        $newTenant->previous_hash = $lastTenant->current_hash;

        foreach ($updates as $key => $value) {
            $newTenant->{$key} = $value;
        }

        // Save first to generate the id
        $newTenant->save();

        // Generate the current hash after saving to get the id
        $newTenant->current_hash = hash('sha256', $newTenant->id . $newTenant->tenant_name . $newTenant->email . $newTenant->password . $newTenant->contact_info . $newTenant->profile_picture . $newTenant->status . $newTenant->version . $newTenant->previous_record_id . $newTenant->previous_hash);

        // Save again to update the current_hash
        $newTenant->save();

        return $newTenant;
    }
}
