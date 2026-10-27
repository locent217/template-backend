<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserTypeSeeder::class
        ]);
        $this->call([
            DepartmentSeeder::class
        ]);
        $saInfo = UserType::where('name', 'Super Administrator')->first();
        User::create([
            'username' => config('app.seed_admin_username'),
            'first_name' => config('app.seed_admin_firstname'),
            'last_name' => config('app.seed_admin_lastname'),
            'middle_name' => config('app.seed_admin_middlename'),
            'password' => Hash::make(config('app.seed_admin_password')),
            'email' => config('app.seed_admin_email'),
            'user_type_id' => $saInfo->id,
            'active' => true
        ]);

        $this->call([
            ModulePermissionSeeder::class
        ]);
    }
}
