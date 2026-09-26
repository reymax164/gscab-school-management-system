<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // hardcoded defaults until the admin overrides them via System Settings
        $defaults = [
            'tuition_fee' => '15000.00',
            'misc_fee' => '3500.00',
            'books_fee' => '2500.00',
        ];

        foreach ($defaults as $key => $value) {
            SystemSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
