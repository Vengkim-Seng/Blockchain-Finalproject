<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lease extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'landlord_id',
        'tenant_id',
        'tenant_name',
        'room_number',
        'start_date',
        'end_date',
        'lease_agreement',
        'status',
        'version',
        'previous_record_id',
        'previous_hash',
        'current_hash'
    ];

    public function landlord()
    {
        return $this->belongsTo(Landlord::class, 'landlord_id', 'landlord_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
