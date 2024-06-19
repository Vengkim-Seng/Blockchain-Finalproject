<?php

namespace App\Http\Controllers\Landlord;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Landlord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class LeaseController extends Controller
{
    
    public function create()
    {
        $landlord = Auth::guard('landlord')->user();
        $tenants = Tenant::where('landlord_id', $landlord->id)->get();
        return view('landlord.create-lease', compact('landlord', 'tenants'));
    }

    public function store(Request $request)
    {
        $landlord = Auth::guard('landlord')->user();

        $request->validate([
            'tenant_id' => 'required|exists:tenants,tenant_id',
            'room_number' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'lease_agreement' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Check if the tenant already has an active lease
        $existingLease = Lease::where('tenant_id', $request->tenant_id)
            ->where('end_date', '>', now())
            ->first();

        if ($existingLease) {
            return redirect()->back()->withErrors(['tenant_id' => 'This tenant already has an active lease.']);
        }

        // Check if the room number is already taken by the landlord
        $existingRoom = Lease::where('landlord_id', $landlord->id)
            ->where('room_number', $request->room_number)
            ->where('end_date', '>', now())
            ->first();

        if ($existingRoom) {
            return redirect()->back()->withErrors(['room_number' => 'This room number is already assigned to another tenant.']);
        }

        $leaseAgreementPath = $request->file('lease_agreement')->store('lease_agreements', 'public');

        Lease::create([
            'landlord_id' => $landlord->id, // Use the authenticated landlord's ID
            'tenant_id' => $request->tenant_id,
            'room_number' => $request->room_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'lease_agreement' => $leaseAgreementPath,
        ]);

        return redirect()->route('leases.create')->with('success', 'Lease created successfully.');
    }

    public function index()
    {
        $landlord = Auth::guard('landlord')->user();
        $leases = Lease::where('landlord_id', $landlord->id)->get();
        return view('landlord.show-leases', compact('leases', 'landlord'));
    }

    // public function show(Lease $lease)
    // {
    //     $landlord = Auth::guard('landlord')->user();
    //     return view('landlord.show-lease', compact('lease', 'landlord'));
    // }

    public function edit(Lease $lease)
    {
        $landlord = Auth::guard('landlord')->user();
        return view('landlord.edit-lease', compact('lease', 'landlord'));
    }

    public function update(Request $request, Lease $lease)
    {
        $request->validate([
            'room_number' => [
                'required',
                'integer',
                Rule::unique('leases')->ignore($lease),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'lease_agreement' => 'nullable|file|mimes:pdf|max:2048', // Make lease agreement field nullable for update
        ]);

        // Update lease data
        $lease->update([
            'room_number' => $request->room_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Check if lease agreement file is uploaded
        if ($request->hasFile('lease_agreement')) {
            // Delete old lease agreement file if exists
            if (Storage::disk('public')->exists($lease->lease_agreement)) {
                Storage::disk('public')->delete($lease->lease_agreement);
            }
            // Store new lease agreement file
            $leaseAgreementPath = $request->file('lease_agreement')->store('lease_agreements', 'public');
            // Update lease agreement path
            $lease->update(['lease_agreement' => $leaseAgreementPath]);
        }

        return redirect()->route('leases.index', $lease)->with('success', 'Lease updated successfully.');
    }


    public function destroy(Lease $lease)
    {
        // Delete the lease
        $lease->delete();

        return redirect()->route('leases.index')->with('success', 'Lease deleted successfully.');
    }

    //For tenants

    public function showTenantLeases()
    {
        $tenant = Auth::guard('tenants')->user();
        // $leases = Lease::where('tenant_id', $tenant->tenant_id)->with('landlord')->get();
        $leases = Lease::where('tenant_id', $tenant->tenant_id)->get();
        
        return view('tenant.show-leases', compact('leases', 'tenant'));
    }


}
