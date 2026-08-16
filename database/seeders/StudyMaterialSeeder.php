<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StudyMaterial;
use App\Models\User;
use App\Models\Branch;

class StudyMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (admin) for uploaded_by
        $admin = User::first();
        $branch = Branch::first();

        if (!$admin) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        $materials = [
            [
                'title' => 'Algebra Practice Problems',
                'description' => 'Comprehensive practice problems for Grade 10 algebra with solutions.',
                'subject' => 'Mathematics',
                'class_level' => 'SS1',
                'type' => 'PDF',
                'file_path' => 'materials/sample-algebra.pdf',
                'file_size' => 2621440, // 2.5 MB
                'duration' => null,
                'views' => 0,
                'downloads' => 45,
                'uploaded_by' => $admin->id,
                'branch_id' => $branch ? $branch->id : null,
                'is_active' => true,
            ],
            [
                'title' => 'Physics Lab Tutorial',
                'description' => 'Step-by-step guide for conducting physics experiments safely.',
                'subject' => 'Science',
                'class_level' => 'SS2',
                'type' => 'Video',
                'file_path' => 'materials/sample-physics.mp4',
                'file_size' => 52428800, // 50 MB
                'duration' => 932, // 15:32 in seconds
                'views' => 128,
                'downloads' => 0,
                'uploaded_by' => $admin->id,
                'branch_id' => $branch ? $branch->id : null,
                'is_active' => true,
            ],
            [
                'title' => 'English Grammar Notes',
                'description' => 'Comprehensive grammar rules and examples for advanced English.',
                'subject' => 'English',
                'class_level' => 'SS3',
                'type' => 'Presentation',
                'file_path' => 'materials/sample-grammar.pptx',
                'file_size' => 8589934, // 8.2 MB
                'duration' => null,
                'views' => 0,
                'downloads' => 67,
                'uploaded_by' => $admin->id,
                'branch_id' => $branch ? $branch->id : null,
                'is_active' => true,
            ],
            [
                'title' => 'History Timeline Worksheet',
                'description' => 'Interactive timeline worksheet for World War II events.',
                'subject' => 'History',
                'class_level' => 'SS2',
                'type' => 'Worksheet',
                'file_path' => 'materials/sample-history.docx',
                'file_size' => 1887437, // 1.8 MB
                'duration' => null,
                'views' => 0,
                'downloads' => 34,
                'uploaded_by' => $admin->id,
                'branch_id' => $branch ? $branch->id : null,
                'is_active' => true,
            ],
            [
                'title' => 'Computer Science Fundamentals',
                'description' => 'Introduction to programming concepts and basic algorithms.',
                'subject' => 'Computer Science',
                'class_level' => 'JSS3',
                'type' => 'PDF',
                'file_path' => 'materials/sample-computer.pdf',
                'file_size' => 3145728, // 3 MB
                'duration' => null,
                'views' => 0,
                'downloads' => 23,
                'uploaded_by' => $admin->id,
                'branch_id' => $branch ? $branch->id : null,
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            StudyMaterial::create($material);
        }

        $this->command->info('Study materials seeded successfully!');
    }
}
