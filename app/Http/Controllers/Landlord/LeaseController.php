<?php

namespace App\Http\Controllers\Landlord;

use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeaseController extends Controller
{
    public function create()
    {
        $landlord = Auth::guard('landlord')->user();

        // Fetch tenants with latest record that does not have a "DELETE" status
        $latestRecords = Tenant::select('tenant_id', 'tenant_name', 'email', \DB::raw('MAX(created_at) as latest_created_at'))
            ->where('landlord_id', $landlord->landlord_id)
            ->groupBy('tenant_id', 'tenant_name', 'email')
            ->get();

        $tenants = Tenant::where(function ($query) use ($latestRecords) {
            foreach ($latestRecords as $record) {
                $query->orWhere(function ($subQuery) use ($record) {
                    $subQuery->where('tenant_id', $record->tenant_id)
                        ->where('created_at', $record->latest_created_at);
                });
            }
        })
            ->where('status', '!=', 'DELETE')
            ->get();

        return view('landlord.create-lease', compact('landlord', 'tenants'));
    }

    public function store(Request $request)
    {
        Log::info('Storing new lease with request data: ', $request->all());

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
            ->orderBy('version', 'desc')
            ->first();

        if ($existingLease && $existingLease->status != 'DELETE' && $existingLease->end_date > now()) {
            Log::error('Tenant already has an active lease.');
            return redirect()->back()->withErrors(['tenant_id' => 'This tenant already has an active lease.']);
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
            'tenant_id' => $request->tenant_id,
            'room_number' => $request->room_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'lease_agreement' => $leaseAgreementPath,
        ]);

        // Setting initial values for blockchain attributes
        $lease->status = 'INSERT';
        $lease->version = 1;

        // Check if there are previous leases for the same tenant to set the correct previous_record_id and previous_hash
        $lastLeaseForTenant = Lease::where('tenant_id', $request->tenant_id)->orderBy('lease_id', 'desc')->first();
        if ($lastLeaseForTenant) {
            $lease->previous_record_id = $lastLeaseForTenant->lease_id;
            $lease->previous_hash = $lastLeaseForTenant->current_hash;
        } else {
            $lease->previous_record_id = 0;
            $lease->previous_hash = '0';
        }

        $lease->save();
        Log::info('New lease saved with ID: ' . $lease->lease_id);

        // Re-fetch the saved lease to ensure lease_id is set
        $lease = Lease::findOrFail($lease->lease_id);

        // Generate the current hash
        $hash_data = [
            'lease_id' => $lease->lease_id,
            'landlord_id' => $lease->landlord_id,
            'tenant_id' => $lease->tenant_id,
            'room_number' => $lease->room_number,
            'start_date' => $lease->start_date,
            'end_date' => $lease->end_date,
            'lease_agreement' => $lease->lease_agreement,
            'status' => $lease->status,
            'version' => $lease->version,
            'previous_record_id' => $lease->previous_record_id,
            'previous_hash' => $lease->previous_hash,
            'created_at' => $lease->created_at,
            'updated_at' => $lease->updated_at
        ];

        Log::info('Data used for computing hash: ', $hash_data);

        $lease->current_hash = hash('sha256', implode('', $hash_data));

        Log::info('Generated current hash for lease: ' . $lease->current_hash);

        // Save again to update the current_hash
        Lease::where('lease_id', $lease->lease_id)->update(['current_hash' => $lease->current_hash, 'updated_at' => now()]);

        Log::info('Lease current hash after saving: ' . $lease->current_hash);

        return redirect()->route('leases.create')->with('success', 'Lease created successfully.');
    }


    public function index()
    {
        $landlord = Auth::guard('landlord')->user();
        if ($landlord) {
            Log::info('Fetching leases for landlord ID: ' . $landlord->landlord_id);

            $latestRecords = Lease::select('landlord_id', 'tenant_id', 'room_number', DB::raw('MAX(created_at) as latest_created_at'))
                ->where('landlord_id', $landlord->landlord_id)
                ->groupBy('landlord_id', 'tenant_id', 'room_number')
                ->get();

            $leases = Lease::with('tenant') // Eager load tenant information
                ->where(function ($query) use ($latestRecords) {
                    foreach ($latestRecords as $record) {
                        $query->orWhere(function ($subQuery) use ($record) {
                            $subQuery->where('landlord_id', $record->landlord_id)
                                ->where('tenant_id', $record->tenant_id)
                                ->where('room_number', $record->room_number)
                                ->where('created_at', $record->latest_created_at);
                        });
                    }
                })
                ->where('status', '!=', 'DELETE')
                ->orderBy('tenant_id', 'desc')
                ->paginate(10);

            Log::info('Number of leases fetched: ' . $leases->count());

            if ($leases->isEmpty()) {
                Log::info('No leases found for landlord ID: ' . $landlord->landlord_id);
            } else {
                Log::info('Fetched leases: ', $leases->toArray());
            }

            return view('landlord.show-leases', compact('leases', 'landlord'));
        } else {
            Log::error('Landlord not found or not logged in');
            return redirect()->back()->withErrors(['message' => 'Landlord not found']);
        }
    }


    public function edit($id)
    {
        $landlord = Auth::guard('landlord')->user();
        $lease = Lease::with('tenant')->where('lease_id', $id)->firstOrFail();
        return view('landlord.edit-lease', compact('lease', 'landlord'));
    }


    public function update(Request $request, $id)
    {
        $lease = Lease::where('lease_id', $id)->firstOrFail();
        Log::info('Updating lease with ID: ' . $lease->lease_id);

        $request->validate([
            'room_number' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'lease_agreement' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $updatedData = [
            'landlord_id' => $lease->landlord_id,
            'tenant_id' => $lease->tenant_id,
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
        $newLease->tenant_id = $updatedData['tenant_id'];
        $newLease->room_number = $updatedData['room_number'];
        $newLease->start_date = $updatedData['start_date'];
        $newLease->end_date = $updatedData['end_date'];
        $newLease->lease_agreement = $updatedData['lease_agreement'];
        $newLease->status = $updatedData['status'];
        $newLease->version = $updatedData['version'];
        $newLease->previous_record_id = $updatedData['previous_record_id'];
        $newLease->previous_hash = $updatedData['previous_hash'];

        $newLease->save();

        $hash_data = [
            'lease_id' => $newLease->lease_id,
            'landlord_id' => $newLease->landlord_id,
            'tenant_id' => $newLease->tenant_id,
            'room_number' => $newLease->room_number,
            'start_date' => $newLease->start_date,
            'end_date' => $newLease->end_date,
            'lease_agreement' => $newLease->lease_agreement,
            'status' => $newLease->status,
            'version' => $newLease->version,
            'previous_record_id' => $newLease->previous_record_id,
            'previous_hash' => $newLease->previous_hash,
            'created_at' => $newLease->created_at,
            'updated_at' => $newLease->updated_at
        ];

        Log::info('Data used for computing hash: ', $hash_data);

        $newLease->current_hash = hash('sha256', implode('', $hash_data));

        Lease::where('lease_id', $newLease->lease_id)->update(['current_hash' => $newLease->current_hash]);

        Log::info('Lease updated successfully with new ID: ' . $newLease->lease_id);
        Log::info('Data being stored: ', $newLease->toArray());

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully.');
    }

    public function softDeleteLease($id)
    {
        Log::info('Soft deleting lease with ID: ' . $id);

        $currentLease = Lease::where('lease_id', $id)->latest('version')->firstOrFail();

        $newLease = $currentLease->replicate(['lease_id']);
        $newLease->version = $currentLease->version + 1;
        $newLease->status = 'DELETE';
        $newLease->previous_record_id = $currentLease->lease_id;
        $newLease->previous_hash = $currentLease->current_hash;

        $newLease->save();

        Log::info('New Soft Deleted Lease ID: ' . $newLease->lease_id);

        $hash_data = [
            'lease_id' => $newLease->lease_id,
            'landlord_id' => $newLease->landlord_id,
            'tenant_id' => $newLease->tenant_id,
            'room_number' => $newLease->room_number,
            'start_date' => $newLease->start_date,
            'end_date' => $newLease->end_date,
            'lease_agreement' => $newLease->lease_agreement,
            'status' => $newLease->status,
            'version' => $newLease->version,
            'previous_record_id' => $newLease->previous_record_id,
            'previous_hash' => $newLease->previous_hash,
            'created_at' => $newLease->created_at,
            'updated_at' => $newLease->updated_at
        ];

        Log::info('Data used for computing hash: ', $hash_data);

        $newLease->current_hash = hash('sha256', implode('', $hash_data));

        Log::info('Current Hash for Soft Deleted Lease: ' . $newLease->current_hash);

        Lease::where('lease_id', $newLease->lease_id)->update(['current_hash' => $newLease->current_hash, 'updated_at' => now()]);

        return redirect()->route('leases.index')->with('success', 'Lease has been successfully deleted.');
    }

    // For tenants
    public function showTenantLeases()
    {
        $tenant = Auth::guard('tenants')->user();
        if ($tenant) {
            Log::info('Fetching leases for tenant ID: ' . $tenant->tenant_id);

            $latestRecords = Lease::select('landlord_id', 'tenant_id', 'room_number', DB::raw('MAX(created_at) as latest_created_at'))
                ->where('tenant_id', $tenant->tenant_id)
                ->groupBy('landlord_id', 'tenant_id', 'room_number')
                ->get();

            $leases = Lease::where(function ($query) use ($latestRecords) {
                foreach ($latestRecords as $record) {
                    $query->orWhere(function ($subQuery) use ($record) {
                        $subQuery->where('landlord_id', $record->landlord_id)
                            ->where('tenant_id', $record->tenant_id)
                            ->where('room_number', $record->room_number)
                            ->where('created_at', $record->latest_created_at);
                    });
                }
            })
                ->where('status', '!=', 'DELETE')
                ->orderBy('tenant_id', 'desc')
                ->paginate(10);

            Log::info('Number of leases fetched: ' . $leases->count());

            if ($leases->isEmpty()) {
                Log::info('No leases found for tenant ID: ' . $tenant->tenant_id);
            } else {
                Log::info('Fetched leases: ', $leases->toArray());
            }

            return view('tenant.show-leases', compact('leases', 'tenant'));
        } else {
            Log::error('Tenant not found or not logged in');
            return redirect()->back()->withErrors(['message' => 'Tenant not found']);
        }
    }


    public function index2()
    {
        $valid = true;

        // Fetch all leases including soft-deleted ones with their related landlord and tenant
        $leases = Lease::with(['landlord', 'tenant'])->withTrashed()->orderBy('lease_id', 'asc')->get();

        foreach ($leases as $lease) {
            $hash_data = [
                'lease_id' => $lease->lease_id,
                'landlord_id' => $lease->landlord_id,
                'tenant_id' => $lease->tenant_id,
                'room_number' => $lease->room_number,
                'start_date' => $lease->start_date,
                'end_date' => $lease->end_date,
                'lease_agreement' => $lease->lease_agreement,
                'status' => $lease->status,
                'version' => $lease->version,
                'previous_record_id' => $lease->previous_record_id,
                'previous_hash' => $lease->previous_hash,
                'created_at' => $lease->created_at,
                'updated_at' => $lease->updated_at
            ];

            $computed_hash = hash('sha256', implode('', $hash_data));

            Log::info('Hash calculation details for lease ', $hash_data);
            Log::info('Computed Hash: ' . $computed_hash);

            if ($computed_hash !== $lease->current_hash) {
                Log::error('Hash mismatch', [
                    'lease_id' => $lease->lease_id,
                    'computed_hash' => $computed_hash,
                    'current_hash' => $lease->current_hash,
                ]);
                $valid = false;
                break;
            }

            if ($lease->previous_record_id == 0 && $lease->previous_hash == '0') {
                continue;
            }

            $linked_records_count = Lease::where('lease_id', $lease->previous_record_id)
                ->where('current_hash', $lease->previous_hash)
                ->count();

            if ($linked_records_count === 0) {
                Log::error('Invalid linked records count', [
                    'lease_id' => $lease->lease_id,
                    'previous_record_id' => $lease->previous_record_id,
                    'previous_hash' => $lease->previous_hash,
                    'linked_records_count' => $linked_records_count,
                ]);
                $valid = false;
                break;
            }
        }

        return view('all-lease-table', compact('leases', 'valid'));
    }

}
