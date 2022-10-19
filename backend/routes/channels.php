<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('developers.deliveries', function ($developer) {
    return ['developer_id' => $developer->id];
}, ['guards' => ['developer']]);

Broadcast::channel('deliveries.{cityId}', function ($driver, $cityId) {
    if($driver->city_id == $cityId) {
        return ['pub_id' => $driver->pub_id];
    }
});

Broadcast::channel('driver.{pubId}', function ($driver, $pubId) {
    return $driver->pub_id === $pubId;
});
