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
use Illuminate\Support\Facades\Log;

class LeaseController extends Controller
{
    public function create()
    {
        $landlord = Auth::guard('landlord')->user();
        $tenants = Tenant::where('landlord_id', $landlord->landlord_id)->get();
        return view('landlord.create-lease', compact('landlord', 'tenants'));
    }

    public function store(Request $request)
    {
        Log::info('Storing new lease with request data: ', $request->all());

        $landlord = Auth::guard('landlord')->user();

        $request->validate([
            'tenant_name' => 'required|exists:tenants,tenant_name',
            'room_number' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'lease_agreement' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Check if the tenant already has an active lease
        $existingLease = Lease::where('tenant_name', $request->tenant_name)
            ->orderBy('version', 'desc')
            ->first();

        if ($existingLease && $existingLease->status != 'DELETE' && $existingLease->end_date > now()) {
            Log::error('Tenant already has an active lease.');
            return redirect()->back()->withErrors(['tenant_name' => 'This tenant already has an active lease.']);
        }

        // Check if the room number is already taken by the landlord
        $existingRoom = Lease::where('landlord_id', $landlord->landlord_id)
            ->where('room_number', $request->room_number)
            ->orderBy('version', 'desc')
            ->first();

        if ($existingRoom && $existingRoom->status != 'DELETE' && $existingRoom->end_date > now()) {
            Log::error('Room number is already assigned to another tenant.');
            return redirect()->back()->withErrors(['room_number' => 'This room number is already assigned to another tenant.']);
        }

        $leaseAgreementPath = $request->file('lease_agreement')->store('lease_agreements', 'public');
        Log::info('Lease agreement path: ' . $leaseAgreementPath);

        $lease = new Lease([
            'landlord_id' => $landlord->landlord_id,
            'tenant_name' => $request->tenant_name,
            'room_number' => $request->room_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'lease_agreement' => $leaseAgreementPath,
        ]);

        // Setting initial values for blockchain attributes
        $lease->status = 'INSERT';
        $lease->version = 1;

        // Check if there are previous leases for the same tenant to set the correct previous_record_id and previous_hash
        $lastLeaseForTenant = Lease::where('tenant_name', $request->tenant_name)->orderBy('lease_id', 'desc')->first();
        if ($lastLeaseForTenant) {
            $lease->previous_record_id = $lastLeaseForTenant->lease_id;
            $lease->previous_hash = $lastLeaseForTenant->current_hash;
        } else {
            $lease->previous_record_id = 0;
            $lease->previous_hash = '0';
        }

        $lease->save();
        Log::info('New lease saved with ID: ' . $lease->lease_id);

        $lease->current_hash = hash('sha256', $lease->lease_id . $lease->tenant_name . $lease->room_number . $lease->start_date . $lease->end_date . $lease->lease_agreement . $lease->status . $lease->version . $lease->previous_record_id . $lease->previous_hash);
        $lease->save();

        Log::info('Lease current hash: ' . $lease->current_hash);

        return redirect()->route('leases.create')->with('success', 'Lease created successfully.');
    }

    public function index()
    {
        $landlord = Auth::guard('landlord')->user();
        Log::info('Fetching leases for landlord ID: ' . $landlord->landlord_id);

        $leases = Lease::select('leases.*')
            ->join(
                \DB::raw('(SELECT tenant_name, landlord_id, room_number, MAX(created_at) as latest_created_at FROM leases GROUP BY tenant_name, landlord_id, room_number) as latest'),
                function ($join) {
                    $join->on('leases.tenant_name', '=', 'latest.tenant_name')
                        ->on('leases.landlord_id', '=', 'latest.landlord_id')
                        ->on('leases.room_number', '=', 'latest.room_number')
                        ->on('leases.created_at', '=', 'latest.latest_created_at');
                }
            )
            ->where('leases.landlord_id', $landlord->landlord_id)
            ->where(function ($query) {
                $query->where('leases.status', '!=', 'DELETE')
                    ->orWhere(function ($subquery) {
                        $subquery->where('leases.status', 'DELETE')
                            ->whereExists(function ($query) {
                                $query->select(\DB::raw(1))
                                    ->from('leases as l2')
                                    ->whereRaw('l2.tenant_name = leases.tenant_name')
                                    ->whereRaw('l2.landlord_id = leases.landlord_id')
                                    ->whereRaw('l2.room_number = leases.room_number')
                                    ->whereRaw('l2.created_at > leases.created_at');
                            });
                    });
            })
            ->orderBy('leases.lease_id', 'desc')
            ->get();

        Log::info('Fetched leases: ', $leases->toArray());

        return view('landlord.show-leases', compact('leases', 'landlord'));
    }

    public function edit(Lease $lease)
    {
        $landlord = Auth::guard('landlord')->user();
        return view('landlord.edit-lease', compact('lease', 'landlord'));
    }

    public function update(Request $request, Lease $lease)
    {
        Log::info('Updating lease with ID: ' . $lease->lease_id);

        $request->validate([
            'room_number' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'lease_agreement' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Manual unique check for room number with the same landlord and tenant
        $updatedData = [
            'landlord_id' => $lease->landlord_id,
            'tenant_name' => $lease->tenant_name,
            'room_number' => $request->room_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'lease_agreement' => $lease->lease_agreement,
            'status' => 'UPDATE',
            'version' => $lease->version + 1,
            'previous_record_id' => $lease->lease_id,
            'previous_hash' => $lease->current_hash,
        ];

        if ($request->hasFile('lease_agreement')) {
            if (Storage::disk('public')->exists($lease->lease_agreement)) {
                Storage::disk('public')->delete($lease->lease_agreement);
            }
            $leaseAgreementPath = $request->file('lease_agreement')->store('lease_agreements', 'public');
            $updatedData['lease_agreement'] = $leaseAgreementPath;
        }

        $newLease = new Lease();
        $newLease->landlord_id = $updatedData['landlord_id'];
        $newLease->tenant_name = $updatedData['tenant_name'];
        $newLease->room_number = $updatedData['room_number'];
        $newLease->start_date = $updatedData['start_date'];
        $newLease->end_date = $updatedData['end_date'];
        $newLease->lease_agreement = $updatedData['lease_agreement'];
        $newLease->status = $updatedData['status'];
        $newLease->version = $updatedData['version'];
        $newLease->previous_record_id = $updatedData['previous_record_id'];
        $newLease->previous_hash = $updatedData['previous_hash'];

        $newLease->save();

        $newLease->current_hash = hash('sha256', $newLease->lease_id . $newLease->tenant_name . $newLease->room_number . $newLease->start_date . $newLease->end_date . $newLease->lease_agreement . $newLease->status . $newLease->version . $newLease->previous_record_id . $newLease->previous_hash);
        $newLease->save();

        Log::info('Lease updated successfully with new ID: ' . $newLease->lease_id);
        Log::info('Data being stored: ', $newLease->toArray());

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully.');
    }

    public function softDeleteLease($lease_id)
    {
        Log::info('Soft deleting lease with ID: ' . $lease_id);

        $currentLease = Lease::where('lease_id', $lease_id)->latest('version')->firstOrFail();

        $newLease = $currentLease->replicate();
        $newLease->version = $currentLease->version + 1;
        $newLease->status = 'DELETE';
        $newLease->previous_record_id = $currentLease->lease_id;
        $newLease->previous_hash = $currentLease->current_hash;

        // Save first to generate the id for the new record
        $newLease->save();

        // Log the new Lease ID after save
        Log::info('New Soft Deleted Lease ID: ' . $newLease->lease_id);

        // Generate the current hash after saving to get the id
        $newLease->current_hash = hash('sha256', $newLease->lease_id . $newLease->tenant_name . $newLease->room_number . $newLease->start_date . $newLease->end_date . $newLease->lease_agreement . $newLease->status . $newLease->version . $newLease->previous_record_id . $newLease->previous_hash);

        // Log the current hash
        Log::info('Current Hash for Soft Deleted Lease: ' . $newLease->current_hash);

        // Save again to update the current_hash
        $newLease->save();

        return redirect()->route('leases.index')->with('success', 'Lease has been successfully deleted.');
    }

    // For tenants
    public function showTenantLeases()
    {
        $tenant = Auth::guard('tenants')->user();
        $leases = Lease::where('tenant_name', $tenant->tenant_name)->get();
        return view('tenant.show-leases', compact('leases', 'tenant'));
    }
}
