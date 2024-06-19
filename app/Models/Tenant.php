<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Tenant extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'tenants';

    protected $primaryKey = 'tenant_id';

    protected $fillable = [
        'landlord_id',
        'tenant_name',
        'email',
        'password',
        'contact_info',
        'profile_picture'
    ];

    // Define the method required by the Authenticatable interface
    public function getAuthIdentifierName()
    {
        return 'tenant_id'; // Assuming 'id' is the primary key of your tenants table
    }

    public function getAuthIdentifier()
    {
        return $this->getKey(); // Return the value of the primary key
    }

    public function getAuthPassword()
    {
        return $this->password; // Assuming 'password' is the hashed password field in your tenants table
    }

    public function landlord()
    {
        return $this->belongsTo('App\Models\Landlord', 'landlord_id');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'tenant_id', 'tenant_id');
    }
}
