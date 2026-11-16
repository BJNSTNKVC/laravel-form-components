<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Workbench\App\Livewire\Playground;

class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register the workbench Livewire components.
     */
    public function boot(): void
    {
        Livewire::component('playground', Playground::class);
    }
}
