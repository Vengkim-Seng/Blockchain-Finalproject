<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tenant;
use App\Models\Lease;
use App\Models\RentPayment;
use App\Models\Utility_bills;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TenantController extends Controller
{
    public function showRegistrationForm()
    {
        $landlord = Auth::guard('landlord')->user();
        return view('landlord.register-tenant', compact('landlord'));
    }

    public function register(Request $request)
    {
        \Log::info('Register Request Data: ', $request->all());

        $request->validate([
            'tenant_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:5',
            'contact_info' => 'required|string|max:50',
        ]);

        $tenant = new Tenant;

        if (Tenant::count() == 0) {
            $tenant->tenant_id = 1;
            $tenant->status = "INSERT";
            $tenant->version = 1;
            $tenant->previous_record_id = 0;
            $tenant->previous_hash = 0;
        } else {
            $lastTenant = Tenant::orderBy('tenant_id', 'desc')->first(); // Order by 'tenant_id' to get the latest record
            $tenant->tenant_id = $lastTenant->tenant_id + 1;
            $tenant->status = "INSERT";
            $tenant->version = 1;
            $tenant->previous_record_id = 0; // No previous record for new tenant
            $tenant->previous_hash = 0; // No previous hash for new tenant
        }

        $tenant->landlord_id = Auth::guard('landlord')->user()->landlord_id; // Ensure landlord_id is set
        $tenant->tenant_name = $request->tenant_name;
        $tenant->email = $request->email;
        $tenant->password = Hash::make($request->password);
        $tenant->contact_info = $request->contact_info;
        $tenant->profile_picture = 'default-profile-picture.jpg';

        $tenant->save();

        \Log::info('New Tenant ID: ' . $tenant->id);

        $hash_data = [
            'id' => $tenant->id,
            'tenant_id' => $tenant->tenant_id,
            'landlord_id' => $tenant->landlord_id,
            'tenant_name' => $tenant->tenant_name,
            'email' => $tenant->email,
            'password' => $tenant->password,
            'profile_picture' => $tenant->profile_picture,
            'contact_info' => $tenant->contact_info,
            'status' => $tenant->status,
            'version' => $tenant->version,
            'previous_record_id' => $tenant->previous_record_id,
            'previous_hash' => $tenant->previous_hash,
            'created_at' => $tenant->created_at,
            'updated_at' => $tenant->updated_at
        ];

        \Log::info('Data used for computing hash: ', $hash_data);

        $tenant->current_hash = hash('sha256', implode('', $hash_data));

        \Log::info('Current Hash: ' . $tenant->current_hash); // Log the hash before saving

        $tenant->save();

        return redirect()->route('tenant.show')->with('success', 'Tenant registered successfully!');
    }

    public function showAllTenant()
    {
        $landlord = Auth::guard('landlord')->user();
        if ($landlord) {
            // Fetch the latest tenant records based on created_at time
            $latestRecords = Tenant::select('tenant_name', 'email', \DB::raw('MAX(created_at) as latest_created_at'))
                ->where('landlord_id', $landlord->landlord_id)
                ->groupBy('tenant_name', 'email')
                ->get();

            // Fetch tenant details using the latest created_at time for each tenant
            $tenants = Tenant::where(function ($query) use ($latestRecords) {
                foreach ($latestRecords as $record) {
                    $query->orWhere(function ($subQuery) use ($record) {
                        $subQuery->where('tenant_name', $record->tenant_name)
                            ->where('email', $record->email)
                            ->where('created_at', $record->latest_created_at);
                    });
                }
            })
                ->where('status', '!=', 'DELETE')
                ->orderBy('tenant_id', 'desc')
                ->paginate(10);

            return view('landlord.show-tenant', compact('tenants', 'landlord'));
        } else {
            Log::error('Landlord not found or not logged in');
            return redirect()->back()->withErrors(['message' => 'Landlord not found']);
        }
    }

    public function softDeleteTenant($tenant_id)
    {
        // Fetch the latest version of the tenant record for the given tenant_id
        $currentTenant = Tenant::where('tenant_id', $tenant_id)
            ->where('landlord_id', Auth::guard('landlord')->user()->landlord_id)
            ->latest('version')
            ->firstOrFail();

        $newTenant = $currentTenant->replicate();
        $newTenant->version = $currentTenant->version + 1;
        $newTenant->status = 'DELETE';
        $newTenant->previous_record_id = $currentTenant->id;
        $newTenant->previous_hash = $currentTenant->current_hash;

        $newTenant->save();

        \Log::info('New Soft Deleted Tenant ID: ' . $newTenant->id);

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

        \Log::info('Current Hash for Soft Deleted Tenant: ' . $newTenant->current_hash); // Log the hash before saving

        $newTenant->save();

        return redirect()->route('tenant.show')->with('success', 'Tenant has been successfully deleted.');
    }

    public function showLoginForm()
    {
        return view('tenant-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        \Log::debug('Tenant login attempt with credentials: ', $credentials);

        // Fetch the latest record for the tenant with the given email
        $latestTenant = Tenant::where('email', $credentials['email'])
            ->orderBy('version', 'desc')
            ->first();

        \Log::debug('Latest tenant record for login attempt: ', [
            'tenant_id' => $latestTenant->tenant_id ?? null,
            'status' => $latestTenant->status ?? null,
            'version' => $latestTenant->version ?? null,
            'deleted_at' => $latestTenant->deleted_at ?? null,
        ]);

        // Ensure the latest tenant record has a status other than 'DELETE' and is not soft deleted
        if ($latestTenant && $latestTenant->status !== 'DELETE' && is_null($latestTenant->deleted_at) && Hash::check($credentials['password'], $latestTenant->password)) {
            Auth::guard('tenants')->login($latestTenant);
            \Log::info('Tenant login successful: ', ['tenant_id' => $latestTenant->tenant_id]);
            return redirect()->intended('tenant/dashboard');
        } else {
            \Log::info('Tenant login failed: ', [
                'credentials' => $credentials,
                'latestTenant' => $latestTenant ? $latestTenant->toArray() : null,
            ]);
            return back()->withErrors(['email' => 'Invalid credentials or account deleted'])->withInput();
        }
    }



    public function dashboard()
    {
        $tenant = Auth::guard('tenants')->user();
        if (!$tenant) {
            return redirect('login-tenant')->with('error', 'Please log in to continue.');
        }

        $currentLease = Lease::where('tenant_id', $tenant->tenant_id)->latest('start_date')->first();

        $upcomingLeaseExpiration = null;
        if ($currentLease && $currentLease->end_date <= Carbon::now()->addMonth()) {
            $upcomingLeaseExpiration = $currentLease;
        }

        $pendingRentPayments = RentPayment::where('tenant_id', $tenant->tenant_id)
            ->where('status', 'pending')
            ->whereNull('proof_of_payment')
            ->get();

        $declinedRentPayments = RentPayment::where('tenant_id', $tenant->tenant_id)->where('status', 'declined')->get();

        $pendingUtilityPayments = Utility_bills::where('tenant_id', $tenant->tenant_id)
            ->where('status', 'pending')
            ->whereNull('proof_of_utility_payment')
            ->get();

        $declinedUtilityPayments = Utility_bills::where('tenant_id', $tenant->tenant_id)->where('status', 'declined')->get();

        return view('tenant.home-dashboard', compact('tenant', 'currentLease', 'pendingRentPayments', 'declinedRentPayments', 'pendingUtilityPayments', 'declinedUtilityPayments', 'upcomingLeaseExpiration'));
    }

    public function logout()
    {
        Auth::guard('tenants')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    public function index()
    {
        $valid = true;

        // Fetch all tenants including soft-deleted ones
        $tenants = Tenant::withTrashed()->orderBy('tenant_id', 'asc')->get();

        foreach ($tenants as $tenant) {
            $hash_data = [
                'id' => $tenant->id,
                'tenant_id' => $tenant->tenant_id,
                'landlord_id' => $tenant->landlord_id,
                'tenant_name' => $tenant->tenant_name,
                'email' => $tenant->email,
                'password' => $tenant->password,
                'profile_picture' => $tenant->profile_picture,
                'contact_info' => $tenant->contact_info,
                'status' => $tenant->status,
                'version' => $tenant->version,
                'previous_record_id' => $tenant->previous_record_id,
                'previous_hash' => $tenant->previous_hash,
                'created_at' => $tenant->created_at,
                'updated_at' => $tenant->updated_at
            ];

            $computed_hash = hash('sha256', implode('', $hash_data));

            // Detailed logging for hash computation
            \Log::info('Hash calculation details for tenant ', $hash_data);
            \Log::info('Computed Hash: ' . $computed_hash);

            if ($computed_hash !== $tenant->current_hash) {
                \Log::error('Hash mismatch', [
                    'tenant_id' => $tenant->tenant_id,
                    'computed_hash' => $computed_hash,
                    'current_hash' => $tenant->current_hash,
                ]);
                $valid = false;
                break;
            }

            // Skip the linked records check for the initial record
            if ($tenant->previous_record_id == 0 && $tenant->previous_hash == '0') {
                continue;
            }

            $linked_records_count = Tenant::where('id', $tenant->previous_record_id)
                ->where('current_hash', $tenant->previous_hash)
                ->count();

            if ($linked_records_count === 0) {
                \Log::error('Invalid linked records count', [
                    'tenant_id' => $tenant->tenant_id,
                    'previous_record_id' => $tenant->previous_record_id,
                    'previous_hash' => $tenant->previous_hash,
                    'linked_records_count' => $linked_records_count,
                ]);
                $valid = false;
                break;
            }
        }

        return view('all-tenants-table', compact('tenants', 'valid'));
    }
}
