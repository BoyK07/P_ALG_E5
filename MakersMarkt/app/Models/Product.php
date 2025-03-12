<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'material',
        'production_time',
        'complexity',
        'durability',
        'unique_features',
        'contains_external_links',
        'maker_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'production_time' => 'integer',
        'contains_external_links' => 'boolean',
    ];

    /**
     * Get the maker that owns the product.
     */
    public function maker()
    {
        return $this->belongsTo(User::class, 'maker_id');
    }

    /**
     * Get the orders for the product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the moderations for the product.
     */
    public function moderations()
    {
        return $this->hasMany(Moderation::class);
    }

    /**
     * Get the reports for the product.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get all reviews for the product through orders.
     */
    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Order::class, 'product_id', 'order_id');
    }
}