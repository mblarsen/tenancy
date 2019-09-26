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

class CleanController
{
    public function __invoke()
    {
        // views
        $welcome = view('welcome');

        // cache
        cache()->store('welcome', $welcome);
        $cached = cache()->pull('welcome');

        if ($cached !== $welcome) {
            throw new \InvalidArgumentException('mismatching views');
        }

        //
    }
}
