<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    use HasFactory;

    protected $primaryKey = 'rent_payment_id';

    protected $fillable = [
        'tenant_id',
        'lease_id',
        'payment_date',
        'amount',
        'proof_of_payment',
        'status',
    ];

    // Define the relationship with the lease
    public function lease()
    {
        return $this->belongsTo(Lease::class, 'lease_id', 'lease_id');
    }

    public function landlord()
    {
        return $this->belongsTo(Landlord::class);
    }


    // Define the relationship with the tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

}
