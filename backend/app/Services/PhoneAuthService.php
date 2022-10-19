<?php

namespace App\Services;

use App\Helper;
use Exception;
use Illuminate\Support\Facades\Config;
use Log;
use Twilio\Rest\Client;

class PhoneAuthService
{
    private $client;
    private $config;

    public function __construct()
    {
        $this->config = collect(Config::get('services.twilio'));

        $this->client = new Client(
            $this->config->get('account_sid'),
            $this->config->get('auth_token'),
        );
    }

    public function verify(string $phoneNumber): ?\stdClass
    {
        $phoneNumber = Helper::toPhone($phoneNumber);

        try {
            $verification = $this->client
                ->verify
                ->v2
                ->services($this->config->get('verification_service_id'))
                ->verifications
                ->create($phoneNumber, 'sms');

            $response = [
                'phoneNumber' => $verification->to,
            ];

            return (object) $response;
        } catch (Exception $e) {
            Log::debug('PHONE AUTH SERVICE - VERIFY');
            Log::debug($phoneNumber);
            Log::debug($e->getMessage());

            return null;
        }
    }

    public function check(string $phoneNumber, string|int $code): ?\stdClass
    {
        $phoneNumber = Helper::toPhone($phoneNumber);

        try {
            $verificationCheck = $this->client
                ->verify
                ->v2
                ->services($this->config->get('verification_service_id'))
                ->verificationChecks
                ->create($code, [
                    'to' => $phoneNumber,
                ]);

            $response = [
                'phoneNumber' => $verificationCheck->to,
                'status' => $verificationCheck->status,
                'valid' => $verificationCheck->valid,
                'dateUpdated' => $verificationCheck->dateUpdated,
            ];

            return (object) $response;
        } catch (Exception $e) {
            Log::debug('PHONE AUTH SERVICE - CHECK');
            Log::debug($phoneNumber);
            Log::debug($e->getMessage());

            return null;
        }
    }
}
