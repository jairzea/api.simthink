<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Investigation;
use App\Models\InvestigationFolder;
use App\Models\RagUpload;
use App\Models\SyntheticUser;
use App\Models\SyntheticResponse;
use App\Models\CreditTransaction;
use App\Models\Permission;
use App\Models\Role;

class SimthinkBulkSeeder extends Seeder
{
    public function run(): void
    {
        // Crear permisos
        $permisos = collect([
            'crear_investigacion',
            'ver_creditos',
            'gestionar_usuarios',
            'subir_archivos',
        ])->map(fn($perm) => Permission::updateOrCreate(
['name' => $perm],
    ['id' => Str::uuid(), 'guard_name' => 'web']
        ));

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $empresa = Role::firstOrCreate(['name' => 'Empresa']);

        $admin->syncPermissions(Permission::all());
        $empresa->syncPermissions(
Permission::whereIn('name', [
                'crear_investigacion',
                'ver_creditos',
                'subir_archivos',
            ])->get()
        );
    

        // Crear usuarios
        $adminUser = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@simthink.com',
            'password' => Hash::make('12345678'),
        ]);
        $adminUser->assignRole($admin);

        $empresaUsers = User::factory()->count(5)->create()->each(function ($user) use ($empresa) {
            $user->assignRole($empresa);

            // Crédito
            CreditTransaction::factory()->count(2)->for($user)->create();

            // Folders
            $folders = InvestigationFolder::factory()->count(2)->for($user)->create();

            // Investigaciones por usuario
            Investigation::factory()
                ->count(3)
                ->for($user)
                ->has(SyntheticUser::factory()
                    ->count(2)
                    ->has(SyntheticResponse::factory()->count(3)))
                ->has(RagUpload::factory()->count(1))
                ->create()
                ->each(function ($investigation) use ($folders) {
                    // Agregar investigación a folder aleatorio
                    $folders->random()->investigationFolderItems()->create([
                        'investigation_id' => $investigation->id,
                    ]);
                });
        });
    }
}