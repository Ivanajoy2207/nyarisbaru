<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'bio',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wishlist' => 'array',
        ];
    }

    public function forumPosts()
    {
        return $this->hasMany(\App\Models\ForumPost::class);
    }

    public function forumComments()
    {
        return $this->hasMany(\App\Models\ForumComment::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }


    public function chatsAsBuyer()
    {
        return $this->hasMany(\App\Models\Chat::class, 'buyer_id');
    }

    public function chatsAsSeller()
    {
        return $this->hasMany(\App\Models\Chat::class, 'seller_id');
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'user_wishlist')->withTimestamps();
    }

    public function sellerReviews()
    {
        return $this->hasMany(\App\Models\SellerReview::class, 'seller_id');
    }

    public function averageSellerRating()
    {
        return round($this->sellerReviews()->avg('rating'), 1);
    }





}
