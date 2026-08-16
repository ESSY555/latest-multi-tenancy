<?php

namespace App\Models;

use App\Traits\BelongsToBranch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Notification extends Model
{
    use BelongsToBranch;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'title',
        'message',
        'type',
        'is_read',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array'
    ];

    // Notification types
    const TYPE_ASSIGNMENT = 'assignment';
    const TYPE_RESULT = 'result';
    const TYPE_ATTENDANCE = 'attendance';
    const TYPE_ANNOUNCEMENT = 'announcement';
    const TYPE_LESSON_PLAN = 'lesson_plan';
    const TYPE_SYSTEM = 'system';
    const TYPE_TEACHER_REPORT = 'teacher_report';
    const TYPE_ASSIGNMENT_PUBLISH = 'assignment_publish';
    const TYPE_ASSIGNMENT_SUBMISSION = 'assignment_submission';
    const TYPE_ASSIGNMENT_REVIEW = 'assignment_review';
    const TYPE_EXAM_TIMETABLE = 'exam_timetable';

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Create notification for multiple users
    public static function createForUsers($userIds, $data)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $payload = $data['data'] ?? null;
            $notifications[] = [
                'user_id' => $userId,
                'branch_id' => $data['branch_id'],
                'title' => $data['title'],
                'message' => $data['message'],
                'type' => $data['type'],
                // insert() bypasses $casts — store JSON for the data column
                'data' => is_array($payload) ? json_encode($payload) : $payload,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return static::insert($notifications);
    }

    // Create assignment notification
    public static function createAssignmentNotification($assignment, $studentIds)
    {
        return static::createForUsers($studentIds, [
            'branch_id' => $assignment->branch_id,
            'title' => 'New Assignment: ' . $assignment->title,
            'message' => 'You have a new assignment in ' . $assignment->subject->name,
            'type' => self::TYPE_ASSIGNMENT,
            'data' => [
                'assignment_id' => $assignment->id,
                'subject' => $assignment->subject->name,
                'due_date' => $assignment->due_date?->format('Y-m-d'),
                'class' => $assignment->schoolClass->name
            ]
        ]);
    }

    // Create result notification
    public static function createResultNotification($result, $studentId)
    {
        return static::create([
            'user_id' => $studentId,
            'branch_id' => $result->branch_id,
            'title' => 'New Result Available',
            'message' => 'Your result for ' . $result->subject->name . ' is now available',
            'type' => self::TYPE_RESULT,
            'data' => [
                'result_id' => $result->id,
                'subject' => $result->subject->name,
                'score' => $result->score,
                'grade' => $result->grade,
                'class' => $result->schoolClass->name
            ]
        ]);
    }

    // Assignment publish -> notify students in class
    public static function createAssignmentPublishNotification(\App\Models\Assignment $assignment, array $studentIds)
    {
        if (empty($studentIds)) return false;
        return static::createForUsers($studentIds, [
            'branch_id' => $assignment->schoolClass->branch_id,
            'title' => 'New Assignment: ' . $assignment->title,
            'message' => 'A new assignment has been published for ' . $assignment->schoolClass->name,
            'type' => self::TYPE_ASSIGNMENT_PUBLISH,
            'data' => [
                'assignment_id' => $assignment->id,
                'class' => $assignment->schoolClass->name,
                'due_date' => $assignment->due_date?->format('Y-m-d')
            ]
        ]);
    }

    // Assignment submission -> notify teacher
    public static function createAssignmentSubmissionNotification(\App\Models\AssignmentSubmission $submission)
    {
        $assignment = $submission->assignment;
        return static::create([
            'user_id' => $assignment->teacher_id,
            'branch_id' => $assignment->schoolClass->branch_id,
            'title' => 'New Assignment Submission',
            'message' => 'A student submitted: ' . $assignment->title,
            'type' => self::TYPE_ASSIGNMENT_SUBMISSION,
            'data' => [
                'assignment_id' => $assignment->id,
                'submission_id' => $submission->id,
                'student_id' => $submission->student_id,
                'submitted_at' => $submission->submitted_at?->format('Y-m-d H:i:s')
            ]
        ]);
    }

    // Assignment review outcome -> notify student
    public static function createAssignmentReviewNotification(\App\Models\AssignmentSubmission $submission)
    {
        $assignment = $submission->assignment;
        return static::create([
            'user_id' => $submission->student_id,
            'branch_id' => $assignment->schoolClass->branch_id,
            'title' => 'Assignment Updated: ' . $assignment->title,
            'message' => 'Your submission has been ' . $submission->status . '.',
            'type' => self::TYPE_ASSIGNMENT_REVIEW,
            'data' => [
                'assignment_id' => $assignment->id,
                'submission_id' => $submission->id,
                'status' => $submission->status,
                'grade' => $submission->grade,
                'remarks' => $submission->remarks,
                'graded_at' => $submission->graded_at?->format('Y-m-d H:i:s')
            ]
        ]);
    }

    // Create attendance notification for parents
    public static function createAttendanceNotification($attendance, $parentIds)
    {
        return static::createForUsers($parentIds, [
            'branch_id' => $attendance->branch_id,
            'title' => 'Attendance Update',
            'message' => 'Your child\'s attendance has been updated',
            'type' => self::TYPE_ATTENDANCE,
            'data' => [
                'attendance_id' => $attendance->id,
                'student_name' => $attendance->student->name,
                'status' => $attendance->status,
                'date' => $attendance->date->format('Y-m-d'),
                'subject' => $attendance->subject->name
            ]
        ]);
    }

    // Create lesson plan notification for admins
    public static function createLessonPlanNotification($lessonPlan)
    {
        // Get all admin users in the branch
        $adminUsers = User::whereHas('branches', function($query) use ($lessonPlan) {
            $query->where('branches.id', $lessonPlan->branch_id)
                  ->whereIn('role', ['admin', 'super_admin']);
        })->pluck('id');

        if ($adminUsers->count() > 0) {
            return static::createForUsers($adminUsers->toArray(), [
                'branch_id' => $lessonPlan->branch_id,
                'title' => 'New Lesson Plan Submitted for Review',
                'message' => 'A lesson plan titled "' . $lessonPlan->lesson_title . '" has been submitted by ' . $lessonPlan->teacher->name . ' and requires your review.',
                'type' => self::TYPE_LESSON_PLAN,
                'data' => [
                    'lesson_plan_id' => $lessonPlan->id,
                    'teacher_name' => $lessonPlan->teacher->name,
                    'lesson_title' => $lessonPlan->lesson_title,
                    'subject_topic' => $lessonPlan->subject_topic,
                    'class_grade_level' => $lessonPlan->class_grade_level,
                    'submitted_at' => $lessonPlan->submitted_at?->format('Y-m-d H:i:s')
                ]
            ]);
        }
    }

    // Create lesson plan approval notification for teacher
    public static function createLessonPlanApprovalNotification($lessonPlan, $reviewer)
    {
        return static::create([
            'user_id' => $lessonPlan->teacher_id,
            'branch_id' => $lessonPlan->branch_id,
            'title' => 'Lesson Plan Approved',
            'message' => 'Your lesson plan "' . $lessonPlan->lesson_title . '" has been approved by ' . $reviewer->name . '.',
            'type' => self::TYPE_LESSON_PLAN,
            'data' => [
                'lesson_plan_id' => $lessonPlan->id,
                'lesson_title' => $lessonPlan->lesson_title,
                'reviewer_name' => $reviewer->name,
                'reviewed_at' => $lessonPlan->reviewed_at?->format('Y-m-d H:i:s'),
                'action' => 'approved'
            ]
        ]);
    }

    // Create lesson plan rejection notification for teacher
    public static function createLessonPlanRejectionNotification($lessonPlan, $reviewer, $rejectionReason)
    {
        return static::create([
            'user_id' => $lessonPlan->teacher_id,
            'branch_id' => $lessonPlan->branch_id,
            'title' => 'Lesson Plan Rejected',
            'message' => 'Your lesson plan "' . $lessonPlan->lesson_title . '" has been rejected by ' . $reviewer->name . '. Please review the feedback and resubmit.',
            'type' => self::TYPE_LESSON_PLAN,
            'data' => [
                'lesson_plan_id' => $lessonPlan->id,
                'lesson_title' => $lessonPlan->lesson_title,
                'reviewer_name' => $reviewer->name,
                'rejection_reason' => $rejectionReason,
                'reviewed_at' => $lessonPlan->reviewed_at?->format('Y-m-d H:i:s'),
                'action' => 'rejected'
            ]
        ]);
    }

    // Create teacher report notification for admins
    public static function createTeacherReportNotification($teacherReport)
    {
        // Get all admin users in the branch
        $adminUsers = User::whereHas('branches', function($query) use ($teacherReport) {
            $query->where('branches.id', $teacherReport->branch_id)
                  ->whereIn('role', ['admin', 'super_admin']);
        })->pluck('id');

        if ($adminUsers->count() > 0) {
            return static::createForUsers($adminUsers->toArray(), [
                'branch_id' => $teacherReport->branch_id,
                'title' => 'New Teacher Report Submitted for Review',
                'message' => 'A teacher report for ' . $teacherReport->formatted_report_date . ' has been submitted by ' . $teacherReport->teacher->name . ' and requires your review.',
                'type' => self::TYPE_TEACHER_REPORT,
                'data' => [
                    'teacher_report_id' => $teacherReport->id,
                    'teacher_name' => $teacherReport->teacher->name,
                    'report_date' => $teacherReport->formatted_report_date,
                    'submitted_at' => $teacherReport->submitted_at?->format('Y-m-d H:i:s')
                ]
            ]);
        }
    }

    // Create teacher report approval notification for teacher
    public static function createTeacherReportApprovalNotification($teacherReport, $reviewer)
    {
        return static::create([
            'user_id' => $teacherReport->teacher_id,
            'branch_id' => $teacherReport->branch_id,
            'title' => 'Teacher Report Approved',
            'message' => 'Your teacher report for ' . $teacherReport->formatted_report_date . ' has been approved by ' . $reviewer->name . '.',
            'type' => self::TYPE_TEACHER_REPORT,
            'data' => [
                'teacher_report_id' => $teacherReport->id,
                'report_date' => $teacherReport->formatted_report_date,
                'reviewer_name' => $reviewer->name,
                'reviewed_at' => $teacherReport->reviewed_at?->format('Y-m-d H:i:s'),
                'action' => 'approved'
            ]
        ]);
    }

    // Create teacher report rejection notification for teacher
    public static function createTeacherReportRejectionNotification($teacherReport, $reviewer, $rejectionReason)
    {
        return static::create([
            'user_id' => $teacherReport->teacher_id,
            'branch_id' => $teacherReport->branch_id,
            'title' => 'Teacher Report Rejected',
            'message' => 'Your teacher report for ' . $teacherReport->formatted_report_date . ' has been rejected by ' . $reviewer->name . '. Please review the feedback and resubmit.',
            'type' => self::TYPE_TEACHER_REPORT,
            'data' => [
                'teacher_report_id' => $teacherReport->id,
                'report_date' => $teacherReport->formatted_report_date,
                'reviewer_name' => $reviewer->name,
                'rejection_reason' => $rejectionReason,
                'reviewed_at' => $teacherReport->reviewed_at?->format('Y-m-d H:i:s'),
                'action' => 'rejected'
            ]
        ]);
    }

    // Create exam timetable notification for admins, teachers, and students
    public static function createExamTimetableNotification($exam, array $userIds)
    {
        if (empty($userIds)) {
            return false;
        }

        $scopeLabel = $exam->exam_scope === 'mock'
            ? ($exam->mockExam?->name ?? 'Mock')
            : ($exam->academicTerm?->name ?? 'Term');

        $subjectLabel = $exam->subject?->name ?? 'General';

        return static::createForUsers($userIds, [
            'branch_id' => $exam->academicYear->branch_id,
            'title' => 'New Exam Timetable Added',
            'message' => "{$scopeLabel} timetable scheduled for {$exam->exam_date->format('d M Y')} ({$subjectLabel})",
            'type' => self::TYPE_EXAM_TIMETABLE,
            'data' => [
                'exam_id' => $exam->id,
                'academic_year_id' => $exam->academic_year_id,
                'exam_scope' => $exam->exam_scope,
                'term_name' => $exam->academicTerm?->name,
                'mock_name' => $exam->mockExam?->name,
                'class_name' => $exam->schoolClass?->name,
                'subject_name' => $subjectLabel,
            ],
        ]);
    }
}
