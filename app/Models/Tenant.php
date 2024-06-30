<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Tenant extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticatableTrait;

    protected $primaryKey = 'id';

    protected $fillable = [
        'tenant_id',
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
        return $this->belongsTo(Landlord::class, 'landlord_id', 'id');
    }
    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

}
