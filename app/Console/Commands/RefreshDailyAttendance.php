<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchoolClass;
use App\Models\Enrollment;
use App\Models\Attendance;
use Carbon\Carbon;

class RefreshDailyAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:refresh-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh daily attendance for all classes - ensures form teachers have fresh attendance sheets each day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting daily attendance refresh...');
        
        $today = Carbon::today()->format('Y-m-d');
        $classes = SchoolClass::with('enrollments.student')->get();
        
        $totalClasses = $classes->count();
        $totalStudents = 0;
        $attendanceRecordsCreated = 0;
        
        $this->info("Processing {$totalClasses} classes...");
        
        foreach ($classes as $class) {
            $students = $class->enrollments;
            $totalStudents += $students->count();
            
            $this->line("Processing class: {$class->name} ({$students->count()} students)");
            
            // Check if attendance already exists for today
            $existingAttendance = Attendance::where('school_class_id', $class->id)
                ->where('date', $today)
                ->count();
            
            if ($existingAttendance > 0) {
                $this->line("  ✓ Attendance already recorded for today");
                continue;
            }
            
            // Create attendance records for each student (default to present)
            foreach ($students as $enrollment) {
                Attendance::create([
                    'school_class_id' => $class->id,
                    'student_id' => $enrollment->student_id,
                    'date' => $today,
                    'status' => 'present', // Default status
                ]);
                $attendanceRecordsCreated++;
            }
            
            $this->line("  ✓ Created {$students->count()} attendance records");
        }
        
        $this->info("Daily attendance refresh completed!");
        $this->info("Total classes processed: {$totalClasses}");
        $this->info("Total students: {$totalStudents}");
        $this->info("New attendance records created: {$attendanceRecordsCreated}");
        $this->info("Date: {$today}");
        
        return Command::SUCCESS;
    }
}
