<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Student Permissions
            'scholarships.list',
            'applications.create',
            'applications.upload-documents',
            'applications.view-own',
            'applications.view-details',
            'applications.view-logs',
            'awards.list-own',
            'disbursements.view',
            'disbursements.upload-receipts',
            'disbursements.view-details',

            // Admin Permissions - Scholarship Management
            'scholarships.create',
            'scholarships.update',
            'scholarships.delete',

            // Admin Permissions - Application Management
            'applications.list-all',
            'applications.view-any',
            'applications.review',

            // Admin Permissions - Cost Categories
            'cost-categories.create',
            'cost-categories.list',

            // Admin Permissions - Budget Management
            'budgets.create',
            'budgets.view',

            // Admin Permissions - Award Management
            'awards.create',
            'awards.create-schedules',

            // Admin Permissions - Disbursement Management
            'disbursements.mark-paid',
            'disbursements.filter',

            // Admin Permissions - Receipt Management
            'receipts.verify',

            // Admin Permissions - Reports
            'reports.scholarships',
            'reports.awards',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Assign Student Permissions
        $studentPermissions = [
            'scholarships.list',
            'applications.create',
            'applications.upload-documents',
            'applications.view-own',
            'applications.view-details',
            'applications.view-logs',
            'awards.list-own',
            'disbursements.view',
            'disbursements.upload-receipts',
            'disbursements.view-details',
        ];

        $studentRole->syncPermissions($studentPermissions);

        // Assign Admin Permissions (all permissions)
        // not in $studentPermissions
        $adminPermissions = array_diff($permissions, $studentPermissions);
        $adminRole->syncPermissions($adminPermissions);

        $users = [
            [
                'name' => 'Student User',
                'email' => 'student@example.com',
                'password' => bcrypt('password'),
                'role' => 'student',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            $user = \App\Models\User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            // Assign role to user
            $user->assignRole($userData['role']);
        }
    }
}
