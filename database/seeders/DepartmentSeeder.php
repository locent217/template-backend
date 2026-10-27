<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => 'Information Technology',
            'code' => 'INFORMATION_TECHNOLOGY'
        ]);

        Department::create([
            'name' => 'Audit',
            'code' => 'AUDIT'
        ]);

        Department::create([
            'name' => 'Account Management',
            'code' => 'ACCOUNT_MANAGEMENT'
        ]);

        Department::create([
            'name' => 'Claims',
            'code' => 'CLAIMS'
        ]);

        Department::create([
            'name' => 'Human Resource',
            'code' => 'HUMAN_RESOURCE'
        ]);

        Department::create([
            'name' => 'Executive',
            'code' => 'EXECUTIVE'
        ]);

        Department::create([
            'name' => 'Legal',
            'code' => 'LEGAL'
        ]);

        Department::create([
            'name' => 'External Operations',
            'code' => 'EXTERNAL_OPERATIONS'
        ]);
    }
}
