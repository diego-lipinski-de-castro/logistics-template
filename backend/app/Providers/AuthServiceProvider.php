<?php

namespace App\Providers;

use App\Models\Delivery;
use App\Models\Driver;
use App\Models\User;
use App\Policies\DeliveryPolicy;
use App\Policies\DriverPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Driver::class => DriverPolicy::class,
        Delivery::class => DeliveryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
