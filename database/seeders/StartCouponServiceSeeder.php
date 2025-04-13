<?php

namespace Database\Seeders;

use App\Models\Internal\Service;
use Illuminate\Database\Seeder;

class StartCouponServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::create([
            'name' => 'Movimentações +100',
            'code_service' => 'mv+100',
        ]);
        Service::create([
            'name' => 'Movimentações +200',
            'code_service' => 'mv+200',
        ]);
        Service::create([
            'name' => 'Movimentações +1000',
            'code_service' => 'mv+1000',
        ]);
        Service::create([
            'name' => 'Membros +100',
            'code_service' => 'mb+100',
        ]);
        Service::create([
            'name' => 'Membros +200',
            'code_service' => 'mb+200',
        ]);
        Service::create([
            'name' => 'Membros +1000',
            'code_service' => 'mb+1000',
        ]);
    }
}
