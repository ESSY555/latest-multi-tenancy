<?php

use Illuminate\Support\Facades\Route;
use App\Models\Branch;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherAttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ResultListController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentResultController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ELibraryController;
use App\Http\Controllers\Admin\AdminTeacherController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\FormTeacherAssignmentController;
use App\Http\Controllers\FormTeacherController;
use App\Http\Controllers\TeacherManagementController;
use App\Http\Controllers\SchoolRegistrationController;

// School registration — the first page a new school owner sees.
// Pre-tenant-context: no branch session required.
Route::get('/register-school', [SchoolRegistrationController::class, 'show'])->name('school.register');
Route::post('/register-school', [SchoolRegistrationController::class, 'store'])->name('school.register.store');

Route::get('/', function () {
    // Guests are directed to school registration; authenticated users see the homepage.
    if (! auth()->check()) {
        return redirect()->route('school.register');
    }
    return view('home');
});

// About page
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/academics', function () {
    return view('academics');
})->name('academics');

Route::get('/sample', function () {
    return view('sample');
})->name('sample');


use App\Http\Controllers\AnnualResultController;


Route::get('/student/results/preview', function () {
    // Attempt to find a student with results for a realistic preview
    $result = \App\Models\Result::with(['student', 'academicTerm.academicYear', 'schoolClass', 'subject'])->first();

    if (!$result) {
        return "No results found in the database. Please ensure you have students with assigned results to preview this template.";
    }

    $student = $result->student;
    $term = $result->academicTerm;

    // Fetch all approved results for this student and term
    $results = \App\Models\Result::where('student_id', $student->id)
        ->where('term_id', $term->id)
        ->with(['subject', 'schoolClass'])
        ->get();

    $totalScore = $results->sum('total');
    $averageScore = $results->avg('total') ?? 0;
    $totalSubjects = $results->count();

    // Render the PDF view directly for browser debugging
    return view('student.results-pdf', compact(
        'student',
        'results',
        'term',
        'totalScore',
        'averageScore',
        'totalSubjects'
    ));
})->name('student.results-preview');

Route::get('/student/results/annual/index', [AnnualResultController::class, 'index'])->name('student.results.annual.index');
Route::post('/student/results/annual/bulk-approve', [AnnualResultController::class, 'bulkApprove'])->name('student.results.annual.bulk-approve');
Route::post('/student/results/annual/bulk-disapprove', [AnnualResultController::class, 'bulkDisapprove'])->name('student.results.annual.bulk-disapprove');
Route::get('/student/results/annual/preview', [AnnualResultController::class, 'preview'])->name('student.results.annual-preview');
Route::get('/student/results/annual/{student}/{year}', [AnnualResultController::class, 'show'])->name('student.results.annual');
Route::get('/student/results/annual/{student}/{year}/print', [AnnualResultController::class, 'printAnnual'])->name('student.results.annual.print');
Route::post('/student/results/annual/summary/{student}/{year}', [AnnualResultController::class, 'updateSummary'])->name('student.results.annual.update-summary');
Route::post('/student/results/annual/approve/{student}/{year}', [AnnualResultController::class, 'approve'])->name('student.results.annual.approve');


// Contact page
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/branch/{branch}', [App\Http\Controllers\ContactController::class, 'showBranch'])->name('contact.branch');

// School News Routes
Route::get('/news', [App\Http\Controllers\SchoolNewsController::class, 'index'])->name('school-news.index');

// Super Admin only routes
Route::middleware(['auth', 'super.admin'])->group(function () {
    Route::get('/news/create', [App\Http\Controllers\SchoolNewsController::class, 'create'])->name('school-news.create');
    Route::post('/news', [App\Http\Controllers\SchoolNewsController::class, 'store'])->name('school-news.store');
    Route::get('/news/{schoolNews}/edit', [App\Http\Controllers\SchoolNewsController::class, 'edit'])->name('school-news.edit');
    Route::put('/news/{schoolNews}', [App\Http\Controllers\SchoolNewsController::class, 'update'])->name('school-news.update');
    Route::delete('/news/{schoolNews}', [App\Http\Controllers\SchoolNewsController::class, 'destroy'])->name('school-news.destroy');
    Route::get('/admin/news', [App\Http\Controllers\SchoolNewsController::class, 'admin'])->name('school-news.admin');

    // Bulk Email Routes
    Route::get('/admin/bulk-email', [App\Http\Controllers\BulkEmailController::class, 'index'])->name('bulk-email.index');
    Route::post('/admin/bulk-email/send', [App\Http\Controllers\BulkEmailController::class, 'send'])->name('bulk-email.send');
    Route::post('/admin/bulk-email/preview', [App\Http\Controllers\BulkEmailController::class, 'preview'])->name('bulk-email.preview');
});

Route::get('/news/{schoolNews}', [App\Http\Controllers\SchoolNewsController::class, 'show'])->name('school-news.show');

