<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LessonPlan;
use App\Models\User;
use App\Models\Branch;

class LessonPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a teacher user from branch_user table
        $teacher = \DB::table('branch_user')
            ->join('users', 'branch_user.user_id', '=', 'users.id')
            ->where('branch_user.role', 'teacher')
            ->select('users.*')
            ->first();
            
        $branch = Branch::first();

        if (!$teacher || !$branch) {
            return;
        }

        // Sample lesson plans
        $lessonPlans = [
            [
                'teacher_name' => $teacher->name,
                'subject_topic' => 'Mathematics',
                'class_grade_level' => 'Primary 4',
                'lesson_date' => now()->addDays(1),
                'duration' => '40 minutes',
                'lesson_title' => 'Introduction to Fractions',
                'objectives' => 'By the end of the lesson, students will be able to identify and compare simple fractions (½, ⅓, ¼).',
                'materials_resources' => 'Apple, knife, chalkboard, fraction chart, worksheet, markers',
                'lesson_introduction' => 'Teacher shows an apple and asks, "If I cut this into 2 equal parts, what do I have?" Students observe and respond.',
                'lesson_development' => "1. Teacher Activity: Demonstrate cutting apple into halves, then quarters\n2. Learner Activity: Students practice shading fractions on worksheet\n3. Method: Demonstration + guided practice",
                'assessment_evaluation' => 'Students solve 5 fraction problems individually and shade fractions on worksheet.',
                'conclusion' => 'Teacher summarizes fractions, assigns homework: "Draw and shade ½, ⅓, and ¼ of shapes."',
                'reflection' => 'Students were engaged with the hands-on activity. Need more practice with quarter fractions.',
                'status' => 'submitted',
                'submitted_at' => now()->subDays(2),
                'teacher_id' => $teacher->id,
                'branch_id' => $branch->id,
            ],
            [
                'teacher_name' => $teacher->name,
                'subject_topic' => 'English Literature',
                'class_grade_level' => 'Primary 5',
                'lesson_date' => now()->addDays(2),
                'duration' => '45 minutes',
                'lesson_title' => 'Reading Comprehension: Story Elements',
                'objectives' => 'Students will identify the main characters, setting, and plot of a short story.',
                'materials_resources' => 'Story book, whiteboard, markers, story map worksheet, colored pencils',
                'lesson_introduction' => 'Teacher reads aloud the first paragraph and asks students to predict what the story might be about.',
                'lesson_development' => "1. Teacher Activity: Read story aloud with expression\n2. Learner Activity: Students complete story map worksheet\n3. Method: Shared reading + individual work",
                'assessment_evaluation' => 'Students complete story map worksheet identifying characters, setting, and plot.',
                'conclusion' => 'Class discussion about favorite parts of the story. Homework: Read another story and identify elements.',
                'reflection' => 'Story map activity was effective. Students need more practice with plot identification.',
                'status' => 'approved',
                'submitted_at' => now()->subDays(5),
                'reviewed_at' => now()->subDays(3),
                'teacher_id' => $teacher->id,
                'branch_id' => $branch->id,
            ],
            [
                'teacher_name' => $teacher->name,
                'subject_topic' => 'Science',
                'class_grade_level' => 'Primary 6',
                'lesson_date' => now()->addDays(3),
                'duration' => '50 minutes',
                'lesson_title' => 'Water Cycle Experiment',
                'objectives' => 'Students will observe and describe the water cycle through a hands-on experiment.',
                'materials_resources' => 'Clear plastic container, water, food coloring, ice cubes, heat lamp, observation sheet',
                'lesson_introduction' => 'Teacher shows a glass of water and asks, "Where does rain come from?" Students share their ideas.',
                'lesson_development' => "1. Teacher Activity: Set up water cycle experiment\n2. Learner Activity: Students observe and record changes\n3. Method: Experiment + observation + discussion",
                'assessment_evaluation' => 'Students complete observation sheet and explain water cycle in their own words.',
                'conclusion' => 'Class creates a water cycle diagram. Homework: Draw the water cycle at home.',
                'reflection' => 'Experiment was successful. Students were excited to see condensation forming.',
                'status' => 'draft',
                'teacher_id' => $teacher->id,
                'branch_id' => $branch->id,
            ],
        ];

        foreach ($lessonPlans as $lessonPlan) {
            LessonPlan::create($lessonPlan);
        }
    }
}
