<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Delivery;
use App\Models\Developer;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    /**
     * @return null|true
     */
    public function before($authUser, $ability)
    {
        if ($authUser instanceof Developer) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($authUser)
    {
        return true;
    }

    public function view($authUser, Delivery $delivery): bool
    {
        if ($authUser instanceof Admin) {
            return ! empty(array_intersect($delivery->user->cities_ids, $authUser->cities_ids));
        }

        return $authUser->id === $delivery->user_id;
    }

    public function create($authUser): bool
    {
        if ($authUser instanceof Admin) {
            return false;
        }

        return true;
    }

    /**
     * @return false
     */
    public function update($authUser, Delivery $delivery): bool
    {
        return false;
    }

    /**
     * @return false
     */
    public function delete($authUser, Delivery $delivery): bool
    {
        return false;
    }

    /**
     * @return false
     */
    public function restore($authUser, Delivery $delivery): bool
    {
        return false;
    }

    /**
     * @return false
     */
    public function forceDelete($authUser, Delivery $delivery): bool
    {
        return false;
    }
}