// Gallery Routes - Public access for viewing
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Gallery Routes - Super Admin only for management
Route::middleware(['auth', 'super.admin'])->prefix('gallery')->name('gallery.')->group(function () {
    Route::get('/admin', [GalleryController::class, 'admin'])->name('admin');
    Route::get('/create', [GalleryController::class, 'create'])->name('create');
    Route::post('/', [GalleryController::class, 'store'])->name('store');
    Route::get('/{gallery}/edit', [GalleryController::class, 'edit'])->name('edit');
    Route::put('/{gallery}', [GalleryController::class, 'update'])->name('update');
    Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
    Route::patch('/{gallery}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('toggle-status');
});

// Gallery show route (must come after admin routes to avoid conflicts)
Route::get('/gallery/{gallery}', [GalleryController::class, 'show'])->name('gallery.show');

// Resources Routes (Public Access - All users can view)
Route::prefix('resources')->name('resources.')->group(function () {
    Route::get('/', function () {
        return view('resources.index');
    })->name('index');

    Route::get('/syllabus', function () {
        return view('resources.syllabus');
    })->name('syllabus');

    Route::get('/timetables', function () {
        return view('resources.timetables');
    })->name('timetables');

    Route::get('/elibrary', [ELibraryController::class, 'index'])->name('elibrary');

    // E-Library reading/downloading with real book IDs
    Route::get('/elibrary/read/{book}', [ELibraryController::class, 'read'])->name('elibrary.read');
    Route::get('/elibrary/download/{book}', [ELibraryController::class, 'download'])->name('elibrary.download');

    // Fallback routes for title-based access (backward compatibility)
    Route::get('/elibrary/read-title/{title}', [ELibraryController::class, 'readByTitle'])->name('elibrary.read-title');
    Route::get('/elibrary/download-title/{title}', [ELibraryController::class, 'downloadByTitle'])->name('elibrary.download-title');

    Route::get('/materials', [App\Http\Controllers\StudyMaterialController::class, 'index'])->name('materials');
    Route::get('/materials/download/{material}', [App\Http\Controllers\StudyMaterialController::class, 'download'])->name('materials.download');
    Route::get('/materials/view/{material}', [App\Http\Controllers\StudyMaterialController::class, 'view'])->name('materials.view');
    Route::get('/materials/api/get', [App\Http\Controllers\StudyMaterialController::class, 'getMaterials'])->name('materials.api.get');
});




// Branch management (super admin only)
Route::middleware(['auth', 'super.admin'])->group(function () {
    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
    Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
    Route::post('/branches', [BranchController::class, 'store'])->name('branches.store');
    Route::get('/branches/{branch}', [BranchController::class, 'show'])->name('branches.show');
    Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('branches.edit');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('branches.destroy');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Public Academic Calendar View
Route::get('/academic-calendar/calendar', [AcademicCalendarController::class, 'calendar'])->name('academic-calendar.calendar');

// Admission Routes
Route::prefix('admissions')->name('admissions.')->group(function () {
    Route::get('/process', [AdmissionController::class, 'process'])->name('process');
    Route::get('/requirements', [AdmissionController::class, 'requirements'])->name('requirements');
    Route::get('/application', [AdmissionController::class, 'application'])->name('application');
    Route::post('/store', [AdmissionController::class, 'store'])->name('store');
});

// Admin Admission Routes (protected by admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin/admissions')->name('admin.admissions.')->group(function () {
    Route::get('/', [AdmissionController::class, 'adminIndex'])->name('index');
    Route::get('/{application}', [AdmissionController::class, 'adminShow'])->name('show');
    Route::put('/{application}/status', [AdmissionController::class, 'adminUpdateStatus'])->name('update-status');
    Route::delete('/{application}', [AdmissionController::class, 'adminDestroy'])->name('destroy');
    Route::post('/bulk-update-status', [AdmissionController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    Route::get('/export/csv', [AdmissionController::class, 'exportCsv'])->name('export-csv');
});

// Branch-aware routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();
        return back();
    })->name('notifications.read');

    Route::get('/notifications/{notification}/open', function (\App\Models\Notification $notification, Request $request) {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->markAsRead();

        $redirectTo = $request->query('redirect');
        if (is_string($redirectTo) && str_starts_with($redirectTo, '/')) {
            return redirect($redirectTo);
        }

        if (is_string($redirectTo) && filter_var($redirectTo, FILTER_VALIDATE_URL)) {
            $target = parse_url($redirectTo);
            $app = parse_url(config('app.url'));
            $sameHost = isset($target['host'], $app['host']) && strcasecmp($target['host'], $app['host']) === 0;

            if ($sameHost) {
                $path = $target['path'] ?? '/';
                $query = isset($target['query']) ? '?' . $target['query'] : '';
                return redirect($path . $query);
            }
        }

        return redirect()->route('dashboard');
    })->name('notifications.open');

    Route::post('/notifications/read-all', function () {
        \App\Models\Notification::forUser(auth()->id())
            ->unread()
            ->update(['is_read' => true]);
        return back();
    })->name('notifications.read-all');

    // Branch selection routes (Accessible once logged in, but before branch constraint)
    Route::get('/select-branch', function () {
        $branches = \App\Models\Branch::orderBy('name')->get();
        return view('select-branch', compact('branches'));
    })->name('dashboard.select-branch');

    Route::post('/select-branch', function (Request $request) {
        $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
        ]);
        session(['current_branch_id' => $request->branch_id]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('dashboard');
    })->name('dashboard.select-branch.post');

    // Admin Teacher Management Routes
    Route::middleware(['auth', 'admin'])->prefix('admin/teachers')->name('admin.teachers.')->group(function () {
        Route::get('/', [AdminTeacherController::class, 'index'])->name('index');
        Route::get('/create', [AdminTeacherController::class, 'create'])->name('create');
        Route::post('/', [AdminTeacherController::class, 'store'])->name('store');
        Route::get('/{teacher}', [AdminTeacherController::class, 'show'])->name('show');
        Route::get('/{teacher}/edit', [AdminTeacherController::class, 'edit'])->name('edit');
        Route::put('/{teacher}', [AdminTeacherController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [AdminTeacherController::class, 'destroy'])->name('destroy');
    });

    // Admin Student Management Routes
    Route::middleware(['auth', 'admin'])->prefix('admin/students')->name('admin.students.')->group(function () {
        Route::get('/export', [AdminStudentController::class, 'export'])->name('export');
        Route::get('/', [AdminStudentController::class, 'index'])->name('index');
        Route::get('/create', [AdminStudentController::class, 'create'])->name('create');
        Route::post('/', [AdminStudentController::class, 'store'])->name('store');
        Route::get('/{student}', [AdminStudentController::class, 'show'])->name('show');
        Route::get('/{student}/edit', [AdminStudentController::class, 'edit'])->name('edit');
        Route::put('/{student}', [AdminStudentController::class, 'update'])->name('update');
        Route::delete('/{student}', [AdminStudentController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['branch.selected'])->group(function () {
        Route::get('/exam-timetables', [App\Http\Controllers\Admin\ExamTimetableController::class, 'browse'])
            ->name('exam-timetables.view');

        // Lesson Plans (list status before {lessonPlan} so paths like /lesson-plans/status/draft are not captured by the wildcard)
        Route::get('/lesson-plans', [LessonPlanController::class, 'index'])->name('lesson-plans.index');
        Route::get('/lesson-plans/create', [LessonPlanController::class, 'create'])->name('lesson-plans.create');
        Route::post('/lesson-plans', [LessonPlanController::class, 'store'])->name('lesson-plans.store');
        Route::get('/lesson-plans/status/{status}', [LessonPlanController::class, 'byStatus'])->name('lesson-plans.by-status');
        Route::get('/lesson-plans/{lessonPlan}', [LessonPlanController::class, 'show'])->name('lesson-plans.show');
        Route::get('/lesson-plans/{lessonPlan}/edit', [LessonPlanController::class, 'edit'])->name('lesson-plans.edit');
        Route::put('/lesson-plans/{lessonPlan}', [LessonPlanController::class, 'update'])->name('lesson-plans.update');
        Route::delete('/lesson-plans/{lessonPlan}', [LessonPlanController::class, 'destroy'])->name('lesson-plans.destroy');
        Route::post('/lesson-plans/{lessonPlan}/submit', [LessonPlanController::class, 'submit'])->name('lesson-plans.submit');
        Route::post('/lesson-plans/{lessonPlan}/approve', [LessonPlanController::class, 'approve'])->name('lesson-plans.approve');
        Route::post('/lesson-plans/{lessonPlan}/reject', [LessonPlanController::class, 'reject'])->name('lesson-plans.reject');
        Route::delete('/lesson-plans/{lessonPlan}/attachments/{attachment}', [LessonPlanController::class, 'destroyAttachment'])->name('lesson-plans.attachments.destroy');

        // Subjects
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
        Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::get('/subjects/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
        Route::put('/subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

        // Assignments
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
        Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
        Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
        Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
        Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
        Route::post('/assignments/{assignment}/publish', [AssignmentController::class, 'publish'])->name('assignments.publish');
        Route::post('/assignments/{assignment}/unpublish', [AssignmentController::class, 'unpublish'])->name('assignments.unpublish');
        Route::get('/assignments-stats', [AssignmentController::class, 'stats'])->name('assignments.stats');

        // Attendance
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::post('/attendance/students', [AttendanceController::class, 'getStudents'])->name('attendance.students');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

        // Teacher Attendance
        Route::get('/teacher-attendance', [TeacherAttendanceController::class, 'index'])->name('teacher-attendance.index');
        Route::get('/teacher-attendance/create', [TeacherAttendanceController::class, 'create'])->name('teacher-attendance.create');
        Route::post('/teacher-attendance', [TeacherAttendanceController::class, 'store'])->name('teacher-attendance.store');
        Route::get('/teacher-attendance/daily-view', [TeacherAttendanceController::class, 'teacherDailyView'])->name('teacher-attendance.daily-view');
        Route::get('/teacher-attendance/weekly-summary', [TeacherAttendanceController::class, 'weeklySummary'])->name('teacher-attendance.weekly-summary');
        Route::get('/teacher-attendance/monthly-summary', [TeacherAttendanceController::class, 'monthlySummary'])->name('teacher-attendance.monthly-summary');
        Route::get('/teacher-attendance/my-attendance', [TeacherAttendanceController::class, 'teacherView'])->name('teacher-attendance.teacher-view');
        Route::get('/teacher-attendance/{teacherAttendance}', [TeacherAttendanceController::class, 'show'])->name('teacher-attendance.show');
        Route::get('/teacher-attendance/{teacherAttendance}/edit', [TeacherAttendanceController::class, 'edit'])->name('teacher-attendance.edit');
        Route::put('/teacher-attendance/{teacherAttendance}', [TeacherAttendanceController::class, 'update'])->name('teacher-attendance.update');
        Route::delete('/teacher-attendance/{teacherAttendance}', [TeacherAttendanceController::class, 'destroy'])->name('teacher-attendance.destroy');

        // Teacher Management Routes (Admin and Super Admin only)
        Route::get('/teacher-management', [App\Http\Controllers\TeacherManagementController::class, 'index'])->name('teacher-management.index');
        Route::get('/teacher-management/{teacher}', [App\Http\Controllers\TeacherManagementController::class, 'show'])->name('teacher-management.show');
        Route::post('/teacher-management/{teacher}/activate', [App\Http\Controllers\TeacherManagementController::class, 'activate'])->name('teacher-management.activate');
        Route::post('/teacher-management/{teacher}/deactivate', [App\Http\Controllers\TeacherManagementController::class, 'deactivate'])->name('teacher-management.deactivate');
        Route::delete('/teacher-management/{teacher}', [App\Http\Controllers\TeacherManagementController::class, 'destroy'])->name('teacher-management.destroy');

        // Users - Accessible by both Super Admin and Branch Admin (with branch scope)
        Route::middleware(['admin'])->group(function () {
            Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
            Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
            Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
            Route::get('/students', [StudentController::class, 'index'])->name('students.index');
            Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
            Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        });

        // User Management (Super Admin Only)
        Route::middleware(['super.admin'])->prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        });

        // Class Management (Super Admin Only)
        Route::middleware(['super.admin'])->prefix('classes')->name('classes.')->group(function () {
            Route::get('/', [App\Http\Controllers\ClassController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\ClassController::class, 'store'])->name('store');
            Route::get('/{class}', [App\Http\Controllers\ClassController::class, 'show'])->name('show');
            Route::get('/{class}/edit', [App\Http\Controllers\ClassController::class, 'edit'])->name('edit');
            Route::put('/{class}', [App\Http\Controllers\ClassController::class, 'update'])->name('update');
            Route::delete('/{class}', [App\Http\Controllers\ClassController::class, 'destroy'])->name('destroy');
        });

        // User Management Dashboard (Super Admin Only)
        Route::get('/user-management', function () {
            $users = \App\Models\User::whereHas('branches', function ($query) {
                $query->where('role', 'teacher');
            })->with('branches')->latest()->paginate(20)->withQueryString();

            return view('dashboard.user-management', compact('users'));
        })->name('user-management');

        // Admin User Management Dashboard (Branch Admin Only)
        Route::get('/admin-user-management', function (\Illuminate\Http\Request $request) {
            $currentBranchId = session('current_branch_id');
            $role = $request->query('role');
            $search = $request->query('search');

            $query = \App\Models\User::where(function ($q) use ($currentBranchId) {
                $q->whereHas('branches', function ($query) use ($currentBranchId) {
                    if ($currentBranchId) {
                        $query->where('branch_id', $currentBranchId);
                    }
                })->orWhere('is_super_admin', 1);
            });

            if ($role) {
                if ($role === 'admin') {
                    $query->where(function ($q) use ($currentBranchId) {
                        $q->where('is_super_admin', 1)
                          ->orWhereHas('branches', function ($bQ) use ($currentBranchId) {
                              $bQ->where('role', 'admin');
                              if ($currentBranchId) {
                                  $bQ->where('branch_id', $currentBranchId);
                              }
                          });
                    });
                } else {
                    $query->whereHas('branches', function ($bQ) use ($currentBranchId, $role) {
                        $bQ->where('role', $role);
                        if ($currentBranchId) {
                            $bQ->where('branch_id', $currentBranchId);
                        }
                    });
                }
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            $users = $query->with('branches')->latest()->paginate(20)->withQueryString();

            return view('dashboard.admin-user-management', compact('users'));
        })->name('admin-user-management');

        // Admin Teacher Management Dashboard (Branch Admin Only)
        Route::get('/admin-manage-teachers', [App\Http\Controllers\Admin\AdminTeacherController::class, 'index'])->name('admin-manage-teachers');

        // Admin User Management API Routes
        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::post('/', [App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('destroy');
        });


        // Admin Class Management API Routes
        Route::prefix('admin/classes')->name('admin.classes.')->group(function () {
            Route::get('/', function () {
                $currentBranchId = session('current_branch_id');
                $classes = \App\Models\SchoolClass::where('branch_id', $currentBranchId)
                    ->with(['branch', 'teachers', 'enrollments'])
                    ->latest()
                    ->paginate(20)
                    ->withQueryString();
                $currentAcademicYear = \App\Models\AcademicYear::getCurrentAcademicYearName($currentBranchId);
                return view('dashboard.admin-manage-classes', compact('classes', 'currentAcademicYear'));
            })->name('index');

            Route::post('/', function (Request $request) {
                try {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'grade_level' => 'nullable|string|max:50',
                        'academic_year' => 'nullable|string|max:50',
                    ]);

                    $currentBranchId = session('current_branch_id');

                    $class = \App\Models\SchoolClass::create([
                        'branch_id' => $currentBranchId,
                        'name' => $request->name,
                        'grade_level' => $request->grade_level,
                        'academic_year' => $request->academic_year,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Class created successfully',
                        'class' => $class->load('branch')
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error creating class: ' . $e->getMessage()
                    ], 500);
                }
            })->name('store');

            Route::get('/{class}', function ($classId) {
                try {
                    $currentBranchId = session('current_branch_id');
                    $class = \App\Models\SchoolClass::where('branch_id', $currentBranchId)
                        ->with(['branch', 'teachers', 'enrollments.student'])
                        ->findOrFail($classId);

                    return response()->json([
                        'success' => true,
                        'class' => $class
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error fetching class data: ' . $e->getMessage()
                    ], 500);
                }
            })->name('show');

            Route::get('/{class}/edit', function ($classId) {
                try {
                    $currentBranchId = session('current_branch_id');
                    $class = \App\Models\SchoolClass::where('branch_id', $currentBranchId)
                        ->with('branch')
                        ->findOrFail($classId);

                    return response()->json([
                        'success' => true,
                        'class' => $class
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error fetching class data: ' . $e->getMessage()
                    ], 500);
                }
            })->name('edit');

            Route::put('/{class}', function (Request $request, $classId) {
                try {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'grade_level' => 'nullable|string|max:50',
                        'academic_year' => 'nullable|string|max:50',
                    ]);

                    $currentBranchId = session('current_branch_id');
                    $class = \App\Models\SchoolClass::where('branch_id', $currentBranchId)
                        ->findOrFail($classId);

                    $class->update([
                        'name' => $request->name,
                        'grade_level' => $request->grade_level,
                        'academic_year' => $request->academic_year,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Class updated successfully',
                        'class' => $class->load('branch')
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating class: ' . $e->getMessage()
                    ], 500);
                }
            })->name('update');

            Route::delete('/{class}', function ($classId) {
                try {
                    $currentBranchId = session('current_branch_id');
                    $class = \App\Models\SchoolClass::where('branch_id', $currentBranchId)
                        ->findOrFail($classId);

                    if ($class->enrollments()->count() > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot delete class with enrolled students. Please remove all students first.'
                        ], 400);
                    }

                    $class->delete();

                    return response()->json([
                        'success' => true,
                        'message' => 'Class deleted successfully'
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error deleting class: ' . $e->getMessage()
                    ], 500);
                }
            })->name('destroy');
        });

        // API Routes for class subjects
        Route::get('/api/classes/{classId}/subjects', function ($classId) {
            try {
                $user = auth()->user();
                $class = \App\Models\SchoolClass::findOrFail($classId);
                $subjectsQuery = $class->subjects();

                // If teacher, limit to their subjects
                if ($user && ($user->hasRole('teacher') || $user->hasRole('form_teacher'))) {
                    $teacherSubjectIds = $user->subjects()->pluck('subjects.id');
                    $subjectsQuery->whereIn('subjects.id', $teacherSubjectIds);
                }

                return response()->json([
                    'success' => true,
                    'subjects' => $subjectsQuery->get(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading subjects: ' . $e->getMessage()
                ], 500);
            }
        });

        // API Route for class students (active enrollments)
        Route::get('/api/classes/{classId}/students', function ($classId) {
            try {
                $user = auth()->user();
                $class = \App\Models\SchoolClass::findOrFail($classId);

                $studentsQuery = \App\Models\User::whereHas('enrollments', function ($q) use ($classId) {
                    $q->where('school_class_id', $classId)->where('status', 'active');
                });

                // If teacher, limit to their students (unless they are a class teacher)
                if ($user && ($user->hasRole('teacher') || $user->hasRole('form_teacher'))) {
                    $isClassTeacher = $class->teachers()->where('users.id', $user->id)->exists();
                    if (!$isClassTeacher) {
                        $teacherSubjectIds = $user->subjects()->pluck('subjects.id');
                        $studentsQuery->whereHas('studentSubjects', function ($q) use ($teacherSubjectIds, $classId) {
                            $q->whereIn('subject_id', $teacherSubjectIds)
                                ->where('student_subjects.school_class_id', $classId);
                        });
                    }
                }

                $students = $studentsQuery->select('id', 'name', 'email')->orderBy('name')->get();

                return response()->json([
                    'success' => true,
                    'students' => $students,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading students: ' . $e->getMessage()
                ], 500);
            }
        });

        // Admin Student Management API Routes (separate from admin.students controller routes)
        Route::prefix('admin/students-api')->name('admin.students-api.')->group(function () {
            Route::get('/', function () {
                return view('dashboard.admin-manage-students');
            })->name('index');

            Route::post('/', function (Request $request) {
                try {
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255|unique:users',
                        'phone' => 'nullable|string|max:20',
                        'date_of_birth' => 'nullable|date',
                        'address' => 'nullable|string|max:500',
                        'guardian_name' => 'nullable|string|max:255',
                        'guardian_phone' => 'nullable|string|max:20',
                        'class_id' => 'nullable|exists:school_classes,id',
                        'subject_ids' => 'nullable|array',
                        'subject_ids.*' => 'exists:subjects,id',
                        'password' => 'required|string|min:6|confirmed',
                        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                    ]);

                    $currentBranchId = session('current_branch_id');
                    $currentAcademicYear = \App\Models\AcademicYear::getCurrentAcademicYearName($currentBranchId);

                    DB::beginTransaction();

                    // Handle profile photo
                    $photoPath = null;
                    if ($request->hasFile('profile_photo')) {
                        $file = $request->file('profile_photo');
                        \Log::info('Profile photo upload started (Store)', [
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getClientMimeType(),
                            'size' => $file->getSize()
                        ]);

                        $photoPath = $file->store('profile_photos', 'public');

                        if (!$photoPath) {
                            \Log::error('Profile photo failed to store on disk (Store)');
                            throw new \Exception('The profile photo failed to upload.');
                        }

                        \Log::info('Profile photo stored successfully (Store)', ['path' => $photoPath]);
                    }

                    // Create the student user
                    $student = \App\Models\User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'profile_photo' => $photoPath,
                        'password' => Hash::make($request->password),
                    ]);

                    // Assign student role to current branch
                    $student->branches()->attach($currentBranchId, [
                        'role' => 'student',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Create student profile if additional data is provided
                    if ($request->date_of_birth || $request->guardian_name || $request->guardian_phone) {
                        // Generate admission number
                        $admissionNumber = 'BIS/' . date('Y') . '/' . str_pad((string) $student->id, 4, '0', STR_PAD_LEFT);

                        \App\Models\StudentProfile::create([
                            'user_id' => $student->id,
                            'branch_id' => $currentBranchId,
                            'admission_number' => $admissionNumber,
                            'date_of_birth' => $request->date_of_birth,
                            'guardian_name' => $request->guardian_name,
                            'guardian_phone' => $request->guardian_phone,
                        ]);
                    }

                    // Enroll student in class if specified
                    if ($request->class_id) {
                        \App\Models\Enrollment::create([
                            'student_id' => $student->id,
                            'school_class_id' => $request->class_id,
                            'enrollment_date' => now(),
                            'status' => 'active',
                        ]);

                        // Assign student to subjects if specified
                        if ($request->subject_ids && is_array($request->subject_ids)) {
                            foreach ($request->subject_ids as $subjectId) {
                                DB::table('student_subjects')->insert([
                                    'student_id' => $student->id,
                                    'subject_id' => $subjectId,
                                    'school_class_id' => $request->class_id,
                                    'academic_year' => $currentAcademicYear,
                                    'status' => 'active',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Student created successfully',
                        'student' => $student->load(['branches', 'enrollments.schoolClass'])
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error creating student: ' . $e->getMessage()
                    ], 500);
                }
            })->name('store');

            Route::put('/{student}', function (Request $request, $studentId) {
                \Log::info('--- Student Edit Attempt Started ---', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'student_id' => $studentId,
                    'has_file' => $request->hasFile('profile_photo'),
                    'request_data' => $request->except(['profile_photo'])
                ]);

                try {
                    try {
                        $request->validate([
                            'name' => 'required|string|max:255',
                            'email' => 'required|string|email|max:255|unique:users,email,' . $studentId,
                            'phone' => 'nullable|string|max:20',
                            'address' => 'nullable|string|max:500',
                            'guardian_name' => 'nullable|string|max:255',
                            'guardian_phone' => 'nullable|string|max:20',
                            'class_id' => 'nullable|exists:school_classes,id',
                            'subject_ids' => 'nullable|array',
                            'subject_ids.*' => 'exists:subjects,id',
                            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                        ]);
                    } catch (\Illuminate\Validation\ValidationException $ve) {
                        \Log::warning('Student update validation failed', [
                            'student_id' => $studentId,
                            'errors' => $ve->errors()
                        ]);
                        throw $ve;
                    }

                    $currentBranchId = session('current_branch_id');
                    $currentAcademicYear = \App\Models\AcademicYear::getCurrentAcademicYearName($currentBranchId);

                    // Debug logging
                    \Log::info('Student Update Request', [
                        'student_id' => $studentId,
                        'class_id' => $request->class_id,
                        'subject_ids' => $request->subject_ids,
                        'current_branch_id' => $currentBranchId,
                        'current_academic_year' => $currentAcademicYear
                    ]);

                    DB::beginTransaction();

                    // Find the student
                    $student = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId) {
                        $query->where('branch_id', $currentBranchId)->where('role', 'student');
                    })->findOrFail($studentId);

                    // Update student information
                    $updateData = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                    ];

                    if ($request->hasFile('profile_photo')) {
                        $file = $request->file('profile_photo');
                        \Log::info('Profile photo update started (Update)', [
                            'student_id' => $studentId,
                            'original_name' => $file->getClientOriginalName(),
                            'mime_type' => $file->getClientMimeType(),
                            'size' => $file->getSize()
                        ]);

                        // Delete old photo if exists
                        if ($student->profile_photo) {
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($student->profile_photo)) {
                                \Illuminate\Support\Facades\Storage::disk('public')->delete($student->profile_photo);
                                \Log::info('Old profile photo deleted', ['path' => $student->profile_photo]);
                            }
                        }

                        $photoPath = $file->store('profile-photos', 'public');

                        if (!$photoPath) {
                            \Log::error('Profile photo failed to store on disk (Update)');
                            throw new \Exception('The profile photo failed to upload.');
                        }

                        $updateData['profile_photo'] = $photoPath;
                        \Log::info('New profile photo stored successfully (Update)', ['path' => $photoPath]);
                    }

                    $student->update($updateData);

                    // Update student profile
                    if ($student->studentProfile) {
                        $student->studentProfile->update([
                            'guardian_name' => $request->guardian_name,
                            'guardian_phone' => $request->guardian_phone,
                        ]);
                    }

                    // Handle class enrollment
                    if ($request->class_id) {
                        // Remove existing enrollments
                        \App\Models\Enrollment::where('student_id', $student->id)->delete();

                        // Create new enrollment
                        \App\Models\Enrollment::create([
                            'student_id' => $student->id,
                            'school_class_id' => $request->class_id,
                            'enrollment_date' => now(),
                            'status' => 'active',
                        ]);

                        // Handle subject assignments
                        // Remove existing subject assignments
                        DB::table('student_subjects')->where('student_id', $student->id)->delete();

                        // Add new subject assignments
                        if ($request->subject_ids && is_array($request->subject_ids)) {
                            \Log::info('Assigning subjects to student', [
                                'student_id' => $student->id,
                                'subject_ids' => $request->subject_ids,
                                'class_id' => $request->class_id
                            ]);

                            foreach ($request->subject_ids as $subjectId) {
                                DB::table('student_subjects')->insert([
                                    'student_id' => $student->id,
                                    'subject_id' => $subjectId,
                                    'school_class_id' => $request->class_id,
                                    'academic_year' => $currentAcademicYear,
                                    'status' => 'active',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }

                            \Log::info('Subjects assigned successfully', [
                                'student_id' => $student->id,
                                'assigned_count' => count($request->subject_ids)
                            ]);
                        } else {
                            \Log::info('No subjects to assign', [
                                'student_id' => $student->id,
                                'subject_ids' => $request->subject_ids
                            ]);
                        }
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Student updated successfully',
                        'student' => $student->load(['branches', 'enrollments.schoolClass', 'studentSubjects'])
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error updating student: ' . $e->getMessage()
                    ], 500);
                }
            })->name('update');

            Route::get('/{student}', function ($studentId) {
                try {
                    $currentBranchId = session('current_branch_id');
                    $student = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId) {
                        $query->where('branch_id', $currentBranchId)->where('role', 'student');
                    })->with(['branches', 'enrollments.schoolClass', 'studentProfile'])
                        ->findOrFail($studentId);

                    // Add profile data to the response
                    $studentData = $student->toArray();
                    if ($student->studentProfile) {
                        $studentData['date_of_birth'] = $student->studentProfile->date_of_birth;
                        $studentData['guardian_name'] = $student->studentProfile->guardian_name;
                        $studentData['guardian_phone'] = $student->studentProfile->guardian_phone;
                        $studentData['admission_number'] = $student->studentProfile->admission_number;
                    }

                                        $studentData['profile_photo'] = $student->profile_photo ? Storage::url($student->profile_photo) : null;
                    // Address is already in the user model, so it will be included automatically

                    return response()->json([
                        'success' => true,
                        'student' => $studentData
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error fetching student data: ' . $e->getMessage()
                    ], 500);
                }
            })->name('show');

            Route::get('/{student}/edit', function ($studentId) {
                try {
                    $currentBranchId = session('current_branch_id');
                    $student = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId) {
                        $query->where('branch_id', $currentBranchId)->where('role', 'student');
                    })->with(['enrollments.schoolClass', 'studentProfile', 'studentSubjects'])
                        ->findOrFail($studentId);

                    // Get the first enrolled class ID
                    $enrolledClassId = $student->enrollments->first()->school_class_id ?? null;

                    $studentData = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'phone' => $student->phone,
                        'address' => $student->address,
                        'profile_photo' => $student->profile_photo ? asset('storage/' . $student->profile_photo) : null,
                        'enrolled_class_id' => $enrolledClassId,
                        'assigned_subjects' => $student->studentSubjects->pluck('id')->toArray()
                    ];

                    // Add profile data if exists
                    if ($student->studentProfile) {
                        $studentData['guardian_name'] = $student->studentProfile->guardian_name;
                        $studentData['guardian_phone'] = $student->studentProfile->guardian_phone;
                    }

                    return response()->json([
                        'success' => true,
                        'student' => $studentData
                    ]);

                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error fetching student data: ' . $e->getMessage()
                    ], 500);
                }
            })->name('edit');


            Route::delete('/{student}', function ($studentId) {
                try {
                    $currentBranchId = session('current_branch_id');

                    DB::beginTransaction();

                    $student = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId) {
                        $query->where('branch_id', $currentBranchId)->where('role', 'student');
                    })->findOrFail($studentId);

                    // Remove student from current branch only
                    $student->branches()->detach($currentBranchId);

                    // If student has no more branches, delete the user
                    if ($student->branches()->count() === 0) {
                        $student->delete();
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Student removed from branch successfully'
                    ]);

                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error removing student: ' . $e->getMessage()
                    ], 500);
                }
            })->name('destroy');
        });

        // Class Management Dashboard (Super Admin Only)
        Route::get('/class-management', function () {
            $currentAcademicYear = \App\Models\AcademicYear::active()->first();
            return view('dashboard.class-management', compact('currentAcademicYear'));
        })->name('class-management');

        // Exam Timetable Management (Super Admin Only)
        Route::prefix('exam-timetables')->name('exam-timetables.')->group(function () {
            Route::get('/create', [App\Http\Controllers\Admin\ExamTimetableController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ExamTimetableController::class, 'store'])->name('store');

            Route::get('/{timetable}/edit', [App\Http\Controllers\Admin\ExamTimetableController::class, 'edit'])->name('edit');
            Route::put('/{timetable}', [App\Http\Controllers\Admin\ExamTimetableController::class, 'update'])->name('update');

            Route::delete('/{timetable}', [App\Http\Controllers\Admin\ExamTimetableController::class, 'destroy'])->name('destroy');
        });

        // Super Admin Exam Timetables Dashboard (with sidebar)
        Route::get('/admin/exam-timetables', [App\Http\Controllers\Admin\ExamTimetableController::class, 'index'])
            ->name('admin.exam-timetables.index');

        // Super Admin Syllabus Management Dashboard (with sidebar)
        Route::get('/admin/syllabus', [App\Http\Controllers\SyllabusController::class, 'index'])->name('admin.syllabus.index');

        // Super Admin E-Library Management Dashboard (with sidebar)
        Route::get('/admin/elibrary', function () {
            return view('admin.elibrary.index')->with('currentRole', 'super_admin');
        })->name('admin.elibrary.index');

        // Super Admin Study Materials Management Dashboard (with sidebar)
        Route::get('/admin/materials', function () {
            return view('admin.materials.index')->with('currentRole', 'super_admin');
        })->name('admin.materials.index');

        // Super Admin Syllabus CRUD Routes
        Route::prefix('admin/syllabus')->name('admin.syllabus.')->group(function () {
            Route::get('/create', [App\Http\Controllers\SyllabusController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\SyllabusController::class, 'store'])->name('store');
            Route::get('/{syllabus}/edit', [App\Http\Controllers\SyllabusController::class, 'edit'])->name('edit');
            Route::put('/{syllabus}', [App\Http\Controllers\SyllabusController::class, 'update'])->name('update');
            Route::delete('/{syllabus}', [App\Http\Controllers\SyllabusController::class, 'destroy'])->name('destroy');
        });

        // Syllabus Export Routes (Public - accessible to all users)
        Route::prefix('syllabus/export')->name('syllabus.export.')->group(function () {
            Route::get('/pdf', [App\Http\Controllers\SyllabusExportController::class, 'exportPdf'])->name('pdf');
            Route::get('/excel', [App\Http\Controllers\SyllabusExportController::class, 'exportExcel'])->name('excel');
        });

        // Super Admin E-Library CRUD Routes
        Route::prefix('admin/elibrary')->name('admin.elibrary.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ELibraryController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\ELibraryController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\ELibraryController::class, 'store'])->name('store');
            Route::get('/{book}', [App\Http\Controllers\Admin\ELibraryController::class, 'show'])->name('show');
            Route::get('/{book}/edit', [App\Http\Controllers\Admin\ELibraryController::class, 'edit'])->name('edit');
            Route::put('/{book}', [App\Http\Controllers\Admin\ELibraryController::class, 'update'])->name('update');
            Route::delete('/{book}', [App\Http\Controllers\Admin\ELibraryController::class, 'destroy'])->name('destroy');
        });

        // Super Admin Study Materials CRUD Routes
        Route::prefix('admin/materials')->name('admin.materials.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\StudyMaterialController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\StudyMaterialController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\StudyMaterialController::class, 'store'])->name('store');
            Route::get('/{material}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'show'])->name('show');
            Route::get('/{material}/edit', [App\Http\Controllers\Admin\StudyMaterialController::class, 'edit'])->name('edit');
            Route::put('/{material}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'update'])->name('update');
            Route::delete('/{material}', [App\Http\Controllers\Admin\StudyMaterialController::class, 'destroy'])->name('destroy');
            Route::patch('/{material}/toggle-status', [App\Http\Controllers\Admin\StudyMaterialController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Super Admin Teacher Activities (Super Admin Only)
        Route::get('/super-admin/teacher-activities/{teacher}', function ($teacherId) {
            $teacher = \App\Models\User::with([
                'branches',
                'teachingClasses.branch',
                'teachingClasses.subjects',
                'teachingClasses.enrollments',
                'teacherAssignments.schoolClass',
                'lessonPlans',
                'subjects'
            ])->findOrFail($teacherId);

            return view('super-admin.teacher-activities', compact('teacher'));
        })->name('super-admin.teacher-activities');

        // Super Admin Class Assignment Routes
        Route::post('/super-admin/assign-class/{teacher}', function ($teacherId, Request $request) {
            $request->validate([
                'class_id' => 'required|exists:school_classes,id',
            ]);

            $teacher = \App\Models\User::findOrFail($teacherId);
            $class = \App\Models\SchoolClass::findOrFail($request->class_id);

            // Check if teacher is already assigned to this class
            if (!$teacher->teachingClasses()->where('school_class_id', $request->class_id)->exists()) {
                $teacher->teachingClasses()->attach($request->class_id);
                return redirect()->back()->with('success', "Successfully assigned {$class->name} to {$teacher->name}");
            }

            return redirect()->back()->with('error', "Teacher is already assigned to {$class->name}");
        })->name('super-admin.assign-class');

        Route::post('/super-admin/unassign-class/{teacher}', function ($teacherId, Request $request) {
            $request->validate([
                'class_id' => 'required|exists:school_classes,id',
            ]);

            $teacher = \App\Models\User::findOrFail($teacherId);
            $class = \App\Models\SchoolClass::findOrFail($request->class_id);

            $teacher->teachingClasses()->detach($request->class_id);

            return redirect()->back()->with('success', "Successfully unassigned {$class->name} from {$teacher->name}");
        })->name('super-admin.unassign-class');

        // API route to get classes by branch
        Route::get('/api/branches/{branch}/classes', function ($branchId) {
            $classes = \App\Models\SchoolClass::where('branch_id', $branchId)
                ->orderBy('name')
                ->get(['id', 'name', 'grade_level']);

            return response()->json(['classes' => $classes]);
        });

        // API routes to get students and subjects by class
        Route::get('/api/classes/{schoolClass}/students', [App\Http\Controllers\Result\ResultController::class, 'getClassStudents']);
        Route::get('/api/classes/{schoolClass}/subjects', [App\Http\Controllers\Result\ResultController::class, 'getClassSubjects']);


        // Teacher Reports Routes
        Route::prefix('teacher-reports')->name('teacher-reports.')->group(function () {
            Route::get('/', [App\Http\Controllers\TeacherReportController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\TeacherReportController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\TeacherReportController::class, 'store'])->name('store');
            Route::get('/{teacherReport}', [App\Http\Controllers\TeacherReportController::class, 'show'])->name('show');
            Route::get('/{teacherReport}/edit', [App\Http\Controllers\TeacherReportController::class, 'edit'])->name('edit');
            Route::put('/{teacherReport}', [App\Http\Controllers\TeacherReportController::class, 'update'])->name('update');
            Route::delete('/{teacherReport}', [App\Http\Controllers\TeacherReportController::class, 'destroy'])->name('destroy');
            Route::post('/{teacherReport}/submit', [App\Http\Controllers\TeacherReportController::class, 'submit'])->name('submit');
            Route::post('/{teacherReport}/approve', [App\Http\Controllers\TeacherReportController::class, 'approve'])->name('approve');
            Route::post('/{teacherReport}/reject', [App\Http\Controllers\TeacherReportController::class, 'reject'])->name('reject');
            Route::get('/status/{status}', [App\Http\Controllers\TeacherReportController::class, 'byStatus'])->name('by-status');
        });

        // Teacher Score Sheet Routes
        Route::get('/teacher/score-sheet', [TeacherController::class, 'scoreSheet'])->name('teacher.score-sheet');
        Route::post('/teacher/save-scores', [TeacherController::class, 'saveScores'])->name('teacher.save-scores');

        // Dashboard Calendar View (with sidebar)
        Route::get('/dashboard/calendar', [AcademicCalendarController::class, 'calendar'])->name('dashboard.calendar');


        // Student Dashboard Routes
        Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/student/grades', [StudentDashboardController::class, 'grades'])->name('student.grades');
        Route::get('/student/attendance', [StudentDashboardController::class, 'attendance'])->name('student.attendance');
        Route::get('/student/assignments', [StudentDashboardController::class, 'assignments'])->name('student.assignments');
        Route::get('/student/announcements', [StudentDashboardController::class, 'announcements'])->name('student.announcements');

        // Student Results Routes
        Route::get('/student/results', [StudentResultController::class, 'viewResults'])->name('student.results');
        Route::get('/student/results/print', [StudentResultController::class, 'printResults'])->name('student.results.print');
        Route::get('/student/results/export/pdf', [StudentResultController::class, 'exportResultsPDF'])->name('student.results.export-pdf');
        Route::get('/student/results/export/excel', [StudentResultController::class, 'exportResultsExcel'])->name('student.results.export-excel');
        Route::get('/student/results/{subject}', [StudentResultController::class, 'viewResultBySubject'])->name('student.results.subject');
        Route::get('/student/results/{subject}/export/pdf', [StudentResultController::class, 'exportSubjectPDF'])->name('student.results.subject-export-pdf');

        // Student profile management
        Route::get('/student/profile', [\App\Http\Controllers\StudentProfileController::class, 'index'])->name('student.profile');
        Route::post('/student/profile/update', [\App\Http\Controllers\StudentProfileController::class, 'updateProfile'])->name('student.profile.update');
        Route::post('/student/profile/emergency-contact', [\App\Http\Controllers\StudentProfileController::class, 'updateEmergencyContact'])->name('student.profile.emergency-contact.update');
        Route::post('/student/profile/photo', [\App\Http\Controllers\StudentProfileController::class, 'updateProfilePhoto'])->name('student.profile.photo.update');
        Route::post('/student/profile/password', [\App\Http\Controllers\StudentProfileController::class, 'updatePassword'])->name('student.profile.password');
        Route::post('/student/profile/notifications', [\App\Http\Controllers\StudentProfileController::class, 'updateNotifications'])->name('student.profile.notifications');
        Route::delete('/student/profile/photo', [\App\Http\Controllers\StudentProfileController::class, 'deleteProfilePhoto'])->name('student.profile.photo.delete');

        // Super Admin profile management
        Route::get('/super-admin/profile', [\App\Http\Controllers\SuperAdminProfileController::class, 'index'])->name('super-admin.profile');
        Route::post('/super-admin/profile/update', [\App\Http\Controllers\SuperAdminProfileController::class, 'updateProfile'])->name('super-admin.profile.update');
        Route::post('/super-admin/profile/emergency-contact', [\App\Http\Controllers\SuperAdminProfileController::class, 'updateEmergencyContact'])->name('super-admin.profile.emergency-contact.update');
        Route::post('/super-admin/profile/photo', [\App\Http\Controllers\SuperAdminProfileController::class, 'updateProfilePhoto'])->name('super-admin.profile.photo.update');
        Route::post('/super-admin/profile/password', [\App\Http\Controllers\SuperAdminProfileController::class, 'updatePassword'])->name('super-admin.profile.password');
        Route::post('/super-admin/profile/notifications', [\App\Http\Controllers\SuperAdminProfileController::class, 'updateNotifications'])->name('super-admin.profile.notifications');
        Route::delete('/super-admin/profile/photo', [\App\Http\Controllers\SuperAdminProfileController::class, 'deleteProfilePhoto'])->name('super-admin.profile.photo.delete');

        // Admin profile management
        Route::get('/admin/profile', [\App\Http\Controllers\AdminProfileController::class, 'index'])->name('admin.profile');
        Route::post('/admin/profile/update', [\App\Http\Controllers\AdminProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::post('/admin/profile/emergency-contact', [\App\Http\Controllers\AdminProfileController::class, 'updateEmergencyContact'])->name('admin.profile.emergency-contact.update');
        Route::post('/admin/profile/photo', [\App\Http\Controllers\AdminProfileController::class, 'updateProfilePhoto'])->name('admin.profile.photo.update');
        Route::post('/admin/profile/password', [\App\Http\Controllers\AdminProfileController::class, 'updatePassword'])->name('admin.profile.password');
        Route::post('/admin/profile/notifications', [\App\Http\Controllers\AdminProfileController::class, 'updateNotifications'])->name('admin.profile.notifications');
        Route::delete('/admin/profile/photo', [\App\Http\Controllers\AdminProfileController::class, 'deleteProfilePhoto'])->name('admin.profile.photo.delete');

        // Teacher profile management
        Route::get('/teacher/profile', [\App\Http\Controllers\TeacherProfileController::class, 'index'])->name('teacher.profile');
        Route::post('/teacher/profile/update', [\App\Http\Controllers\TeacherProfileController::class, 'updateProfile'])->name('teacher.profile.update');
        Route::post('/teacher/profile/emergency-contact', [\App\Http\Controllers\TeacherProfileController::class, 'updateEmergencyContact'])->name('teacher.profile.emergency-contact.update');
        Route::post('/teacher/profile/photo', [\App\Http\Controllers\TeacherProfileController::class, 'updateProfilePhoto'])->name('teacher.profile.photo.update');
        Route::post('/teacher/profile/password', [\App\Http\Controllers\TeacherProfileController::class, 'updatePassword'])->name('teacher.profile.password');
        Route::post('/teacher/profile/notifications', [\App\Http\Controllers\TeacherProfileController::class, 'updateNotifications'])->name('teacher.profile.notifications');
        Route::delete('/teacher/profile/photo', [\App\Http\Controllers\TeacherProfileController::class, 'deleteProfilePhoto'])->name('teacher.profile.photo.delete');

        // Parent profile management
        Route::get('/parent/profile', [\App\Http\Controllers\ParentProfileController::class, 'index'])->name('parent.profile');
        Route::post('/parent/profile/update', [\App\Http\Controllers\ParentProfileController::class, 'updateProfile'])->name('parent.profile.update');
        Route::post('/parent/profile/emergency-contact', [\App\Http\Controllers\ParentProfileController::class, 'updateEmergencyContact'])->name('parent.profile.emergency-contact.update');
        Route::post('/parent/profile/photo', [\App\Http\Controllers\ParentProfileController::class, 'updateProfilePhoto'])->name('parent.profile.photo.update');
        Route::post('/parent/profile/password', [\App\Http\Controllers\ParentProfileController::class, 'updatePassword'])->name('parent.profile.password');
        Route::post('/parent/profile/notifications', [\App\Http\Controllers\ParentProfileController::class, 'updateNotifications'])->name('parent.profile.notifications');
        Route::delete('/parent/profile/photo', [\App\Http\Controllers\ParentProfileController::class, 'deleteProfilePhoto'])->name('parent.profile.photo.delete');
        // Student assignment submission
        Route::get('/assignments/{assignment}/submit', [\App\Http\Controllers\AssignmentSubmissionController::class, 'create'])->name('assignments.submit');
        Route::post('/assignments/{assignment}/submit', [\App\Http\Controllers\AssignmentSubmissionController::class, 'store'])->name('assignments.submit.store');
        // Teacher review
        Route::get('/assignments/{assignment}/review', [\App\Http\Controllers\AssignmentSubmissionController::class, 'review'])->name('assignments.review');
        Route::post('/assignments/{assignment}/submissions/{submission}/grade', [\App\Http\Controllers\AssignmentSubmissionController::class, 'grade'])->name('assignments.submissions.grade');
        Route::get('/student/calendar', function () {
            return view('dashboard.student.calendar');
        })->name('student.calendar');

        // Parent Dashboard Routes
        Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard');
        Route::get('/parent/child/{child}/details', [ParentDashboardController::class, 'childDetails'])->name('parent.child.details');
        Route::get('/parent/child/{child}/grades', [ParentDashboardController::class, 'childGrades'])->name('parent.child.grades');
        Route::get('/parent/child/{child}/attendance', [ParentDashboardController::class, 'childAttendance'])->name('parent.child.attendance');

        // Academic Calendar Routes (Admin Only)
        Route::middleware(['admin'])->prefix('academic-calendar')->name('academic-calendar.')->group(function () {
            Route::get('/', [AcademicCalendarController::class, 'index'])->name('index');

            // Academic Years
            Route::get('/years/create', [AcademicCalendarController::class, 'createYear'])->name('years.create');
            Route::post('/years', [AcademicCalendarController::class, 'storeYear'])->name('years.store');
            Route::get('/years/{id}/edit', [AcademicCalendarController::class, 'editYear'])->name('years.edit');
            Route::put('/years/{id}', [AcademicCalendarController::class, 'updateYear'])->name('years.update');

            // Academic Semesters
            Route::get('/years/{yearId}/semesters/create', [AcademicCalendarController::class, 'createSemester'])->name('semesters.create');
            Route::post('/years/{yearId}/semesters', [AcademicCalendarController::class, 'storeSemester'])->name('semesters.store');

            // Academic Terms
            Route::get('/years/{yearId}/terms/create', [AcademicCalendarController::class, 'createTerm'])->name('terms.create');
            Route::post('/years/{yearId}/terms', [AcademicCalendarController::class, 'storeTerm'])->name('terms.store');
            Route::get('/years/{yearId}/terms/{termId}/edit', [AcademicCalendarController::class, 'editTerm'])->name('terms.edit');
            Route::put('/years/{yearId}/terms/{termId}', [AcademicCalendarController::class, 'updateTerm'])->name('terms.update');

            // Academic Events
            Route::get('/years/{yearId}/events/create', [AcademicCalendarController::class, 'createEvent'])->name('events.create');
            Route::post('/years/{yearId}/events', [AcademicCalendarController::class, 'storeEvent'])->name('events.store');

            // Academic Holidays
            Route::get('/years/{yearId}/holidays/create', [AcademicCalendarController::class, 'createHoliday'])->name('holidays.create');
            Route::post('/years/{yearId}/holidays', [AcademicCalendarController::class, 'storeHoliday'])->name('holidays.store');

            // Academic Exams
            Route::get('/years/{yearId}/exams/create', [AcademicCalendarController::class, 'createExam'])->name('exams.create');
            Route::post('/years/{yearId}/exams', [AcademicCalendarController::class, 'storeExam'])->name('exams.store');
        });
    });

    // Form Teacher Routes
    Route::prefix('form-teacher')->name('form-teacher.')->group(function () {
        Route::get('/', [FormTeacherController::class, 'index'])->name('dashboard');
        Route::get('/report-cards', [FormTeacherController::class, 'reportCards'])->name('report-cards');
        Route::get('/students', [FormTeacherController::class, 'students'])->name('students');
        Route::get('/students/{student}', [FormTeacherController::class, 'studentShow'])->name('students.show');
        Route::get('/attendance', [FormTeacherController::class, 'attendance'])->name('attendance');
        Route::post('/attendance', [FormTeacherController::class, 'storeAttendance'])->name('attendance.store');
        Route::get('/assignments', [FormTeacherController::class, 'assignments'])->name('assignments');
        Route::get('/remarks', [FormTeacherController::class, 'remarks'])->name('remarks');
        Route::get('/remarks/create', [FormTeacherController::class, 'createRemark'])->name('remarks.create');
        Route::post('/remarks', [FormTeacherController::class, 'storeRemark'])->name('remarks.store');
        Route::get('/announcements', [FormTeacherController::class, 'announcements'])->name('announcements');
        Route::get('/announcements/create', [FormTeacherController::class, 'createAnnouncement'])->name('announcements.create');
        Route::post('/announcements', [FormTeacherController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::get('/reports', [FormTeacherController::class, 'reports'])->name('reports');
    });

    // Admin Form Teacher Assignment Routes (Super Admin and Branch Admin)
    Route::middleware(['admin'])->prefix('admin/form-teacher-assignments')->name('admin.form-teacher-assignments.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'store'])->name('store');
        Route::get('/{formTeacherAssignment}/edit', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'edit'])->name('edit');
        Route::put('/{formTeacherAssignment}', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'update'])->name('update');
        Route::delete('/{formTeacherAssignment}', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'destroy'])->name('destroy');
        Route::patch('/{formTeacherAssignment}/toggle-status', [App\Http\Controllers\Admin\FormTeacherAssignmentController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Result Management Routes
    Route::middleware(['auth'])->prefix('result')->name('result.')->group(function () {
        Route::get('/', [App\Http\Controllers\Result\ResultController::class, 'index'])->name('index');
        Route::get('/mock', [App\Http\Controllers\Result\MockResultController::class, 'index'])->name('mock-index');
        Route::get('/mock/exams', [App\Http\Controllers\Result\MockResultController::class, 'exams'])->name('mock-exams');
        Route::post('/mock/exams', [App\Http\Controllers\Result\MockResultController::class, 'storeExam'])->name('mock-exams.store');
        Route::post('/mock/exams/{mockExam}/toggle', [App\Http\Controllers\Result\MockResultController::class, 'toggleExam'])->name('mock-exams.toggle');
        Route::get('/create', [App\Http\Controllers\Result\ResultController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Result\ResultController::class, 'store'])->name('store');
        Route::post('/bulk', [App\Http\Controllers\Result\ResultController::class, 'bulkStore'])->name('bulk-store');
        Route::get('/api/bulk-entry-data', [App\Http\Controllers\Result\ResultController::class, 'getBulkEntryData'])->name('api.bulk-entry-data');
        Route::get('/{result}', [App\Http\Controllers\Result\ResultController::class, 'show'])->name('show');
        Route::get('/{result}/edit', [App\Http\Controllers\Result\ResultController::class, 'edit'])->name('edit');
        Route::put('/{result}', [App\Http\Controllers\Result\ResultController::class, 'update'])->name('update');
        Route::delete('/{result}', [App\Http\Controllers\Result\ResultController::class, 'destroy'])->name('destroy');
        Route::post('/student/{studentId}/term/{termId}/approve', [App\Http\Controllers\Result\ResultController::class, 'approveSheet'])->name('approve-sheet');
        Route::post('/student/{studentId}/term/{termId}/disapprove', [App\Http\Controllers\Result\ResultController::class, 'disapproveSheet'])->name('disapprove-sheet');
        Route::post('/bulk/approve', [App\Http\Controllers\Result\ResultController::class, 'bulkApproveSheets'])->name('bulk-approve-sheets');
        Route::post('/bulk/disapprove', [App\Http\Controllers\Result\ResultController::class, 'bulkDisapproveSheets'])->name('bulk-disapprove-sheets');
        Route::get('/student/{studentId}/term/{termId}', [App\Http\Controllers\Result\ResultController::class, 'studentResultSheet'])->name('student-sheet');
        Route::post('/{result}/comment', [App\Http\Controllers\Result\ResultController::class, 'addComment'])->name('add-comment');

        // Mock Result sheet routes
        Route::get('/mock/student/{studentId}/exam/{mockExamId}', [App\Http\Controllers\Result\MockResultController::class, 'studentSheet'])->name('mock-student-sheet');
        Route::get('/mock/{mockResult}/edit', [App\Http\Controllers\Result\MockResultController::class, 'edit'])->name('mock-edit');
        Route::put('/mock/{mockResult}', [App\Http\Controllers\Result\MockResultController::class, 'update'])->name('mock-update');
        Route::delete('/mock/{mockResult}', [App\Http\Controllers\Result\MockResultController::class, 'destroy'])->name('mock-destroy');
        Route::post('/mock/{mockResult}/comment', [App\Http\Controllers\Result\MockResultController::class, 'addComment'])->name('mock-add-comment');
        Route::post('/mock/student/{studentId}/exam/{mockExamId}/approve', [App\Http\Controllers\Result\MockResultController::class, 'approveSheet'])->name('mock-approve-sheet');
        Route::post('/mock/student/{studentId}/exam/{mockExamId}/disapprove', [App\Http\Controllers\Result\MockResultController::class, 'disapproveSheet'])->name('mock-disapprove-sheet');
        Route::post('/mock/bulk/approve', [App\Http\Controllers\Result\MockResultController::class, 'bulkApproveSheets'])->name('mock-bulk-approve-sheets');
        Route::post('/mock/bulk/disapprove', [App\Http\Controllers\Result\MockResultController::class, 'bulkDisapproveSheets'])->name('mock-bulk-disapprove-sheets');
    });
});

// TEMPORARY — remove after running once. Regrades all stored term results
// using the class-aware scale (SS: A1–F9, JS: A1/C/P/F, else A–F), then clears caches.
// Add ?dry=1 to the URL to preview counts without saving anything.
Route::get('/__regrade/{key}', function (\Illuminate\Http\Request $request, $key) {
    abort_unless($key === '09ae95535e315f9436a7ff92dd7af8fd5d4e9df188e09863', 403);
    \Artisan::call('results:regrade', $request->boolean('dry') ? ['--dry-run' => true] : []);
    $output = \Artisan::output();
    \Artisan::call('optimize:clear');
    return nl2br(e($output . "\n" . \Artisan::output()));
});

// TEMPORARY — remove this route as soon as the pending migrations have been
// confirmed applied in production. Gated by MIGRATE_KEY in .env (set it in
// production's .env only; never commit the real value). If MIGRATE_KEY is
// unset, this 404s instead of ever comparing against an empty key.
Route::get('/__migrate/{key}', function ($key) {
    $expectedKey = config('deploy.migrate_key');
    abort_if(blank($expectedKey), 404);
    abort_unless(hash_equals($expectedKey, $key), 403);

    $log = '';
    foreach (['config:clear', 'route:clear', 'view:clear', 'cache:clear'] as $command) {
        \Artisan::call($command);
        $log .= "\$ php artisan {$command}\n" . \Artisan::output() . "\n";
    }

    \Artisan::call('migrate', ['--force' => true]);
    $log .= "\$ php artisan migrate --force\n" . \Artisan::output() . "\n";

    \Artisan::call('migrate:status');
    $log .= "\$ php artisan migrate:status\n" . \Artisan::output() . "\n";

    \Artisan::call('optimize:clear');
    $log .= "\$ php artisan optimize:clear\n" . \Artisan::output();

    return '<pre>' . e($log) . '</pre>';
});
