<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Landlord;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:landlord');
    }

    public function show()
    {
        $landlord = Auth::guard('landlord')->user();
        return view('landlord.profile', compact('landlord'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image',
        ]);

        if ($request->hasFile('profile_picture')) {
            $filename = $request->profile_picture->store('profile_pictures', 'public');
            $landlord = Auth::guard('landlord')->user();

            $newLandlord = $this->createNewLandlordRecord($landlord, ['profile_picture' => $filename]);

            Auth::guard('landlord')->logout();
            return redirect()->route('login-landlord')->with('update_info', 'Due To The Update Information We Kindly Ask You To Login Again');
        }

        return redirect()->back()->with('error', 'There was an error uploading the image.');
    }

    public function updateField(Request $request)
    {
        \Log::info('Updating field: ' . $request->field . ' with value: ' . $request->value);

        $landlord = Auth::guard('landlord')->user();
        $field = $request->field;
        $value = $request->value;

        if (isset($landlord->{$field})) {
            $newLandlord = $this->createNewLandlordRecord($landlord, [$field => $value]);

            Auth::guard('landlord')->logout();
            return response()->json(['success' => true, 'message' => 'Due To The Update Information We Kindly Ask You To Login Again', 'update_info' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field specified.']);
    }

    public function showChangePasswordForm()
    {
        $landlord = Auth::guard('landlord')->user();
        return view('landlord.change-password', compact('landlord'));
    }

    public function changePassword(Request $request)
    {
        $landlord = Auth::guard('landlord')->user();

        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:5|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (!Hash::check($request->input('old_password'), $landlord->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect'])->withInput();
        }

        $newLandlord = $this->createNewLandlordRecord($landlord, ['password' => Hash::make($request->input('new_password'))]);

        session()->flash('success', 'Password changed successfully.');

        Auth::guard('landlord')->logout();
        return redirect()->route('login-landlord')->with('update_info', 'Due To The Update Information We Kindly Ask You To Login Again');
    }

    private function createNewLandlordRecord($landlord, $updates)
    {
        $lastLandlord = Landlord::where('landlord_id', $landlord->landlord_id)
            ->orderBy('version', 'desc')
            ->first();

        $newLandlord = new Landlord;
        $newLandlord->landlord_id = $lastLandlord->landlord_id; // Ensure landlord_id remains the same
        $newLandlord->landlord_name = $lastLandlord->landlord_name;
        $newLandlord->email = $lastLandlord->email;
        $newLandlord->password = $lastLandlord->password;
        $newLandlord->contact_info = $lastLandlord->contact_info;
        $newLandlord->profile_picture = $lastLandlord->profile_picture;
        $newLandlord->status = "UPDATE";
        $newLandlord->version = $lastLandlord->version + 1;
        $newLandlord->previous_record_id = $lastLandlord->id;
        $newLandlord->previous_hash = $lastLandlord->current_hash;

        foreach ($updates as $key => $value) {
            $newLandlord->{$key} = $value;
        }

        $newLandlord->save();

        // Now that the newLandlord has been saved, it will have an id
        $hash_data = [
            'id' => $newLandlord->id,
            'landlord_id' => $newLandlord->landlord_id,
            'landlord_name' => $newLandlord->landlord_name,
            'email' => $newLandlord->email,
            'password' => $newLandlord->password,
            'profile_picture' => $newLandlord->profile_picture,
            'contact_info' => $newLandlord->contact_info,
            'status' => $newLandlord->status,
            'version' => $newLandlord->version,
            'previous_record_id' => $newLandlord->previous_record_id,
            'previous_hash' => $newLandlord->previous_hash
        ];

        \Log::info('Data used for computing hash: ', $hash_data);

        $newLandlord->current_hash = hash('sha256', implode('', $hash_data));

        \Log::info('Current Hash in Profile Update: ' . $newLandlord->current_hash); // Log the hash before saving

        $newLandlord->save();

        return $newLandlord;
    }
}
?>