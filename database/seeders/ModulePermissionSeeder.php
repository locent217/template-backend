<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'dashboard' => [
                'name' => 'Dashboard',
                'category' => 'general',
                'actions' => ['view'],
            ],

            'users' => [
                'name' => 'User Management',
                'category' => 'administration',
                'actions' => ['view', 'create', 'update', 'delete', 'manage_permissions'],
            ],

            'remittances' => [
                'name' => 'Remittances',
                'category' => 'transaction',
                'actions' => ['view', 'create', 'update', 'delete'],
            ],
        ];

        foreach ($modules as $code => $details) {
            $module = Module::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $details['name'],
                    'category' => $details['category'],
                    'active' => true,
                ]
            );

            foreach ($details['actions'] as $action) {
                Permission::updateOrCreate(
                    ['code' => "{$code}.{$action}"],
                    [
                        'module_id' => $module->id,
                        'name' => ucfirst($action) . ' ' . $details['name'],
                    ]
                );
            }
        }
    }
}
