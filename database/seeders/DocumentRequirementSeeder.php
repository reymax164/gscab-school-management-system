<?php

namespace Database\Seeders;

use App\Models\Enrollments\DocumentRequirement;
use Illuminate\Database\Seeder;

class DocumentRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultRequirements = [
            'Original Form 138 (Report Card)',
            'PSA Birth Certificate (Photocopy)',
            'Certificate of Good Moral Character',
            '2x2 Colored ID Picture (2 copies)',
            'ESC / QVR Voucher Certificate',
        ];

        foreach ($defaultRequirements as $name) {
            DocumentRequirement::firstOrCreate(
                ['name' => $name],
                ['is_active' => true]
            );
        }
    }
}