<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    protected $primaryKey = 'lease_id';

    protected $fillable = [
        'landlord_id',
        'tenant_name',
        'room_number',
        'start_date',
        'end_date',
        'lease_agreement',
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_name', 'tenant_name');
    }
}
