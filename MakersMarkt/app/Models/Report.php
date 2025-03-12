<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'report_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'reason',
        'report_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
    ];

    /**
     * Get the user that made the report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that was reported.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}