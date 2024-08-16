<?php

namespace Modules\Order\Providers;

use App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Order';

    public function boot(): void
    {
        $this->mergeConfigFrom(
            module_path('Order', 'config/settings.php'),
            'settings'
        );
        Route::middleware('web')->group(module_path('Order', 'routes/web.php'));
        $this->loadMigrations();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'order');
    }

    private function loadMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Migrations'));
    }
}
