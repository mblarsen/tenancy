<?php

namespace Tenancy\Tests\Performance\Mocks\Http;

use PHPUnit\Framework\RiskyTestError;
use Tenancy\Environment;

class TenantController extends CleanController
{
    public function __invoke(Environment $environment)
    {
        $tenant = $environment->getTenant();

        if (! $tenant) {
            throw new RiskyTestError('No tenant identified');
        }

        parent::__invoke();
    }
}
