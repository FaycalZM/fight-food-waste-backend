<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'scheduled_time',
        'route',
        'volunteers_count',
        'distribution_status',
    ];

    // public function beneficiaries()
    // {
    //     return $this->hasMany(DistributionBeneficiary::class, 'distribution_id');
    // }

    // public function products()
    // {
    //     return $this->hasMany(DistributionProduct::class, 'distribution_id');
    // }

    // Many-to-Many relationship with Beneficiaries
    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'distribution_beneficiaries', 'distribution_id', 'beneficiary_id');
    }

    // Many-to-Many relationship with Products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'distribution_products', 'distribution_id', 'product_id')
            ->withPivot('quantity_distributed'); // Include extra fields like quantity if needed
    }

    public function report()
    {
        return $this->hasOne(DistributionReport::class, 'distribution_id');
    }
}
