<?php

namespace App\Policies;

use App\Models\Inquiry;
use App\Models\Listing;
use App\Models\User;

class InquiryPolicy
{
    /**
     * Determine whether the user can create an inquiry / pre-order (UC-05).
     * Any non-suspended buyer, or a user who is not the produce owner.
     */
    public function create(User $user, ?Listing $product = null): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        if ($product && ($product->farmer_id === $user->user_id || $product->user_id === $user->id)) {
            return false; // Cannot order one's own harvest listing
        }

        return true;
    }

    /**
     * Determine whether the user can view the inquiry.
     * Only the buyer, the farmer who received it, or an administrator.
     */
    public function view(User $user, Inquiry $inquiry): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        $farmerId = $inquiry->farmer_id ?? $inquiry->listing?->farmer_id;

        return $user->isAdmin()
            || $user->user_id === $inquiry->buyer_id
            || $user->id === $inquiry->buyer_id
            || $user->user_id === $farmerId
            || $user->id === $farmerId;
    }

    /**
     * Determine whether the user can update the status (accept/decline) of the inquiry.
     * Strictly the farmer who received the inquiry or a municipal administrator.
     */
    public function updateStatus(User $user, Inquiry $inquiry): bool
    {
        if ($user->isSuspended()) {
            return false;
        }

        $farmerId = $inquiry->farmer_id ?? $inquiry->listing?->farmer_id;

        return $user->isAdmin() || $user->user_id === $farmerId || $user->id === $farmerId;
    }
}
