<?php

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products/listings.
     */
    public function viewAny(User $user): bool
    {
        return ! $user->isSuspended();
    }

    /**
     * Determine whether the user can view the listing.
     */
    public function view(User $user, Listing $product): bool
    {
        return ! $user->isSuspended();
    }

    /**
     * Determine whether the user can create harvest listings (UC-03).
     * Strictly reserved for Farmers (Producers) and Municipal Administrators.
     */
    public function create(User $user): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->isFarmer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the harvest listing.
     * Only the listing creator (farmer) or an administrator can modify it.
     */
    public function update(User $user, Listing $product): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->isAdmin() || ($user->isFarmer() && ($user->user_id === $product->farmer_id || $user->id === $product->user_id));
    }

    /**
     * Determine whether the user can delete/unpublish the harvest listing.
     */
    public function delete(User $user, Listing $product): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        return $user->isAdmin() || ($user->isFarmer() && ($user->user_id === $product->farmer_id || $user->id === $product->user_id));
    }
}
