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


    public function softDeleteLandlord($id)
        {
            // $landlord = Landlord::find($id);

            // if (Landlord::count() == 0) {
            //     $landlord->status = "DELETE";
            //     $landlord->version = 1;
            //     $landlord->previous_record_id = 0;
            //     $landlord->previous_hash = 0;
            // } else {
            //     $lastLandlord = Landlord::orderBy('id', 'desc')->first();
            //     $landlord->status = "DELETE";
            //     $landlord->version = $lastLandlord->version + 1;
            //     $landlord->previous_record_id = $lastLandlord->id;
            //     $landlord->previous_hash = $lastLandlord->current_hash;
            // }

            // // Generate a new current hash including the new status
            // $landlord->current_hash = hash('sha256', $landlord->id . $landlord->landlord_name . $landlord->email . $landlord->password . $landlord->contact_info . $landlord->status . $landlord->version . $landlord->previous_record_id . $landlord->previous_hash);

            // $landlord->save(); // Save the updates before soft deleting
            // $landlord->delete(); // Soft delete the landlord

            // return redirect()->route('register.landlord')->with('success', 'Landlord has been successfully deleted.');
            // $currentLandlord = Landlord::where('id', $id)->latest('version')->firstOrFail();
            // $lastLandlord = Landlord::orderBy('id', 'desc')->first();

            // if ($lastLandlord !== null){
            //     $newLandlord = $currentLandlord->replicate();
            //     $newLandlord->version = $currentLandlord->version + 1;
            //     $newLandlord->status = 'DELETE';
            //     $newlandlord->previous_record_id = $currentLandlord->id;
            //     $newlandlord->previous_hash = $currentLandlord->current_hash;
            // } 
            
            $currentLandlord = Landlord::where('id', $id)->latest('version')->firstOrFail();
            $lastLandlord = Landlord::orderBy('id', 'desc')->first();

            $newLandlord = $currentLandlord->replicate();
            $newLandlord->version = $currentLandlord->version + 1;
            $newLandlord->status = 'DELETE';
            $newLandlord->previous_record_id = $currentLandlord->id; // Corrected variable name


            $newLandlord->current_hash = hash('sha256', $newLandlord->id . $newLandlord->landlord_name . $newLandlord->email . $newLandlord->password . $newLandlord->contact_info . $newLandlord->status . $newLandlord->version . $newLandlord->previous_record_id . $newLandlord->previous_hash);

                $newLandlord->save(); // Save the updates before soft deleting
                $newLandlord->delete(); // Soft delete the landlord

                return redirect()->route('register.landlord')->with('success', 'Landlord has been successfully deleted.');

        }
}

