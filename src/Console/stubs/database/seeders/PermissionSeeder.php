<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $definedPermissions = Permission::getPredefined();

        foreach ($definedPermissions as $table => $permissions) {
            foreach ($permissions as $permission) {
                Permission::query()->firstOrCreate(['name' => $table.'.'.$permission]);
            }
            $this->command->info("Seeded {$table} permissions");
        }
    }
}
