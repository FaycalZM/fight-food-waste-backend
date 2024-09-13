<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'barcode',
        'category',
        'expiration_date',
        'user_id',
        'stock_id'
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function collections()
    {
        return $this->hasMany(CollectionProduct::class, 'product_id');
    }

    public function distributions()
    {
        return $this->hasMany(DistributionProduct::class, 'product_id');
    }
}
