<?php declare(strict_types=1);

/*
 * This file is part of the tenancy/tenancy package.
 *
 * (c) Daniël Klabbers <daniel@klabbers.email>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://laravel-tenancy.com
 * @see https://github.com/tenancy
 */

namespace Tenancy\Tests\Identification\Drivers\Console;

use Illuminate\Contracts\Console\Kernel;
use Tenancy\Identification\Contracts\ResolvesTenants;
use Tenancy\Identification\Drivers\Console\Providers\IdentificationProvider;
use Tenancy\Testing\TestCase;
use Tenancy\Tests\Identification\Drivers\Console\Mocks\Tenant;

class IdentifyByConsoleTest extends TestCase
{
    protected $additionalProviders = [IdentificationProvider::class];
    protected $additionalMocks = [__DIR__ . '/Mocks/factories/'];

    /** @var User */
    protected $user;

    /** @var Tenant */
    protected $tenant;

    protected function afterSetUp()
    {
        /** @var ResolvesTenants $resolver */
        $resolver = $this->app->make(ResolvesTenants::class);
        $resolver->addModel(Tenant::class);

        $this->tenant = factory(Tenant::class)->create();

        $this->app->make(Kernel::class)->command(
            'identifies',
            function () {
            }
        );
    }

    /**
     * @test
     */
    public function artisan_identifies_tenant()
    {
        $this->assertFalse($this->environment->isIdentified());

        $this->artisan('identifies', [
            '--tenant' => $this->tenant->name
        ]);

        $this->assertEquals($this->tenant->name, optional($this->environment->getTenant())->name);

        $this->assertTrue($this->environment->isIdentified());
    }

    /**
     * @test
     */
    public function artisan_does_not_identify_multiple()
    {
        $this->assertFalse($this->environment->isIdentified());

        $this->artisan('identifies', [
            '--tenant' => $this->tenant->name,
            '--tenant' => 'foo'
        ]);

        $this->assertNull(optional($this->environment->getTenant())->name);

        $this->assertTrue($this->environment->isIdentified());
    }
}
