<?php

namespace Jhonoryza\Filament\RolePermission;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Jhonoryza\Filament\RolePermission\Console\InstallCommand;
use Jhonoryza\Filament\RolePermission\Console\PolicyGeneratorCommand;

class RolePermissionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /**
         * Register the command.
         */
        $this->commands([
            InstallCommand::class,
            PolicyGeneratorCommand::class,
        ]);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $stubsPath = base_path('stubs');
        if (! is_dir($stubsPath)) {
            (new Filesystem)->makeDirectory($stubsPath);
        }
        $this->publishes([
            __DIR__.'/Console/stubs/policy' => base_path('stubs/simple-role-permission'),
        ], 'simple-role-permission-stubs');
    }
}
