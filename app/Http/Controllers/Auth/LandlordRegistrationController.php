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
        \Log::info('Register Request Data: ', $request->all());

        $request->validate([
            'landlord_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:landlords,email',
            'password' => 'required|string|min:5|confirmed',
            'contact_info' => 'required|string|max:50',
        ]);

        $landlord = new Landlord;

        if (Landlord::count() == 0) {
            $landlord->landlord_id = 1;
            $landlord->status = "INSERT";
            $landlord->version = 1;
            $landlord->previous_record_id = 0;
            $landlord->previous_hash = 0;
        } else {
            $lastLandlord = Landlord::orderBy('id', 'desc')->first(); // Order by 'id' to get the latest record
            $landlord->landlord_id = $lastLandlord->landlord_id + 1;
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

        $landlord->save();

        \Log::info('New Landlord ID: ' . $landlord->id);

        $hash_data = [
            'id' => $landlord->id,
            'landlord_id' => $landlord->landlord_id,
            'landlord_name' => $landlord->landlord_name,
            'email' => $landlord->email,
            'password' => $landlord->password,
            'profile_picture' => $landlord->profile_picture,
            'contact_info' => $landlord->contact_info,
            'status' => $landlord->status,
            'version' => $landlord->version,
            'previous_record_id' => $landlord->previous_record_id,
            'previous_hash' => $landlord->previous_hash
        ];

        \Log::info('Data used for computing hash: ', $hash_data);

        $landlord->current_hash = hash('sha256', implode('', $hash_data));

        \Log::info('Current Hash: ' . $landlord->current_hash); // Log the hash before saving

        $landlord->save();

        return redirect()->route('login-landlord')->with('success', 'Registration successful. Please log in.');
    }

    public function softDeleteLandlord($id)
    {
        $currentLandlord = Landlord::where('id', $id)->latest('version')->firstOrFail();

        $newLandlord = $currentLandlord->replicate();
        $newLandlord->version = $currentLandlord->version + 1;
        $newLandlord->status = 'DELETE';
        $newLandlord->previous_record_id = $currentLandlord->id;
        $newLandlord->previous_hash = $currentLandlord->current_hash;

        $newLandlord->save();

        \Log::info('New Soft Deleted Landlord ID: ' . $newLandlord->id);

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

        \Log::info('Current Hash for Soft Deleted Landlord: ' . $newLandlord->current_hash); // Log the hash before saving

        $newLandlord->save();
        $newLandlord->delete();

        return redirect()->route('register.landlord')->with('info', 'Due To The Update Information We Kindly Ask You To Login Again');
    }

    public function index()
    {
        $valid = true;

        // Fetch all landlords including soft-deleted ones
        $landlords = Landlord::withTrashed()->orderBy('landlord_id', 'asc')->get();

        foreach ($landlords as $landlord) {
            $hash_data = [
                'id' => $landlord->id,
                'landlord_id' => $landlord->landlord_id,
                'landlord_name' => $landlord->landlord_name,
                'email' => $landlord->email,
                'password' => $landlord->password,
                'profile_picture' => $landlord->profile_picture,
                'contact_info' => $landlord->contact_info,
                'status' => $landlord->status,
                'version' => $landlord->version,
                'previous_record_id' => $landlord->previous_record_id,
                'previous_hash' => $landlord->previous_hash
            ];

            $computed_hash = hash('sha256', implode('', $hash_data));

            // Detailed logging for hash computation
            \Log::info('Hash calculation details for landlord ', $hash_data);
            \Log::info('Computed Hash: ' . $computed_hash);

            if ($computed_hash !== $landlord->current_hash) {
                \Log::error('Hash mismatch', [
                    'landlord_id' => $landlord->id,
                    'computed_hash' => $computed_hash,
                    'current_hash' => $landlord->current_hash,
                ]);
                $valid = false;
                break;
            }

            // Skip the linked records check for the initial record
            if ($landlord->previous_record_id == 0 && $landlord->previous_hash == '0') {
                continue;
            }

            $linked_records_count = Landlord::where('id', $landlord->previous_record_id)
                ->where('current_hash', $landlord->previous_hash)
                ->count();

            if ($linked_records_count === 0) {
                \Log::error('Invalid linked records count', [
                    'landlord_id' => $landlord->id,
                    'previous_record_id' => $landlord->previous_record_id,
                    'previous_hash' => $landlord->previous_hash,
                    'linked_records_count' => $linked_records_count,
                ]);
                $valid = false;
                break;
            }
        }

        return view('all-landlord-table', compact('landlords', 'valid'));
    }
}
?>