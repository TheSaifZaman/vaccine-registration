<?php

namespace App\Providers;

use App\Database\CustomBlueprint;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class CustomBlueprintServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            Blueprint::class,
            CustomBlueprint::class
        );
    }
}
