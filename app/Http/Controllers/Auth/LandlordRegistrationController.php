<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LandlordRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('sign-up-landlord');
    }

    public function register(Request $request)
    {
        $request->validate([
            'landlord_name' => 'required|string|max:50|unique:landlords',
            'email' => 'required|string|email|max:50|unique:landlords',
            'password' => 'required|string|min:5|confirmed',
            'contact_info' => 'required|string|max:50',
        ]);

        $landlord = new Landlord;

        if (Landlord::count() == 0) {
            $landlord->status = "INSERT";
            $landlord->version = 1;
            $landlord->previous_record_id = 0;
            $landlord->previous_hash = 0;
        } else {
            $lastLandlord = Landlord::orderBy('id', 'desc')->first();
            $landlord->status = "INSERT";
            $landlord->version = 1;
            $landlord->previous_record_id = $lastLandlord->id;
            $landlord->previous_hash = $lastLandlord->current_hash;
        }

        $landlord->landlord_name = $request->landlord_name;
        $landlord->email = $request->email;
        $landlord->password = Hash::make($request->password);
        $landlord->contact_info = $request->contact_info;
        $landlord->profile_picture = 'default-profile-picture.jpg';

        // Generate the current hash
        $landlord->current_hash = hash('sha256', $landlord->id . $landlord->landlord_name . $landlord->email . $landlord->password . $landlord->contact_info . $landlord->status . $landlord->version . $landlord->previous_record_id . $landlord->previous_hash);

        $landlord->save();

        return redirect()->route('login-landlord')->with('success', 'Registration successful. Please log in.');
    }
}

