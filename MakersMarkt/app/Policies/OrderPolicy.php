<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->id === $order->product->maker_id || $user->hasRole('admin');
    }
}
