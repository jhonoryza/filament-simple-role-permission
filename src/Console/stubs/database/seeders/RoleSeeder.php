<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $predefinedRoles = User::getPredefined();
        foreach ($predefinedRoles as $role) {
            $role = Role::query()->firstOrCreate(['name' => $role]);
            $permissionss = Permission::all();
            $role->permissions()->sync($permissionss->pluck('id'));
        }
        $this->command->info('Seeded predefined roles');
    }
}
