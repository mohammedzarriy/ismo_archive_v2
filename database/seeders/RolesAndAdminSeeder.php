<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'agent']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@ismo.ma'],
            [
                'name'      => 'Admin ISMO',
                'password'  => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        $admin->assignRole('admin');
    }
}