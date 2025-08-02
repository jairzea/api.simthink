<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'Admin']);
        $empresa = Role::create(['name' => 'Empresa']);

        $permissions = [
            'crear_investigacion',
            'ver_creditos',
            'gestionar_usuarios',
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::create(['name' => $perm]);
            $admin->givePermissionTo($permission);
            $empresa->givePermissionTo($permission); // o limitar
        }
    }
}