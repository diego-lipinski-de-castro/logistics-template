<?php

namespace App\Policies;

use App\Models\Developer;
use App\Models\Driver;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverPolicy
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

    public function view($authUser, Driver $driver): bool
    {
        return in_array($driver->city_id, $authUser->cities_ids);
    }

    /**
     * @return true
     */
    public function create($authUser): bool
    {
        return true;
    }

    public function update($authUser, Driver $driver): bool
    {
        return in_array($driver->city_id, $authUser->cities_ids);
    }

    public function delete($authUser, Driver $driver): bool
    {
        return in_array($driver->city_id, $authUser->cities_ids);
    }

    public function restore($authUser, Driver $driver): bool
    {
        return in_array($driver->city_id, $authUser->cities_ids);
    }

    public function forceDelete($authUser, Driver $driver): bool
    {
        return in_array($driver->city_id, $authUser->cities_ids);
    }
}
