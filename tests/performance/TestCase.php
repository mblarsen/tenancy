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

namespace Tenancy\Tests\Performance;

use Blackfire\Client;
use Blackfire\ClientConfiguration;
use Tenancy\Affects\Filesystems\Provider as Filesystems;
use Tenancy\Affects\Models\Provider as Models;
use Tenancy\Affects\Views\Provider as Views;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Http\Providers\IdentificationProvider as HttpIdentification;
use Tenancy\Testing\TestCase as Test;

abstract class TestCase extends Test
{
    public $tenant;

    protected $additionalProviders = [
        HttpIdentification::class,
        Filesystems::class,
        Models::class,
        Views::class,
    ];

    protected $additionalMocks = [
        __DIR__.'/../unit/Identification/Http/Mocks/factories/',
    ];

    /** @var Client */
    protected $blackfire;

    protected $resolver;

    protected function beforeBoot()
    {
        $this->blackfire = new Client(new ClientConfiguration(
            env('BLACKFIRE_CLIENT_ID'),
            env('BLACKFIRE_CLIENT_TOKEN')
        ));

        $this->resolver = resolve(ResolvesTenants::class);
    }

    protected function compare(callable $test)
    {
        $probe = $this->blackfire->createProbe();
        $probe->enable();

        // No identification.
        $test();

        $probe->disable();
        $cleanProfile = $this->blackfire->endProbe($probe);

        // Identify and re-run.
        $this->prepareTenantApplication();
        $probe = $this->blackfire->createProbe();

        $probe->enable();

        $test($this->tenant);

        $probe->disable();

        $profile = $this->blackfire->endProbe($probe);

        $this->assertLessThan($cleanProfile->getMainCost()->getWallTime(), $profile->getMainCost()->getWallTime());
    }

    abstract public function prepareTenantApplication();
}
