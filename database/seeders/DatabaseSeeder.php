<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\SchoolClass;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed super admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'gbollytee2k@yahoo.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Abiodun@2023'),
                'is_super_admin' => true,
            ]
        );

        // Default branch (idempotent)
        $branch = Branch::firstOrCreate(
            ['code' => 'MAIN'],
            [
                'name' => 'HQ',
                'city' => 'Abuja',
                'country' => 'Nigeria',
            ]
        );

        // Seed a teacher and assign to branch
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@benzaleeschool.test'],
            [
                'name' => 'John Doe',
                'password' => bcrypt('password'),
            ]
        );
        $branch->users()->syncWithoutDetaching([$teacher->id => ['role' => 'teacher']]);

        // Create a class and assign teacher
        $class = SchoolClass::create([
            'branch_id' => $branch->id,
            'name' => 'Grade 5 - A',
            'grade_level' => 'Grade 5',
            'academic_year' => '2024/2025',
        ]);
        $class->teachers()->syncWithoutDetaching([$teacher->id]);

        // Seed grade scales
        $this->call([
            GradeScaleSeeder::class,
        ]);

        // Seed academic calendar
        $this->call([
            AcademicCalendarSeeder::class,
        ]);

        // Seed teacher reports
        $this->call([
            TeacherReportSeeder::class,
        ]);
    }
}
