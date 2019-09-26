<?php

namespace Tenancy\Tests\Performance\Http;

use Illuminate\Routing\Router;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Performance\Mocks\Http\CleanController;
use Tenancy\Tests\Performance\Mocks\Http\TenantController;
use Tenancy\Tests\Performance\TestCase;

class TenantIdentificationTest extends TestCase
{
    /**
     * @test
     */
    public function compare_controllers()
    {
        /** @var Router $router */
        $router = resolve(Router::class);

        $this->compare(function (Tenant $tenant = null) use ($router) {
            $router->get('/', CleanController::class);

            if ($tenant) {
                $router->get('/', TenantController::class);
            }

            $this->get('/');
        });
    }
}
