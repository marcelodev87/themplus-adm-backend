<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internal\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super ADM',
            'email' => 'superadm@adm.com',
            'password' => Hash::make('123456789')
        ]);
    }
}
