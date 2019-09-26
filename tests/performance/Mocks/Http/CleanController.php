<?php

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
