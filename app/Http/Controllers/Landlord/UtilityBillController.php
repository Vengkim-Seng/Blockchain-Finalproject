<?php

namespace App\Http\Controllers\Landlord;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Landlord;
use App\Models\Utility_bills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;



class UtilityBillController extends Controller
{
    // Fetch Data
    public function index()
    {

        // Fetch utility payments based on their status
        $pendingPayments = Utility_bills::with('lease', 'tenant')->where('status', 'pending')->get();
        $approvedPayments = Utility_bills::with('lease', 'tenant')->where('status', 'approved')->get();
        $declinedPayments = Utility_bills::with('lease', 'tenant')->where('status', 'declined')->get();

        $landlord = Auth::guard('landlord')->user();
        $tenants = Tenant::where('landlord_id', $landlord->id)->get();

        return view('landlord.show-utility', compact('pendingPayments', 'approvedPayments', 'declinedPayments', 'landlord', 'tenants'));
    }


    public function create()
    {
        $landlord = Auth::guard('landlord')->user();

        // Fetch tenants with active leases for the landlord
        $tenantsWithActiveLeases = Tenant::whereHas('leases', function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id)
                ->where('end_date', '>=', now());
        })
        ->with(['leases' => function ($query) use ($landlord) {
            $query->where('landlord_id', $landlord->id)
                ->where('end_date', '>=', now());
        }])
        ->get();

        return view('landlord.create-utility', compact('tenantsWithActiveLeases', 'landlord'));
    }

    // Store Function Start
    private $utilityRates = [
        'electricity' => 0.375,
        'water' => 0.5,
    ];

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'tenant_name' => 'required|exists:tenants,tenant_name',
            'billing_date' => 'required|date',
            'utilities' => 'required|array',
            'proof_of_meter_readings.*' => 'required|file|mimes:jpeg,png,pdf|max:2048',
            'proof_of_utility_payment' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,declined',
        ]);

        // Retrieve the authenticated landlord
        $landlord = Auth::guard('landlord')->user();

        // Retrieve the tenant ID based on the selected tenant name
        $tenantName = $request->input('tenant_name');
        $tenant = Tenant::where('landlord_id', $landlord->id)
            ->where('tenant_name', $tenantName)
            ->firstOrFail();
        $tenantId = $tenant->tenant_id; // Use 'tenant_id' instead of 'id'

        // Retrieve the active lease ID for the tenant
        $activeLease = $tenant->leases()
            ->where('end_date', '>=', now()->toDateString())
            ->latest('start_date')
            ->first();

        if (!$activeLease) {
            // Handle the case when there is no active lease for the selected tenant
            return redirect()->back()->withErrors(['error' => 'Active lease not found for the selected tenant.']);
        }

        // Create path to store proof_of_meter_reading
        $proofOfMeterReadingPaths = [];
        if ($request->hasFile('proof_of_meter_readings')) {
            foreach ($request->file('proof_of_meter_readings') as $file) {
                $path = $file->store('meter_readings', 'public');
                $proofOfMeterReadingPaths[] = $path;
            }
        }

        // Create formula to calculate total amount
        $totalAmount = 0;
        $utilities = [];
        foreach ($request->utilities as $type => $utility) {
            $amount = $this->calculateAmount($type, $utility['previous_meter_reading'], $utility['current_meter_reading']);
            $totalAmount += $amount;

            $utilities[$type] = [
                'previous_meter_reading' => $utility['previous_meter_reading'],
                'current_meter_reading' => $utility['current_meter_reading'],
                'amount' => $amount,
            ];
        }


        // Create the utility payment only if an active lease is found
        $utilityBill = Utility_bills::create([
            'lease_id' => $activeLease->lease_id, // Use 'lease_id' instead of 'id'
            'tenant_id' => $tenantId,
            'billing_date' => $request->billing_date,
            'utilities' => json_encode($utilities),
            'total_amount' => $totalAmount,
            'proof_of_meter_reading' => json_encode($proofOfMeterReadingPaths),
            'proof_of_utility_payment' => $request->proof_of_payment,
            'status' => $request->status ?? 'pending',
        ]);

        if (!$utilityBill) {
            // Handle the case when rent payment creation fails
            return redirect()->back()->withErrors(['error' => 'Failed to create rent payment.']);
        }

        return redirect()->route('utility.create')->with('success', 'Rent payment added successfully.');
    }
    private function calculateAmount($type, $previousMeterReading, $currentMeterReading)
    {
        // Ensure the utility type exists in the rates array
        if (!array_key_exists($type, $this->utilityRates)) {
            throw new \Exception("Invalid utility type: {$type}");
        }

        // Calculate the amount based on the difference in meter readings and the rate
        $rate = $this->utilityRates[$type];
        $usage = $currentMeterReading - $previousMeterReading;

        // Ensure usage is non-negative
        if ($usage < 0) {
            throw new \Exception("Invalid meter readings: previous reading is higher than current reading.");
        }

        return $usage * $rate;
    }
    // Store Function End

    // Update Status
    public function updateStatus(Request $request, Utility_bills $utility_billsPayment)
    {
        // Validate the request data
        $request->validate([
            'status' => 'required|in:pending,approved,declined',
        ]);

        // Update the status
        $utility_billsPayment->status = $request->status;
        $utility_billsPayment->save();

        return redirect()->route('utility.index')->with('success', 'Utility payment status updated successfully.');
    }

    // Tenant
    public function showUtility()
    {
        $tenant = Auth::guard('tenants')->user();
        
        $pendingPayments = Utility_bills::where('tenant_id', $tenant->tenant_id)->where('status', 'pending')->with('landlord', 'lease')->get();
        $approvedPayments = Utility_bills::where('tenant_id', $tenant->tenant_id)->where('status', 'approved')->with('landlord', 'lease')->get();
        $declinedPayments = Utility_bills::where('tenant_id', $tenant->tenant_id)->where('status', 'declined')->with('landlord', 'lease')->get();

        return view('tenant.show-utility', compact('pendingPayments', 'approvedPayments', 'declinedPayments', 'tenant'));
    }

    public function uploadProof(Request $request, $utility_bill_id)
    {
        $request->validate([
            'proof_of_payment' => 'required|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        $utility_bills = Utility_bills::findOrFail($utility_bill_id);

        if ($request->hasFile('proof_of_payment')) {
            // Store the file
            $path = $request->file('proof_of_payment')->store('proof_of_utility_payments', 'public');
            $utility_bills->proof_of_utility_payment = $path;
            $utility_bills->status = 'pending'; // Set status to pending after re-upload
            $utility_bills->save();

            return redirect()->back()->with('success', 'Proof of payment uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload proof of payment.');
    }



}
