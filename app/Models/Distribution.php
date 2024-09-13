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
        'distribution_status',
    ];

    public function beneficiaries()
    {
        return $this->hasMany(DistributionBeneficiary::class, 'distribution_id');
    }

    public function products()
    {
        return $this->hasMany(DistributionProduct::class, 'distribution_id');
    }

    public function report()
    {
        return $this->hasOne(DistributionReport::class, 'distribution_id');
    }
}
