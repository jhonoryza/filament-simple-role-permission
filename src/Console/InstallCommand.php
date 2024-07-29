<?php

namespace Jhonoryza\Filament\RolePermission\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    protected $signature = 'filament-simple-role-permission:install';

    protected $description = 'install filament simple role permission';

    public function __construct(public Filesystem $filesystem)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // copy filament resources
        $this->filesystem->ensureDirectoryExists(app_path('Filament/Resources'));
        $this->filesystem->copyDirectory(__DIR__.'/stubs/app/Filament/Resources', app_path('Filament/Resources'));

        // copy models
        $this->filesystem->ensureDirectoryExists(app_path('Models'));
        $this->filesystem->copyDirectory(__DIR__.'/stubs/app/Models', app_path('Models'));

        // copy database seeder
        $this->filesystem->ensureDirectoryExists(base_path('database/seeders'));
        $this->filesystem->copyDirectory(__DIR__.'/stubs/database/seeders', base_path('database/seeders'));

        // copy database migrations
        $this->filesystem->ensureDirectoryExists(base_path('database/migrations'));
        $this->filesystem->copyDirectory(__DIR__.'/stubs/database/migrations', base_path('database/migrations'));

        $this->info('Simple Role Permission Filament Scaffolding installed successfully.');
    }
}
