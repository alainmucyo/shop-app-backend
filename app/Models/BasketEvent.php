<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasketEvent extends Model
{
    use HasFactory;

    protected $fillable = ['basket_id', 'product_id', 'event_type'];
    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
