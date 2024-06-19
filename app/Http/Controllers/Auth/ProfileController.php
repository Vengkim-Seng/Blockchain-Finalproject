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
        $this->middleware('auth:landlord'); // Ensure the correct guard is used for this controller
    }

    /**
     * Show the form for editing the logged-in landlord's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $landlord = Auth::guard('landlord')->user(); // Fetch the currently authenticated landlord
        return view('landlord.profile', compact('landlord'));
    }

    /**
     * Handle the uploading of the profile picture.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image', // 2MB Max
        ]);

        if ($request->hasFile('profile_picture')) {
            $filename = $request->profile_picture->store('profile_pictures', 'public');
            $landlord = Auth::guard('landlord')->user(); // Ensure to use the same guard

            // Insert a new record with updated profile picture
            $newLandlord = $this->createNewLandlordRecord($landlord, ['profile_picture' => $filename]);

            return redirect()->back()->with('success', 'Profile picture updated successfully!');
        }

        return redirect()->back()->with('error', 'There was an error uploading the image.');
    }

    public function updateField(Request $request)
    {
        \Log::info('Updating field: ' . $request->field . ' with value: ' . $request->value); // Laravel logging

        $landlord = Auth::guard('landlord')->user();
        $field = $request->field;
        $value = $request->value;

        if (isset($landlord->{$field})) {
            // Insert a new record with the updated field value
            $newLandlord = $this->createNewLandlordRecord($landlord, [$field => $value]);

            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field specified.']);
    }

    // Change Password
    public function showChangePasswordForm()
    {
        $landlord = Auth::guard('landlord')->user();
        // Return the view for changing password
        return view('landlord.change-password', compact('landlord'));
    }

    public function changePassword(Request $request)
    {
        $landlord = Auth::guard('landlord')->user();

        // Define validation rules
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:5|confirmed', // Password confirmation must match
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // If validation fails, return with errors
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Check if the old password matches the current one
        if (!Hash::check($request->input('old_password'), $landlord->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect'])->withInput();
        }

        // Insert a new record with the updated password
        $newLandlord = $this->createNewLandlordRecord($landlord, ['password' => Hash::make($request->input('new_password'))]);

        session()->flash('success', 'Password changed successfully.');

        return redirect()->route('profile.show'); // Redirect to a safe location after changing password
    }

    private function createNewLandlordRecord($landlord, $updates)
    {
        // Get the latest version of the landlord record
        $lastLandlord = Landlord::where('landlord_name', $landlord->landlord_name)
            ->orderBy('version', 'desc')
            ->first();

        $newLandlord = new Landlord;
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

        // Generate the current hash
        $newLandlord->current_hash = hash('sha256', $newLandlord->id . $newLandlord->landlord_name . $newLandlord->email . $newLandlord->password . $newLandlord->contact_info . $newLandlord->profile_picture . $newLandlord->status . $newLandlord->version . $newLandlord->previous_record_id . $newLandlord->previous_hash);

        $newLandlord->save();

        return $newLandlord;
    }
}
