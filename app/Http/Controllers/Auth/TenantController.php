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
            'contact_info' => 'required|string|max:50',
            'password' => 'required|string|min:5',
        ]);

        $tenant = new Tenant;

        $tenant->landlord_id = Auth::guard('landlord')->user()->landlord_id;
        $tenant->tenant_name = $request->tenant_name;
        $tenant->email = $request->email;
        $tenant->password = Hash::make($request->password);
        $tenant->contact_info = $request->contact_info;
        $tenant->profile_picture = 'default-profile-picture.jpg';

        if (Tenant::count() == 0) {
            $tenant->status = "INSERT";
            $tenant->version = 1;
            $tenant->previous_record_id = 0;
            $tenant->previous_hash = 0;
        } else {
            $lastTenant = Tenant::where('tenant_name', $request->tenant_name)->orderBy('tenant_id', 'desc')->first();
            if ($lastTenant) {
                $tenant->status = "INSERT";
                $tenant->version = 1;
                $tenant->previous_record_id = $lastTenant->tenant_id;
                $tenant->previous_hash = $lastTenant->current_hash;
            } else {
                $tenant->status = "INSERT";
                $tenant->version = 1;
                $tenant->previous_record_id = 0;
                $tenant->previous_hash = 0;
            }
        }

        $tenant->save();

        \Log::info('New Tenant ID: ' . $tenant->tenant_id);

        $tenant->current_hash = hash('sha256', $tenant->tenant_id . $tenant->tenant_name . $tenant->email . $tenant->password . $tenant->contact_info . $tenant->status . $tenant->version . $tenant->previous_record_id . $tenant->previous_hash);

        \Log::info('Current Hash: ' . $tenant->current_hash);

        $tenant->save();

        return redirect()->route('tenant.show')->with('success', 'Tenant registered successfully!');
    }

    public function showAllTenant()
    {
        $landlord = Auth::guard('landlord')->user();
        if ($landlord) {
            $tenants = Tenant::select('tenants.*')
                ->join(
                    \DB::raw('(SELECT tenant_name, MAX(version) as latest_version FROM tenants GROUP BY tenant_name) as latest'),
                    function ($join) {
                        $join->on('tenants.tenant_name', '=', 'latest.tenant_name')
                            ->on('tenants.version', '=', 'latest.latest_version');
                    }
                )
                ->where('tenants.landlord_id', $landlord->landlord_id)
                ->where('tenants.status', '!=', 'DELETE')
                ->orderBy('tenants.tenant_id', 'desc')
                ->paginate(10);

            return view('landlord.show-tenant', compact('tenants', 'landlord'));
        } else {
            Log::error('Landlord not found or not logged in');
            return redirect()->back()->withErrors(['message' => 'Landlord not found']);
        }
    }

    public function softDeleteTenant($tenant_id)
    {
        $currentTenant = Tenant::where('tenant_id', $tenant_id)->latest('version')->firstOrFail();

        $newTenant = $currentTenant->replicate();
        $newTenant->version = $currentTenant->version + 1;
        $newTenant->status = 'DELETE';
        $newTenant->previous_record_id = $currentTenant->tenant_id;
        $newTenant->previous_hash = $currentTenant->current_hash;

        $newTenant->save();

        \Log::info('New Soft Deleted Tenant ID: ' . $newTenant->tenant_id);

        $newTenant->current_hash = hash('sha256', $newTenant->tenant_id . $newTenant->tenant_name . $newTenant->email . $newTenant->password . $newTenant->contact_info . $newTenant->status . $newTenant->version . $newTenant->previous_record_id . $newTenant->previous_hash);

        \Log::info('Current Hash for Soft Deleted Tenant: ' . $newTenant->current_hash);

        $newTenant->save();

        \Log::info('Soft Deleted Tenant Status: ' . $newTenant->status);
        \Log::info('Soft Deleted Tenant Hash after update: ' . $newTenant->current_hash);

        return redirect()->route('tenant.show')->with('success', 'Tenant has been successfully deleted.');
    }

    public function showLoginForm()
    {
        return view('tenant-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $latestTenant = Tenant::where('email', $credentials['email'])
            ->orderBy('version', 'desc')
            ->first();

        if ($latestTenant && $latestTenant->status !== 'DELETE' && Hash::check($credentials['password'], $latestTenant->password)) {
            Auth::guard('tenants')->login($latestTenant);
            return redirect()->intended('tenant/dashboard');
        } else {
            Log::debug('Tenant login failed', ['credentials' => $credentials]);
            return back()->withErrors(['email' => 'Invalid credentials or account deleted'])->withInput();
        }
    }

    public function dashboard()
    {
        $tenant = Auth::guard('tenants')->user();
        if (!$tenant) {
            return redirect('login-tenant')->with('error', 'Please log in to continue.');
        }

        $currentLease = Lease::where('tenant_name', $tenant->tenant_name)->latest('start_date')->first();

        $upcomingLeaseExpiration = null;
        if ($currentLease && $currentLease->end_date <= Carbon::now()->addMonth()) {
            $upcomingLeaseExpiration = $currentLease;
        }

        $pendingRentPayments = RentPayment::where('tenant_name', $tenant->tenant_name)
            ->where('status', 'pending')
            ->whereNull('proof_of_payment')
            ->get();

        $declinedRentPayments = RentPayment::where('tenant_name', $tenant->tenant_name)->where('status', 'declined')->get();

        $pendingUtilityPayments = Utility_bills::where('tenant_name', $tenant->tenant_name)
            ->where('status', 'pending')
            ->whereNull('proof_of_utility_payment')
            ->get();

        $declinedUtilityPayments = Utility_bills::where('tenant_name', $tenant->tenant_name)->where('status', 'declined')->get();

        return view('tenant.home-dashboard', compact('tenant', 'currentLease', 'pendingRentPayments', 'declinedRentPayments', 'pendingUtilityPayments', 'declinedUtilityPayments', 'upcomingLeaseExpiration'));
    }

    public function logout()
    {
        Auth::guard('tenants')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    //Need to fix this not yet working
    public function index()
    {
        $valid = true;

        // Fetch all tenants including soft-deleted ones
        $tenants = Tenant::withTrashed()->orderBy('tenant_id', 'asc')->get();

        foreach ($tenants as $tenant) {
            $hash_data = [
                'landlord_id' => $tenant->landlord_id,
                'tenant_id' => $tenant->tenant_id,
                'tenant_name' => $tenant->tenant_name,
                'email' => $tenant->email,
                'password' => $tenant->password,
                'profile_picture' => $tenant->profile_picture,
                'contact_info' => $tenant->contact_info,
                'status' => $tenant->status,
                'version' => $tenant->version,
                'previous_record_id' => $tenant->previous_record_id,
                'previous_hash' => $tenant->previous_hash
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

            $linked_records_count = Tenant::where('tenant_id', $tenant->previous_record_id)
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
