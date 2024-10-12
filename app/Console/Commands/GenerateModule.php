<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GenerateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {module_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new module using nwidart modules package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->checkModulePackageInstalled()) {
            $this->error('nwidart/laravel-modules is not installed');
        }

        $moduleName = $this->argument('module_name');
        if (empty($moduleName)) {
            $this->error('Please provide a module name');
        }

        Artisan::call('module:make ' . $moduleName);
        Artisan::call('module:make-migration create_' . Str::snake(Str::plural($moduleName)) . '_table ' . $moduleName);
        Artisan::call('module:make-model ' . $moduleName . ' ' . $moduleName);
        Artisan::call('module:make-request Store' . $moduleName . 'Request ' . $moduleName);
        Artisan::call('module:make-request Update' . $moduleName . 'Request ' . $moduleName);
        Artisan::call('module:make-resource ' . $moduleName . 'Resource ' . $moduleName);

        $this->info($moduleName . ' Module Generated Successfully');

    }

    /**
     * @return bool
     */
    public function checkModulePackageInstalled(): bool
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        return !isset($composer['require']['nwidart/laravel-modules']);
    }
}
