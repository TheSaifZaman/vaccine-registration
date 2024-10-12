<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        foreach ($this->app['modules']->allEnabled() as $module) {
            $this->mapApiRoutes($module);
            $this->loadMigrationsFrom(module_path($module, 'Database/Migrations'));
        }
    }

    /**
     * @param $module
     * @return void
     */
    protected function mapApiRoutes($module): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('Modules\\' . $module . '\Http\Controllers')
            ->group(module_path($module, '/Routes/api.php'));
    }
}
