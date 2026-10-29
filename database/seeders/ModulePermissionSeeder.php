<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class ModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'dashboard' => ['name' => 'Dashboard', 'category' => 'general', 'actions' => ['view']],
            'users' => ['name' => 'User Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete', 'manage_permissions']],
            'departments' => ['name' => 'Department Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'user_types' => ['name' => 'User Type Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'islands' => ['name' => 'Island Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'territories' => ['name' => 'Territory Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'regions' => ['name' => 'Region Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'branches' => ['name' => 'Branch Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'modules' => ['name' => 'Module Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'permissions' => ['name' => 'Permission Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
            'department_user_type_permissions' => ['name' => 'Department User Type Permission Management', 'category' => 'administration', 'actions' => ['view', 'create', 'update', 'delete']],
        ];

        foreach ($modules as $code => $details) {
            $module = Module::updateOrCreate(
                ['code' => $code],
                ['name' => $details['name'], 'category' => $details['category'], 'active' => true]
            );

            foreach ($details['actions'] as $action) {
                Permission::updateOrCreate(
                    ['code' => "{$code}.{$action}"],
                    ['module_id' => $module->id, 'name' => ucfirst(str_replace('_', ' ', $action)) . ' ' . $details['name']]
                );
            }
        }
    }
}
