<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id
            || $user->id === $order->listing->user_id
            || $user->hasRole('admin');
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->id === $order->listing->user_id
            || $user->hasRole('admin');
    }
}