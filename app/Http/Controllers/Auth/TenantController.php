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
        $request->validate([
            'tenant_name' => 'required|string|max:50|unique:tenants',
            'email' => 'required|string|email|max:50|unique:tenants',
            'contact_info' => 'required|string|max:50',
            'password' => 'required|string|min:5'
        ]);

        $tenant = new Tenant;

        if (Tenant::count() == 0) {
            $tenant->status = "INSERT";
            $tenant->version = 1;
            $tenant->previous_record_id = 0;
            $tenant->previous_hash = 0;
        } else {
            $lastTenant = Tenant::orderBy('tenant_id', 'desc')->first();
            $tenant->status = "INSERT";
            $tenant->version = 1;
            $tenant->previous_record_id = $lastTenant->id;
            $tenant->previous_hash = $lastTenant->current_hash;
        }

        $tenant->landlord_id = Auth::guard('landlord')->id();
        $tenant->tenant_name = $request->tenant_name;
        $tenant->email = $request->email;
        $tenant->password = Hash::make($request->password);
        $tenant->contact_info = $request->contact_info;
        $tenant->profile_picture = 'default-profile-picture.jpg';

        // Generate the current hash
        $tenant->current_hash = hash('sha256', $tenant->id . $tenant->tenant_name . $tenant->email . $tenant->password . $tenant->contact_info . $tenant->status . $tenant->version . $tenant->previous_record_id . $tenant->previous_hash);

        $tenant->save();

        return redirect('/tenant/show')->with('success', 'Tenant registered successfully!');
    }

    public function showAllTenant()
    {
        $landlord = Auth::guard('landlord')->user();
        if ($landlord) {
            $tenants = Tenant::where('landlord_id', $landlord->id)
                ->latest()
                ->paginate(10);

            return view('landlord.show-tenant', [
                'tenants' => $tenants,
                'landlord' => $landlord,
            ]);
        } else {
            Log::error('Landlord not found or not logged in');
            return redirect()->back()->withErrors(['message' => 'Landlord not found']);
        }
    }

    public function destroy($tenant_id)
    {
        $landlord = Auth::guard('landlord')->user();

        if ($landlord) {
            $tenant = Tenant::where('landlord_id', $landlord->id)
                ->where('id', $tenant_id)
                ->first();

            if ($tenant) {
                $tenant->delete();
                session()->flash('success', 'Tenant deleted successfully.');
                return redirect()->route('tenant.show');
            } else {
                session()->flash('error', 'Tenant not found or unauthorized.');
                return redirect()->back();
            }
        } else {
            Log::error('Landlord not found or not logged in');
            return redirect()->route('landlord.dashboard')->withErrors(['message' => 'Please log in as a landlord to delete tenants']);
        }
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

        if ($latestTenant && Hash::check($credentials['password'], $latestTenant->password)) {
            Auth::guard('tenants')->login($latestTenant);
            return redirect()->intended('tenant/dashboard');
        } else {
            Log::debug('Tenant login failed', ['credentials' => $credentials]);
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }
    }

    public function dashboard()
    {
        $tenant = Auth::guard('tenants')->user();
        if (!$tenant) {
            return redirect('login-tenant')->with('error', 'Please log in to continue.');
        }

        $currentLease = Lease::where('tenant_id', $tenant->id)->latest('start_date')->first();

        $upcomingLeaseExpiration = null;
        if ($currentLease && $currentLease->end_date <= Carbon::now()->addMonth()) {
            $upcomingLeaseExpiration = $currentLease;
        }

        $pendingRentPayments = RentPayment::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->whereNull('proof_of_payment')
            ->get();

        $declinedRentPayments = RentPayment::where('tenant_id', $tenant->id)->where('status', 'declined')->get();

        $pendingUtilityPayments = Utility_bills::where('tenant_id', $tenant->id)
            ->where('status', 'pending')
            ->whereNull('proof_of_utility_payment')
            ->get();

        $declinedUtilityPayments = Utility_bills::where('tenant_id', $tenant->id)->where('status', 'declined')->get();

        return view('tenant.home-dashboard', compact('tenant', 'currentLease', 'pendingRentPayments', 'declinedRentPayments', 'pendingUtilityPayments', 'declinedUtilityPayments', 'upcomingLeaseExpiration'));
    }

    public function logout()
    {
        Auth::guard('tenants')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
