<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
        'status',
        'escrow_status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }


    public function sellerReview()
    {
        return $this->hasOne(\App\Models\SellerReview::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

}
