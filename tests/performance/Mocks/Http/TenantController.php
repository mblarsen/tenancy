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

namespace Tenancy\Tests\Performance\Mocks\Http;

use PHPUnit\Framework\RiskyTestError;
use Tenancy\Facades\Tenancy;

class TenantController extends CleanController
{
    public function __invoke()
    {
        $tenant = Tenancy::getTenant();

        if (!$tenant) {
            throw new RiskyTestError('No tenant identified');
        }

        parent::__invoke();
    }
}
