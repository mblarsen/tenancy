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

namespace Tenancy\Hooks\Webserver\Hooks;

use Tenancy\Identification\Contracts\Tenant;

class Configuring
{
    /**
     * @var Tenant
     */
    public $tenant;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $view;

    public $data = [];

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * The path for the vHost file to be saved into.
     *
     * @param string $path
     *
     * @return $this
     */
    public function path(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * The template to be used to create the vHost file.
     *
     * @param string $view
     * @param array  $data
     *
     * @return $this
     */
    public function view(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;

        return $this;
    }
}
