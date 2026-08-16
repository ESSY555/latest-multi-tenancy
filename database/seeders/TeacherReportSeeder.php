<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TeacherReport;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use Carbon\Carbon;

class TeacherReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample data
        $teachers = User::whereHas('branches', function($query) {
            $query->where('role', 'teacher');
        })->take(3)->get();

        $classes = SchoolClass::take(3)->get();
        $subjects = Subject::take(3)->get();

        if ($teachers->isEmpty() || $classes->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('Skipping TeacherReportSeeder: Not enough sample data');
            return;
        }

        $teacher = $teachers->first();
        $branchId = $teacher->branches->first()->id;

        // Create sample teacher reports
        $reports = [
            [
                'teacher_name' => $teacher->name,
                'report_date' => Carbon::now()->subDays(2),
                'classes_taught' => [$classes->first()->id],
                'subjects_taught' => [$subjects->first()->id],
                'topics_covered' => 'Introduction to Algebra: Basic concepts, variables, and equations. Students learned how to solve simple linear equations and understand the concept of variables.',
                'teaching_method' => 'lecture',
                'objectives_achieved' => true,
                'objectives_notes' => 'Most students were able to solve basic equations by the end of the lesson.',
                'student_participation' => 'good',
                'participation_notes' => 'Students actively participated in solving problems on the board.',
                'homework_assigned' => true,
                'homework_details' => 'Complete exercises 1-10 from Chapter 2. Due next class.',
                'class_activities' => 'Group problem-solving session where students worked in pairs.',
                'challenges_faced' => 'Some students struggled with the concept of negative numbers.',
                'materials_needed' => 'Additional practice worksheets for struggling students.',
                'additional_notes' => 'Overall, the lesson went well. Need to review negative numbers in the next session.',
                'status' => 'approved',
                'submitted_at' => Carbon::now()->subDays(1),
                'reviewed_at' => Carbon::now()->subDays(1),
                'reviewed_by' => User::whereHas('branches', function($query) use ($branchId) {
                    $query->where('branch_id', $branchId)->where('role', 'admin');
                })->first()->id ?? 1,
            ],
            [
                'teacher_name' => $teacher->name,
                'report_date' => Carbon::now()->subDays(1),
                'classes_taught' => [$classes->first()->id, $classes->get(1)->id ?? $classes->first()->id],
                'subjects_taught' => [$subjects->first()->id, $subjects->get(1)->id ?? $subjects->first()->id],
                'topics_covered' => 'Advanced Algebra: Quadratic equations and factoring. Covered different methods of solving quadratic equations.',
                'teaching_method' => 'group_work',
                'objectives_achieved' => true,
                'objectives_notes' => 'Students successfully learned factoring techniques.',
                'student_participation' => 'excellent',
                'participation_notes' => 'Excellent group collaboration and peer teaching.',
                'homework_assigned' => true,
                'homework_details' => 'Practice factoring problems from textbook.',
                'class_activities' => 'Students worked in groups to solve complex problems.',
                'challenges_faced' => 'Time management was challenging with the group activities.',
                'materials_needed' => 'More whiteboard space for group work.',
                'additional_notes' => 'Group work was very effective. Students learned from each other.',
                'status' => 'submitted',
                'submitted_at' => Carbon::now(),
            ],
            [
                'teacher_name' => $teacher->name,
                'report_date' => Carbon::now(),
                'classes_taught' => [$classes->first()->id],
                'subjects_taught' => [$subjects->first()->id],
                'topics_covered' => 'Review session for upcoming test. Covered all major topics from the unit.',
                'teaching_method' => 'discussion',
                'objectives_achieved' => false,
                'objectives_notes' => 'Need more time to cover all topics thoroughly.',
                'student_participation' => 'average',
                'participation_notes' => 'Some students were quiet during the review session.',
                'homework_assigned' => false,
                'homework_details' => null,
                'class_activities' => 'Q&A session and practice problems.',
                'challenges_faced' => 'Limited time to cover all material.',
                'materials_needed' => 'Additional review materials.',
                'additional_notes' => 'Will need to extend review into next class.',
                'status' => 'draft',
            ],
        ];

        foreach ($reports as $reportData) {
            TeacherReport::create([
                ...$reportData,
                'teacher_id' => $teacher->id,
                'branch_id' => $branchId,
            ]);
        }

        $this->command->info('Teacher reports seeded successfully!');
    }
}
