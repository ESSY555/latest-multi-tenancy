<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\AcademicYear;
use App\Models\AcademicSemester;
use App\Models\AcademicTerm;
use App\Models\AcademicEvent;
use App\Models\AcademicHoliday;
use App\Models\AcademicExam;
use App\Models\Subject;
use App\Models\SchoolClass;
use Carbon\Carbon;

class AcademicCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {
            $this->createAcademicCalendarForBranch($branch);
        }
    }

    private function createAcademicCalendarForBranch($branch)
    {
        // Academic years, semesters, and terms are now created through the admin UI
        // at /academic-calendar/years/create by the Super Admin
        // This allows for dynamic management without hardcoded seeding
    }

    private function createAcademicEvents($academicYear, $branch)
    {
        $events = [
            [
                'title' => 'School Opening Ceremony',
                'description' => 'Annual school opening ceremony for all students and staff',
                'start_date' => '2024-09-02',
                'end_date' => '2024-09-02',
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'event_type' => 'ceremony',
                'location' => 'School Auditorium',
                'is_all_day' => false,
                'is_public' => true,
                'color' => '#ea580c',
                'priority' => 'high'
            ],
            [
                'title' => 'Parent-Teacher Meeting',
                'description' => 'First parent-teacher meeting of the academic year',
                'start_date' => '2024-10-15',
                'end_date' => '2024-10-15',
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'event_type' => 'meeting',
                'location' => 'School Hall',
                'is_all_day' => false,
                'is_public' => true,
                'color' => '#059669',
                'priority' => 'medium'
            ],
            [
                'title' => 'Sports Day',
                'description' => 'Annual sports day with various athletic competitions',
                'start_date' => '2024-11-20',
                'end_date' => '2024-11-20',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'event_type' => 'sports',
                'location' => 'School Ground',
                'is_all_day' => true,
                'is_public' => true,
                'color' => '#0891b2',
                'priority' => 'medium'
            ],
            [
                'title' => 'Cultural Festival',
                'description' => 'Annual cultural festival showcasing student talents',
                'start_date' => '2025-03-15',
                'end_date' => '2025-03-17',
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'event_type' => 'cultural',
                'location' => 'School Campus',
                'is_all_day' => true,
                'is_public' => true,
                'color' => '#be185d',
                'priority' => 'high'
            ]
        ];

        foreach ($events as $eventData) {
            $event = AcademicEvent::create(array_merge($eventData, [
                'academic_year_id' => $academicYear->id
            ]));

            // Attach to all classes if they exist
            $classes = SchoolClass::where('branch_id', $branch->id)->get();
            if ($classes->count() > 0) {
                $event->classes()->attach($classes->pluck('id'));
            }
        }
    }

    private function createAcademicHolidays($academicYear)
    {
        $holidays = [
            [
                'name' => 'Mid-Term Break',
                'description' => 'Short break between terms',
                'start_date' => '2024-12-21',
                'end_date' => '2025-01-05',
                'holiday_type' => 'break',
                'is_public_holiday' => false,
                'color' => '#0891b2'
            ],
            [
                'name' => 'Easter Holiday',
                'description' => 'Easter holiday break',
                'start_date' => '2025-04-18',
                'end_date' => '2025-04-21',
                'holiday_type' => 'holiday',
                'is_public_holiday' => true,
                'color' => '#dc2626'
            ],
            [
                'name' => 'Summer Vacation',
                'description' => 'Long summer vacation',
                'start_date' => '2025-07-01',
                'end_date' => '2025-08-31',
                'holiday_type' => 'vacation',
                'is_public_holiday' => false,
                'color' => '#059669'
            ]
        ];

        foreach ($holidays as $holidayData) {
            AcademicHoliday::create(array_merge($holidayData, [
                'academic_year_id' => $academicYear->id
            ]));
        }
    }

    private function createAcademicExams($academicYear, $branch)
    {
        $subjects = Subject::where('branch_id', $branch->id)->get();
        $classes = SchoolClass::where('branch_id', $branch->id)->get();

        if ($subjects->count() === 0 || $classes->count() === 0) {
            return;
        }

        $exams = [
            [
                'title' => 'First Term Examinations',
                'description' => 'End of first term examinations for all subjects',
                'exam_type' => 'midterm',
                'exam_date' => '2024-12-16',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'duration_minutes' => 180,
                'total_marks' => 100,
                'passing_marks' => 40,
                'is_published' => true,
                'is_online' => false,
                'location' => 'School Classrooms',
                'instructions' => 'Students must bring their own stationery. No electronic devices allowed.',
                'color' => '#2563eb'
            ],
            [
                'title' => 'Second Term Examinations',
                'description' => 'End of second term examinations for all subjects',
                'exam_type' => 'midterm',
                'exam_date' => '2025-03-24',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'duration_minutes' => 180,
                'total_marks' => 100,
                'passing_marks' => 40,
                'is_published' => true,
                'is_online' => false,
                'location' => 'School Classrooms',
                'instructions' => 'Students must bring their own stationery. No electronic devices allowed.',
                'color' => '#2563eb'
            ],
            [
                'title' => 'Final Examinations',
                'description' => 'End of year final examinations for all subjects',
                'exam_type' => 'final',
                'exam_date' => '2025-06-23',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'duration_minutes' => 180,
                'total_marks' => 100,
                'passing_marks' => 40,
                'is_published' => true,
                'is_online' => false,
                'location' => 'School Classrooms',
                'instructions' => 'Students must bring their own stationery. No electronic devices allowed.',
                'color' => '#dc2626'
            ]
        ];

        foreach ($exams as $examData) {
            $exam = AcademicExam::create(array_merge($examData, [
                'academic_year_id' => $academicYear->id,
                'academic_term_id' => $examData['exam_type'] === 'final' ? 3 : ($examData['exam_type'] === 'midterm' ? 1 : 2),
                'subject_id' => $subjects->random()->id,
                'school_class_id' => $classes->random()->id
            ]));
        }
    }
}
