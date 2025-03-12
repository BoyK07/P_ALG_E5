<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'review_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'rating',
        'comment',
        'review_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'date',
    ];

    /**
     * Get the order that owns the review.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the product for this review through the order.
     */
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            Order::class,
            'order_id', // Foreign key on the order table
            'product_id', // Foreign key on the product table
            'order_id', // Local key on the review table
            'product_id' // Local key on the order table
        );
    }

    /**
     * Get the buyer who wrote this review through the order.
     */
    public function reviewer()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'order_id', // Foreign key on the order table
            'id', // Foreign key on the user table
            'order_id', // Local key on the review table
            'buyer_id' // Local key on the order table
        );
    }
}