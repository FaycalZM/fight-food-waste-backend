<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_id',
        'product_id',
        'quantity_collected',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
