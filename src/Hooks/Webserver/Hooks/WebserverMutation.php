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

use Illuminate\Contracts\View\Factory;
use Tenancy\Lifecycle\Hook;
use Tenancy\Tenant\Events\Deleted;

class WebserverMutation extends Hook
{
    public function fire(): void
    {
        event($event = new Configuring($this->event->tenant));

        if ($this->event instanceof Deleted) {
            $this->delete($event);
        } else {
            $this->write($event);
        }
    }

    protected function write(Configuring $event)
    {
        /** @var Factory $factory */
        $factory = resolve(Factory::class);

        $view = $factory->file($event->view, array_merge(['tenant' => $this->event->tenant], $event->data));

        file_put_contents($event->path, $view->render());
    }

    protected function delete(Configuring $event)
    {
        if (file_exists($event->path)) {
            unlink($event->path);
        }
    }
}
