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

            $newTenant = $this->createNewTenantRecord($tenant, ['profile_picture' => $filename]);

            Auth::guard('tenants')->logout();
            return redirect()->route('login-tenant')->with('update_info', 'Due To The Update Information We Kindly Ask You To Login Again');
        }

        return redirect()->back()->with('error', 'There was an error uploading the image.');
    }

    public function updateField(Request $request)
    {
        \Log::info('Updating field: ' . $request->field . ' with value: ' . $request->value);

        $tenant = Auth::guard('tenants')->user();
        $field = $request->field;
        $value = $request->value;

        if (isset($tenant->{$field})) {
            $newTenant = $this->createNewTenantRecord($tenant, [$field => $value]);

            Auth::guard('tenants')->logout();
            return response()->json(['success' => true, 'message' => 'Due To The Update Information We Kindly Ask You To Login Again', 'update_info' => true]);
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

        $newTenant = $this->createNewTenantRecord($tenant, ['password' => Hash::make($request->input('new_password'))]);

        session()->flash('success', 'Password changed successfully.');

        Auth::guard('tenants')->logout();
        return redirect()->route('login-tenant')->with('update_info', 'Due To The Update Information We Kindly Ask You To Login Again');
    }

    private function createNewTenantRecord($tenant, $updates)
    {
        $lastTenant = Tenant::where('tenant_id', $tenant->tenant_id)
            ->orderBy('version', 'desc')
            ->first();

        $newTenant = new Tenant;
        $newTenant->tenant_id = $lastTenant->tenant_id; // Ensure tenant_id remains the same
        $newTenant->landlord_id = $lastTenant->landlord_id; // Ensure landlord_id is set
        $newTenant->tenant_name = $lastTenant->tenant_name;
        $newTenant->email = $lastTenant->email;
        $newTenant->password = $lastTenant->password;
        $newTenant->contact_info = $lastTenant->contact_info;
        $newTenant->profile_picture = $lastTenant->profile_picture;
        $newTenant->status = "UPDATE";
        $newTenant->version = $lastTenant->version + 1;
        $newTenant->previous_record_id = $lastTenant->id;
        $newTenant->previous_hash = $lastTenant->current_hash;

        foreach ($updates as $key => $value) {
            $newTenant->{$key} = $value;
        }

        $newTenant->save();

        // Now that the newTenant has been saved, it will have an id
        $hash_data = [
            'id' => $newTenant->id,
            'tenant_id' => $newTenant->tenant_id,
            'landlord_id' => $newTenant->landlord_id,
            'tenant_name' => $newTenant->tenant_name,
            'email' => $newTenant->email,
            'password' => $newTenant->password,
            'profile_picture' => $newTenant->profile_picture,
            'contact_info' => $newTenant->contact_info,
            'status' => $newTenant->status,
            'version' => $newTenant->version,
            'previous_record_id' => $newTenant->previous_record_id,
            'previous_hash' => $newTenant->previous_hash,
            'created_at' => $newTenant->created_at,
            'updated_at' => $newTenant->updated_at
        ];

        \Log::info('Data used for computing hash: ', $hash_data);

        $newTenant->current_hash = hash('sha256', implode('', $hash_data));

        \Log::info('Current Hash in Profile Update: ' . $newTenant->current_hash); // Log the hash before saving

        $newTenant->save();

        return $newTenant;
    }
}
