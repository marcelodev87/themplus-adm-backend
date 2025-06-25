<?php

namespace Database\Seeders;

use App\Models\Internal\Setting;
use Illuminate\Database\Seeder;

class StartAutomaticSaveFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'key' => 'allow_feedback_saved',
            'value' => '0'
        ]);
    }
}
