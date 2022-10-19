<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @return true
     */
    public function viewAny($authUser): bool
    {
        return true;
    }

    public function view($authUser, User $model): bool
    {
        return ! empty(array_intersect($model->cities_ids, $authUser->cities_ids));
    }

    /**
     * @return true
     */
    public function create($authUser): bool
    {
        return true;
    }

    public function update($authUser, User $model): bool
    {
        return ! empty(array_intersect($model->cities_ids, $authUser->cities_ids));
    }

    public function delete($authUser, User $model): bool
    {
        return ! empty(array_intersect($model->cities_ids, $authUser->cities_ids));
    }

    public function restore($authUser, User $model): bool
    {
        return ! empty(array_intersect($model->cities_ids, $authUser->cities_ids));
    }

    public function forceDelete($authUser, User $model): bool
    {
        return ! empty(array_intersect($model->cities_ids, $authUser->cities_ids));
    }
}
