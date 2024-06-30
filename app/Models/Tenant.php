<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'tenant_id';

    protected $fillable = [
        'landlord_id',
        'tenant_name',
        'email',
        'password',
        'contact_info',
        'profile_picture',
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

    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_name', 'tenant_name');
    }
}
