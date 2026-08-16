<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormTeacher;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Branch;

class FormTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first teacher user from branch_user table
        $teacherId = \DB::table('branch_user')
            ->where('role', 'teacher')
            ->first()
            ->user_id ?? null;
            
        if (!$teacherId) {
            $this->command->info('No teacher found. Please create a teacher user first.');
            return;
        }
        
        $teacher = User::find($teacherId);
        
        if (!$teacher) {
            $this->command->info('No teacher found. Please create a teacher user first.');
            return;
        }

        // Get the first class
        $class = SchoolClass::first();
        
        if (!$class) {
            $this->command->info('No class found. Please create a class first.');
            return;
        }

        // Check if form teacher already exists for this class
        $existingFormTeacher = FormTeacher::where('school_class_id', $class->id)
            ->where('is_active', true)
            ->first();
            
        if ($existingFormTeacher) {
            $this->command->info("Class {$class->name} already has a form teacher: {$existingFormTeacher->user->name}");
            return;
        }

        // Create form teacher assignment
        FormTeacher::create([
            'user_id' => $teacher->id,
            'school_class_id' => $class->id,
            'branch_id' => $class->branch_id,
            'is_active' => true,
            'start_date' => now(),
            'notes' => 'Assigned via seeder for testing',
        ]);

        // Update user role to form_teacher in branch_user table
        \DB::table('branch_user')
            ->where('user_id', $teacher->id)
            ->update(['role' => 'form_teacher']);

        $this->command->info("Form teacher assigned: {$teacher->name} -> {$class->name}");
    }
}
