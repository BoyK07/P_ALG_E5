<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): Response
    {
        return Response::allow();
    }
    
    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): Response
    {
        return $user->hasRole('maker')
            ? Response::allow()
            : Response::deny('You do not have permission to create products.');
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): Response
    {
        return (
            $user->id === $product->maker_id ||
            $user->hasRole('admin') ||
            $user->hasRole('moderator')
        )
            ? Response::allow()
            : Response::deny('You do not have permission to update this product.');
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): Response
    {
        return (
            $user->id === $product->maker_id ||
            $user->hasRole('admin')
        )
            ? Response::allow()
            : Response::deny('You do not have permission to delete this product.');
    }
}
