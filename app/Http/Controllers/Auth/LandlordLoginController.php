<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Landlord\UtilityBillController;
use App\Models\Landlord;
use App\Models\Utility_bills;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\RentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LandlordLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('landlord-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Get the latest version of the landlord record
        $latestLandlord = Landlord::where('email', $credentials['email'])
            ->orderBy('version', 'desc')
            ->first();

        if ($latestLandlord && Hash::check($credentials['password'], $latestLandlord->password)) {
            // Log in using the latest landlord record
            Auth::guard('landlord')->login($latestLandlord);
            return redirect()->intended(route('landlord.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function dashboard()
    {
        if (Auth::guard('landlord')->check()) {
            $landlord = Auth::guard('landlord')->user();

            // Fetch the required data for the dashboard
            $totalTenants = Tenant::where('landlord_id', $landlord->id)->count();
            $activeLeases = Lease::where('landlord_id', $landlord->id)->where('end_date', '>=', now())->count();
            $pendingRentPayments = RentPayment::whereHas('lease', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->where('status', 'pending')->count();
            $pendingUtilityPayments = Utility_bills::whereHas('lease', function ($query) use ($landlord) {
                $query->where('landlord_id', $landlord->id);
            })->where('status', 'pending')->count();

            // Fetch recent pending rent payments
            $recentPendingRentPayments = RentPayment::with('tenant', 'lease')
                ->whereHas('lease', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })
                ->where('status', 'pending')
                ->orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();

            // Fetch recent pending utility payments
            $recentPendingUtilityPayments = Utility_bills::with('tenant', 'lease')
                ->whereHas('lease', function ($query) use ($landlord) {
                    $query->where('landlord_id', $landlord->id);
                })
                ->where('status', 'pending')
                ->orderBy('billing_date', 'desc')
                ->limit(5)
                ->get();

            // Fetch leases expiring within the next month
            $upcomingLeaseExpirations = Lease::with('tenant')
                ->where('landlord_id', $landlord->id)
                ->where('end_date', '<=', Carbon::now()->addMonth())
                ->orderBy('end_date', 'asc')
                ->get();

            return view('landlord.home-dashboard', compact('totalTenants', 'activeLeases', 'pendingRentPayments', 'pendingUtilityPayments', 'recentPendingRentPayments', 'recentPendingUtilityPayments', 'upcomingLeaseExpirations', 'landlord'));
        } else {
            return redirect()->route('login-landlord');
        }
    }

    public function logout()
    {
        Auth::guard('landlord')->logout();

        // Optionally, you can invalidate the user session to regenerate the session ID
        request()->session()->invalidate();

        // Optionally, regenerate a new session ID for added security
        request()->session()->regenerateToken();

        return redirect('/'); // Redirect to the homepage or login page
    }
}