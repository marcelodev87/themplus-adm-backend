<?php

namespace Database\Seeders;

use App\Models\Internal\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super ADM',
            'email' => 'superadm@adm.com',
            'position' => 'admin',
            'password' => Hash::make('123456789'),
        ]);
    }
}
