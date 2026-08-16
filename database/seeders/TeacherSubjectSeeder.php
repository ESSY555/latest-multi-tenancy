<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class TeacherSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users who are teachers (have teaching classes)
        $teachers = User::whereHas('teachingClasses')->get();
        
        // Get all subjects
        $subjects = Subject::all();
        
        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No teachers or subjects found. Skipping teacher-subject seeding.');
            return;
        }
        
        // Clear existing teacher-subject relationships
        DB::table('teacher_subjects')->truncate();
        
        // Assign random subjects to teachers
        foreach ($teachers as $teacher) {
            // Assign 1-3 random subjects to each teacher
            $randomSubjects = $subjects->random(rand(1, min(3, $subjects->count())));
            
            foreach ($randomSubjects as $subject) {
                DB::table('teacher_subjects')->insert([
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subject->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info('Teacher-subject relationships seeded successfully!');
    }
}
