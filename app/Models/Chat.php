<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Produk yang dibicarakan
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Pembeli
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Penjual
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Semua pesan dalam chat
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    // 🔥 PESAN TERAKHIR (WAJIB UNTUK INBOX)
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil lawan bicara (selain user login)
     */
    public function otherUser($authUserId)
    {
        return $this->buyer_id === $authUserId
            ? $this->seller
            : $this->buyer;
    }
}
