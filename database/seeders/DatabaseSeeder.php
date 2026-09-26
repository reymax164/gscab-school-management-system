<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SystemSettingSeeder::class,
            DocumentRequirementSeeder::class,
            StaffSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,
            EnrollmentSeeder::class,
            SectionSeeder::class,
        ]);
    }
}
