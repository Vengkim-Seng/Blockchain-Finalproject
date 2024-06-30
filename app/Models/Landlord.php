<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Landlord extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'landlord_id',
        'landlord_name',
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

    // Define the method required by the Authenticatable interface

    public function getAuthIdentifierName()
    {
        return 'id'; // Assuming 'id' is the primary key of your landlords table
    }

    public function getAuthIdentifier()
    {
        return $this->getKey(); // Return the value of the primary key
    }

    public function getAuthPassword()
    {
        return $this->password; // Assuming 'password' is the hashed password field in your landlords table
    }

    public function leases()
    {
        return $this->hasMany(Lease::class, 'landlord_id', 'id');
    }
}
