<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Listing;
use App\Models\User;

final class ListingPolicy
{
    public function before(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(?User $user, Listing $listing): bool
    {
        return $listing->user->isNotSuspended() && $listing->approved;
    }

    public function create(User $user): bool
    {
        return $user->isNotSuspended();
    }

    public function modify(User $user, Listing $listing): bool
    {
        return $user->isNotSuspended() && $user->id === $listing->user_id;
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }
}
