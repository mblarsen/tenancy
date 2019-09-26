<?php

declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * Copyright Tenancy for Laravel & Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see https://tenancy.dev
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Performance\Http;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Routing\Router;
use Tenancy\Identification\Contracts\Tenant;
use Tenancy\Tests\Identification\Http\Mocks\Hostname;
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

    public function prepareTenantApplication()
    {
        $this->resolver->addModel(Hostname::class);

        $this->createSystemTable('hostnames', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fqdn');
            $table->timestamps();
        });

        $this->tenant = factory(Hostname::class)->create();
    }
}
