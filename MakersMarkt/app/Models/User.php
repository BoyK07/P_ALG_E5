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
        'username',
        'email',
        'password',
        'store_credit',
        'profile_bio',
        'profile_image',
        'contact_info',
        'registration_date',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'store_credit' => 'decimal:2',
        'registration_date' => 'date',
    ];

    /**
     * Get the roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Get the products created by the user as a maker.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'maker_id');
    }

    /**
     * Get the orders placed by the user as a buyer.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the moderations performed by the user.
     */
    public function moderations()
    {
        return $this->hasMany(Moderation::class, 'moderator_id');
    }

    /**
     * Get the reports made by the user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }
}