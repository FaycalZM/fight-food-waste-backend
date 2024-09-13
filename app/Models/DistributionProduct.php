<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'distribution_id',
        'product_id',
        'quantity_distributed',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class, 'distribution_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
