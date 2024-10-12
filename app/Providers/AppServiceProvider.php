<?php

namespace App\Providers;

use App\Helpers\LogHelper;
use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LogHelper::class, function () {
            return new LogHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        MySqlGrammar::macro('typeUlid', function () {
            return 'char(26)';
        });
    }
}
