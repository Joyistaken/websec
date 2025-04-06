<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

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
            // User permissions
            'show_users',
            'edit_users',
            'delete_users',
            'add_credit',
            
            // Product permissions
            'add_products',
            'edit_products',
            'delete_products',
            'manage_stock',
            
            // Admin specific permissions
            'admin_users',
            'create_employees',
            
            // Customer specific permissions
            'buy_products',
            'view_purchases'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Admin role
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Employee role
        $employeeRole = Role::create(['name' => 'Employee']);
        $employeeRole->givePermissionTo([
            'show_users',
            'edit_users',
            'add_credit',
            'add_products',
            'edit_products',
            'delete_products',
            'manage_stock'
        ]);
        
        // Customer role
        $customerRole = Role::create(['name' => 'Customer']);
        $customerRole->givePermissionTo([
            'buy_products',
            'view_purchases'
        ]);
        
        // Create default admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('Admin123!'),
            'credit' => 0,
        ]);
        $admin->assignRole('Admin');
    }
} 