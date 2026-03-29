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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'manage users',
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Course management
            'manage courses',
            'view courses',
            'create courses',
            'edit courses',
            'delete courses',
            
            // Module management
            'manage modules',
            'view modules',
            'create modules',
            'edit modules',
            'delete modules',
            
            // Lesson management
            'manage lessons',
            'view lessons',
            'create lessons',
            'edit lessons',
            'delete lessons',
            
            // Enrollment management
            'manage enrollments',
            'view enrollments',
            'create enrollments',
            'delete enrollments',
            
            // Progress tracking
            'view progress',
            'track progress',
            
            // Admin panel access
            'access admin panel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo($permissions); // Admin gets all permissions

        $learner = Role::firstOrCreate(['name' => 'learner']);
        $learner->givePermissionTo([
            'view courses',
            'view modules',
            'view lessons',
            'view progress',
            'track progress',
        ]);
    }
}
