<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class RoutingService
{
    private $config;
    private $client;

    public function __construct()
    {
        $this->config = collect(config('services.google'));

        $this->client = new Client([
            'base_uri' => $this->config->get('base_url'),
        ]);
    }

    public function getRoute(string $origin, string $destination): \stdClass|null
    {
        if (blank($origin) || blank($destination)) {
            return (object) [
                'ok' => false,
                'data' => null,
            ];
        }

        $response = $this->request('GET', 'maps/api/directions/json', [
            'origin' => $origin,
            'destination' => $destination,
            // 'avoid' => 'indoor',
            // 'mode' => 'driving', // bicycling,
            // 'traffic_model' => 'best_guess', // pessimistic, optimistic
            // 'transit_routing_preference' => 'less_walking', // fewer_transfers
            // 'units' => 'metric', // imperial
            
            // 'waypoints' =>
        ]);

        Log::debug(print_r($response, true));

        if (! $response->ok) {
            return (object) [
                'ok' => false,
                'data' => null,
            ];
        }

        if ($response->data->status != 'OK') {
            return (object) [
                'ok' => false,
                'data' => null,
            ];
        }

        return (object) [
            'ok' => true,
            'data' => $response->data->routes[0],
        ];
    }

    private function request(string $method, string $path, array $params = []): \stdClass
    {
        try {
            $response = $this->client->request($method, $path, [
                'query' => array_merge($params, [
                    'language' => 'pt-BR',
                    'key' => $this->config->get('api_key'),
                ]),
            ]);

            return (object) [
                'ok' => true,
                'data' => $this->response($response),
            ];
        } catch (ClientException $e) {
            return (object) [
                'ok' => false,
                'data' => $this->error($e),
            ];
        } catch (Exception $e) {
            return (object) [
                'ok' => false,
                'data' => $e->getMessage(),
            ];
        }
    }

    private function response($response)
    {
        return json_decode($response->getBody()->getContents());
    }

    private function error($e)
    {
        return json_decode((string) $e->getResponse()->getBody());
    }
}
