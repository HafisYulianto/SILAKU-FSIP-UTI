<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            'reports.view',

            // User management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Entity management
            'entities.view',
            'entities.create',
            'entities.edit',
            'entities.delete',

            // Record management
            'records.view',
            'records.create',
            'records.edit',
            'records.delete',

            // Own record management
            'own-records.view',
            'own-records.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Pimpinan: Read-only dashboard
        $pimpinan = Role::firstOrCreate(['name' => 'Pimpinan']);
        $pimpinan->givePermissionTo([
            'dashboard.view',
            'reports.view',
        ]);

        // Wakil Dekan: Same access as Pimpinan
        $wakilDekan = Role::firstOrCreate(['name' => 'Wakil Dekan']);
        $wakilDekan->givePermissionTo([
            'dashboard.view',
            'reports.view',
        ]);

        // BAAK: Full access (Super Admin)
        $baak = Role::create(['name' => 'BAAK']);
        $baak->givePermissionTo(Permission::all());

        // Kaprodi: View + CRUD records scoped to their prodi
        $kaprodi = Role::create(['name' => 'Kaprodi']);
        $kaprodi->givePermissionTo([
            'dashboard.view',
            'reports.view',
            'records.view',
            'records.create',
            'records.edit',
            'entities.view',
        ]);

        // Dosen: View + create/edit records in existing categories
        $dosen = Role::create(['name' => 'Dosen']);
        $dosen->givePermissionTo([
            'dashboard.view',
            'records.view',
            'records.create',
            'records.edit',
            'own-records.view',
            'own-records.edit',
            'entities.view',
        ]);
    }
}
