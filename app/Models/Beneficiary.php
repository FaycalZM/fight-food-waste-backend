<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_info',
        'address',
        'association_details',
        'individual_needs',
    ];

    public function distributions()
    {
        return $this->hasMany(DistributionBeneficiary::class, 'beneficiary_id');
    }
}
