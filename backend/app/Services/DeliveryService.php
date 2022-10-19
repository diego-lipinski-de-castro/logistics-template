<?php

namespace App\Services;

use App\Helper;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeliveryService
{
    public function check(int $userId, array $destination, bool $returnPaid = false): mixed
    {
        $user = User::find($userId);

        if (blank($user)) {
            return null;
        }

        if (blank($destination) || count($destination) != 2) {
            return null;
        }

        $lat = $destination[0];
        $lng = $destination[1];

        $result = DB::select(<<<EOT
            SELECT
                st_within(
                    ST_GeomFromText('POINT($lng $lat)', 4326),
                    st_transform(users.area::geometry, 4326)
                ) as within
            FROM
                users
            WHERE
                id = $user->id;
        EOT);

        $within = $result[0]->within;

        if (! $within) {
            return null;
        }

        if ($user->charge_style == 'LINE') {
            $result = $this->_getLineResult($user, $lat, $lng);
        }

        if ($user->charge_style == 'ROUTE') {
            $result = $this->_getRouteResult($user, $lat, $lng);
        }

        if (! isset($result) || blank($result)) {
            return null;
        }

        if (! $returnPaid) {
            unset($result['paid']);
            unset($result['formatted_paid']);
        }

        return $result;
    }

    private function _getLineResult($user, $lat, $lng): ?array
    {
        $result = DB::select(<<<EOT
            SELECT
                st_distancesphere (
                    st_geomfromtext('POINT($lng $lat)', 4326),
                    addresses.position::geometry
                ) AS distance
            FROM
                users 
            INNER JOIN
                addresses
            ON
                users.id = addresses.user_id
            WHERE users.id = $user->id;
        EOT);

        $radius = $result[0]->distance;

        $radius = (int) ceil($radius / 1000);

        $result = $user->radiuses->firstWhere('rad', $radius);

        if (blank($result)) {
            return null;
        }

        return $result;
    }

    private function _getRouteResult($user, $lat, $lng): ?array
    {
        $origin = $user->address->position->getLat() .','. $user->address->position->getLng();

        $destination = "$lat,$lng";

        $result = (new RoutingService())->getRoute(
            $origin,
            $destination,
        );

        if (! $result->ok) {
            return null;
        }

        $radius = $result->data->legs[0]->distance->value;

        $radius = (int) ceil($radius / 1000);

        $rad = $user->radiuses->firstWhere('rad', $radius);

        if (blank($rad)) {
            // If blank, means the route got a higher radius than whats available
            // Now we get the last radius and add the price per kilometer

            $radiuses = $user->radiuses;

            $adt = $radiuses->pop();
            $last = $radiuses->last();

            $adtKm = ($radius - $last['rad']);

            $rad = [
                "rad" => $radius,
                "time" => $adt['time'],
                "paid" => $last['paid'] + ($adtKm * $adt['paid']),
                "charged" => $last['charged'] + ($adtKm * $adt['charged']),
                "formatted_paid" => Helper::toMoney($last['paid'] + ($adtKm * $adt['paid'])),
                "formatted_charged" => Helper::toMoney($last['charged'] + ($adtKm * $adt['charged'])),
            ];
        }

        return array_merge($rad, [
            'distance' => $result->data->legs[0]->distance->value,
            'duration' => $result->data->legs[0]->duration->value,

            'formatted_distance' => $result->data->legs[0]->distance->text,
            'formatted_duration' => $result->data->legs[0]->duration->text,

            'line' => $result->data->overview_polyline->points,

            'warnings' => $result->data->warnings,
        ]);
    }
}
