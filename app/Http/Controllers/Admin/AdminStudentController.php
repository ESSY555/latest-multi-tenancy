<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Assignment;
use App\Models\Result;
use App\Models\Attendance;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    /**
     * Export students to Excel
     */
    public function export()
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }

        $students = \App\Models\User::whereHas('branches', function ($query) use ($currentBranchId, $user) {
            $query->where('role', 'student');
            if (!$user->is_super_admin) {
                $query->where('branch_id', $currentBranchId);
            }
        })->with(['studentProfile', 'enrollments.schoolClass'])->get();

        $filename = "students.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Student Name', 'Class', 'Admission Number']);

            foreach ($students as $student) {
                $enrollment = $student->enrollments->sortByDesc('created_at')->first();
                $className = $enrollment && $enrollment->schoolClass ? $enrollment->schoolClass->name : 'Not assigned';
                $admissionNo = $student->studentProfile ? $student->studentProfile->admission_number : 'N/A';

                fputcsv($file, [
                    $student->name,
                    $className,
                    $admissionNo
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a listing of students
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }
        
        // Get branches for the form
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();
            
        // Get active filters
        $search = $request->query('search');
        $selectedBranchId = $user->is_super_admin ? $request->query('branch_id') : $currentBranchId;
        $selectedClassId = $request->query('class_id');
        
        // Get classes for filter dropdown based on branch
        $classes = \App\Models\SchoolClass::orderBy('name')
            ->when(!$user->is_super_admin, function($query) use ($currentBranchId) {
                return $query->where('branch_id', $currentBranchId);
            })
            ->when($user->is_super_admin && $selectedBranchId, function($query) use ($selectedBranchId) {
                return $query->where('branch_id', $selectedBranchId);
            })
            ->get();
        
        // Get students with branch scope, search queries, and class filters
        $studentsQuery = DB::table('branch_user')
            ->where('branch_user.role', 'student')
            ->when($selectedBranchId, function($query) use ($selectedBranchId) {
                return $query->where('branch_user.branch_id', $selectedBranchId);
            })
            ->join('users', 'branch_user.user_id', '=', 'users.id')
            ->join('branches', 'branch_user.branch_id', '=', 'branches.id')
            ->leftJoin('student_profiles', 'users.id', '=', 'student_profiles.user_id')
            ->leftJoin('enrollments', function($join) {
                $join->on('users.id', '=', 'enrollments.student_id')
                     ->whereRaw('enrollments.id = (select id from enrollments where student_id = users.id order by created_at desc limit 1)');
            })
            ->leftJoin('school_classes', 'enrollments.school_class_id', '=', 'school_classes.id')
            ->select(
                'users.*',
                'branches.name as branch_name',
                'branch_user.branch_id',
                'student_profiles.guardian_name as parent_name',
                'student_profiles.guardian_phone as parent_contact',
                'school_classes.name as grade_level',
                'student_profiles.admission_number'
            );

        if ($search) {
            $studentsQuery->where(function($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                  ->orWhere('users.email', 'like', '%' . $search . '%')
                  ->orWhere('users.phone', 'like', '%' . $search . '%')
                  ->orWhere('student_profiles.admission_number', 'like', '%' . $search . '%');
            });
        }

        if ($selectedClassId) {
            $studentsQuery->where('enrollments.school_class_id', $selectedClassId);
        }
        
        $students = $studentsQuery->orderBy('users.name')
            ->paginate(10)
            ->withQueryString();
        
        return view('admin.students.index', compact('students', 'branches', 'classes', 'search', 'selectedBranchId', 'selectedClassId'));
    }

    /**
     * Show the form for creating a new student
     */
    public function create()
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();

        $classes = SchoolClass::whereIn('branch_id', $branches->pluck('id'))
            ->orderBy('branch_id')
            ->orderBy('name')
            ->get(['id', 'branch_id', 'name']);
            
        $academicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();
        
        return view('admin.students.create', compact('branches', 'classes', 'academicYears'));
    }

    /**
     * Store a newly created student
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        if (!$user->is_super_admin && !$currentBranchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|min:3|max:50|alpha_dash|unique:users,username|required_without:email',
            'email' => 'nullable|email|unique:users,email|required_without:username',
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'nationality' => 'nullable|string|max:255',
            'state_of_origin' => 'nullable|string|max:255',
            'local_government_area' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:255',
            'church_denomination' => 'nullable|string|max:255',
            'residential_address' => 'nullable|string|max:1000',
            'language_of_communication' => 'nullable|string|max:255',
            'number_of_children_in_family' => 'nullable|integer|min:1|max:50',
            'position_in_family' => 'nullable|integer|min:1|max:50',
            'school_last_attended' => 'nullable|string|max:255',
            'class_last_attended' => 'nullable|string|max:255',
            'has_health_challenges' => 'nullable|boolean',
            'health_challenges_details' => 'nullable|string|max:2000',
            'crisis_response' => 'nullable|in:administer_first_aid,do_not_administer_first_aid,call_parents_guardians',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_residential_address' => 'nullable|string|max:1000',
            'mother_residential_address' => 'nullable|string|max:1000',
            'father_occupation' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'father_office_address' => 'nullable|string|max:1000',
            'mother_office_address' => 'nullable|string|max:1000',
            'father_phone_number' => 'nullable|string|max:20',
            'mother_phone_number' => 'nullable|string|max:20',
            'address' => 'required|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'profile_photo.max' => 'The profile photo should not be greater than 5MB.',
        ]);
        
        // Ensure branch admin can only create students for their branch
        if (!$user->is_super_admin && $validated['branch_id'] != $currentBranchId) {
            abort(403, 'Unauthorized access to this branch.');
        }

        $classExistsInBranch = SchoolClass::where('id', $validated['school_class_id'])
            ->where('branch_id', $validated['branch_id'])
            ->exists();

        if (!$classExistsInBranch) {
            return back()
                ->withInput()
                ->withErrors(['school_class_id' => 'Selected class does not belong to the chosen branch.']);
        }
        
        try {
            DB::beginTransaction();
            
            // Create user
            $photoPath = null;
            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                \Log::info('Profile photo upload started (Controller Store)', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize()
                ]);

                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('profile-photos', $file, $filename);
                if (!file_exists(public_path('uploads/profile-photos'))) {
                    @mkdir(public_path('uploads/profile-photos'), 0777, true);
                }
                if (file_exists(storage_path('app/public/profile-photos/' . $filename))) {
                    @copy(storage_path('app/public/profile-photos/' . $filename), public_path('uploads/profile-photos/' . $filename));
                }
                $photoPath = $filename;

                if (!$photoPath) {
                    \Log::error('Profile photo failed to store on disk (Controller Store)');
                    throw new \Exception('The profile photo failed to upload.');
                }

                \Log::info('Profile photo stored successfully (Controller Store)', ['path' => $photoPath]);
            }

            $student = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
                'address' => $validated['address'],
                'profile_photo' => $photoPath,
            ]);
            
            // Create student profile (schema uses branch_id, admission_number, date_of_birth, guardian fields)
            $academicYear = \App\Models\AcademicYear::find($validated['academic_year_id']);
            if (preg_match('/^(\d{4})\//', $academicYear->name, $matches)) {
                $year = $matches[1];
            } else {
                $year = $academicYear->start_date ? $academicYear->start_date->format('Y') : date('Y');
            }
            $prefix = "BIS/{$year}/";
            
            $existingNumbers = \App\Models\StudentProfile::where('admission_number', 'like', "{$prefix}%")
                ->pluck('admission_number')
                ->map(function ($num) use ($prefix) {
                    return (int) str_replace($prefix, '', $num);
                })
                ->toArray();
                
            $sequence = 1;
            while (in_array($sequence, $existingNumbers)) {
                $sequence++;
            }
            
            $admissionNumber = $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
            $student->studentProfile()->create([
                'branch_id' => $validated['branch_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'admission_number' => $admissionNumber,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'guardian_name' => $validated['parent_name'] ?? null,
                'guardian_phone' => $validated['parent_phone'] ?? null,
                'parent_name' => $validated['parent_name'] ?? null,
                'parent_phone' => $validated['parent_phone'] ?? null,
                'parent_email' => $validated['parent_email'] ?? null,
                'nationality' => $validated['nationality'] ?? null,
                'state_of_origin' => $validated['state_of_origin'] ?? null,
                'local_government_area' => $validated['local_government_area'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'church_denomination' => $validated['church_denomination'] ?? null,
                'residential_address' => $validated['residential_address'] ?? null,
                'language_of_communication' => $validated['language_of_communication'] ?? null,
                'number_of_children_in_family' => $validated['number_of_children_in_family'] ?? null,
                'position_in_family' => $validated['position_in_family'] ?? null,
                'school_last_attended' => $validated['school_last_attended'] ?? null,
                'class_last_attended' => $validated['class_last_attended'] ?? null,
                'has_health_challenges' => (bool) ($validated['has_health_challenges'] ?? false),
                'health_challenges_details' => $validated['health_challenges_details'] ?? null,
                'crisis_response' => $validated['crisis_response'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'father_residential_address' => $validated['father_residential_address'] ?? null,
                'mother_residential_address' => $validated['mother_residential_address'] ?? null,
                'father_occupation' => $validated['father_occupation'] ?? null,
                'mother_occupation' => $validated['mother_occupation'] ?? null,
                'father_office_address' => $validated['father_office_address'] ?? null,
                'mother_office_address' => $validated['mother_office_address'] ?? null,
                'father_phone_number' => $validated['father_phone_number'] ?? null,
                'mother_phone_number' => $validated['mother_phone_number'] ?? null,
            ]);
            
            // Assign student role to branch
            DB::table('branch_user')->insert([
                'user_id' => $student->id,
                'branch_id' => $validated['branch_id'],
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'school_class_id' => $validated['school_class_id'],
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                ->with('success', 'Student created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified student with comprehensive details
     */
    public function show(User $student)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check if student belongs to current branch
        $studentBranch = DB::table('branch_user')
            ->where('user_id', $student->id)
            ->where('role', 'student')
            ->first();
            
        if (!$studentBranch) {
            abort(404, 'Student not found.');
        }
        
        if (!$user->is_super_admin && $studentBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this student.');
        }
        
        // Get branch information
        $branch = \App\Models\Branch::find($studentBranch->branch_id);
        
        // Get student profile
        $studentProfile = $student->studentProfile;
        
        // Get all classes the student is enrolled in
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['schoolClass.branch', 'schoolClass.teachers', 'schoolClass.subjects'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all assignments for the student
        $assignments = Assignment::whereHas('schoolClass', function($query) use ($enrollments) {
            $query->whereIn('id', $enrollments->pluck('school_class_id'));
        })
        ->with(['schoolClass', 'schoolClass.subjects'])
        ->orderBy('due_date', 'asc')
        ->get();
        
        // Get all results for the student
        $results = Result::where('student_id', $student->id)
            ->with(['schoolClass', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get attendance records for the student
        $attendanceRecords = Attendance::where('student_id', $student->id)
            ->with(['schoolClass', 'schoolClass.teachers'])
            ->latest()
            ->take(50)
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_classes' => $enrollments->count(),
            'total_assignments' => $assignments->count(),
            'completed_assignments' => $assignments->where('status', 'completed')->count(),
            'pending_assignments' => $assignments->where('status', 'pending')->count(),
            'total_results' => $results->count(),
            'average_score' => $results->count() > 0 ? round($results->avg('total'), 2) : 0,
            'attendance_rate' => $attendanceRecords->count() > 0 ? 
                round(($attendanceRecords->where('status', 'present')->count() / $attendanceRecords->count()) * 100, 1) : 0,
        ];
        
        // Get recent activities
        $recentActivities = collect();
        
        // Add enrollment activities
        foreach ($enrollments->take(10) as $enrollment) {
            $recentActivities->push([
                'type' => 'enrollment',
                'title' => 'Enrolled in ' . $enrollment->schoolClass->name,
                'status' => 'enrolled',
                'date' => $enrollment->created_at,
                'data' => $enrollment
            ]);
        }
        
        // Add result activities
        foreach ($results->take(10) as $result) {
            $recentActivities->push([
                'type' => 'result',
                'title' => $result->subject->name . ' - Score: ' . $result->total,
                'status' => 'completed',
                'date' => $result->created_at,
                'data' => $result
            ]);
        }
        
        // Add attendance activities
        foreach ($attendanceRecords->take(10) as $attendance) {
            $recentActivities->push([
                'type' => 'attendance',
                'title' => $attendance->schoolClass->name . ' - ' . ucfirst($attendance->status),
                'status' => $attendance->status,
                'date' => $attendance->date,
                'data' => $attendance
            ]);
        }
        
        // Sort activities by date
        $recentActivities = $recentActivities->sortByDesc('date')->take(15);
        
        return view('admin.students.show', compact(
            'student', 
            'studentProfile',
            'branch', 
            'enrollments', 
            'assignments', 
            'results', 
            'attendanceRecords', 
            'stats', 
            'recentActivities'
        ));
    }

    /**
     * Show the form for editing the specified student
     */
    public function edit(User $student)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check if student belongs to current branch
        $studentBranch = DB::table('branch_user')
            ->where('user_id', $student->id)
            ->where('role', 'student')
            ->first();
            
        if (!$studentBranch) {
            abort(404, 'Student not found.');
        }
        
        if (!$user->is_super_admin && $studentBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this student.');
        }
        
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $currentBranchId)->get();

        $classes = SchoolClass::whereIn('branch_id', $branches->pluck('id'))
            ->orderBy('branch_id')
            ->orderBy('name')
            ->get(['id', 'branch_id', 'name']);

        $selectedClassId = Enrollment::where('student_id', $student->id)
            ->whereHas('schoolClass', function ($query) use ($studentBranch) {
                $query->where('branch_id', $studentBranch->branch_id);
            })
            ->latest('id')
            ->value('school_class_id');

        $academicYears = \App\Models\AcademicYear::orderBy('start_date', 'desc')->get();

        return view('admin.students.edit', compact('student', 'branches', 'studentBranch', 'classes', 'selectedClassId', 'academicYears'));
    }

    /**
     * Update the specified student
     */
    public function update(Request $request, User $student)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check if student belongs to current branch
        $studentBranch = DB::table('branch_user')
            ->where('user_id', $student->id)
            ->where('role', 'student')
            ->first();
            
        if (!$studentBranch) {
            abort(404, 'Student not found.');
        }
        
        if (!$user->is_super_admin && $studentBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this student.');
        }
        
        \Log::info('--- Student Edit Attempt Started (Controller) ---', [
            'student_id' => $student->id,
            'branch_id' => $request->branch_id,
            'has_file' => $request->hasFile('profile_photo'),
            'request_data' => $request->except(['profile_photo'])
        ]);

        $wantsPasswordUpdate = $request->filled('password') && $request->filled('password_confirmation');

        try {
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $student->id,
                'phone' => 'nullable|string|max:20',
                'branch_id' => 'required|exists:branches,id',
                'school_class_id' => 'required|exists:school_classes,id',
                'academic_year_id' => 'required|exists:academic_years,id',
                'date_of_birth' => 'nullable|date|before:today',
                'guardian_name' => 'nullable|string|max:255',
                'guardian_phone' => 'nullable|string|max:20',
                'address' => 'required|string|max:1000',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            ];

            if ($wantsPasswordUpdate) {
                $rules['password'] = 'required|string|min:8|confirmed';
                $rules['password_confirmation'] = 'required|string|min:8';
            }

            $validated = $request->validate($rules, [
                'profile_photo.max' => 'The profile photo should not be greater than 5MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::warning('Student update validation failed (Controller)', [
                'student_id' => $student->id,
                'errors' => $ve->errors()
            ]);
            throw $ve;
        }
        
        // Ensure branch admin can only update students for their branch
        if (!$user->is_super_admin && $validated['branch_id'] != $currentBranchId) {
            abort(403, 'Unauthorized access to this branch.');
        }

        $classExistsInBranch = SchoolClass::where('id', $validated['school_class_id'])
            ->where('branch_id', $validated['branch_id'])
            ->exists();

        if (!$classExistsInBranch) {
            return back()
                ->withInput()
                ->withErrors(['school_class_id' => 'Selected class does not belong to the chosen branch.']);
        }
        
        try {
            DB::beginTransaction();
            
            // Update user
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ];

            if ($wantsPasswordUpdate) {
                $userData['password'] = bcrypt($validated['password']);
            }

            if ($request->hasFile('profile_photo')) {
                $file = $request->file('profile_photo');
                \Log::info('Profile photo update started (Controller Update)', [
                    'student_id' => $student->id,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize()
                ]);

                // Delete old photo if exists
                if ($student->profile_photo) {
                    $oldPath = 'profile-photos/' . $student->profile_photo;
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($oldPath)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
                        \Log::info('Old profile photo deleted (Controller)', ['path' => $oldPath]);
                    }
                }

                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('profile-photos', $file, $filename);
                if (!file_exists(public_path('uploads/profile-photos'))) {
                    @mkdir(public_path('uploads/profile-photos'), 0777, true);
                }
                if (file_exists(storage_path('app/public/profile-photos/' . $filename))) {
                    @copy(storage_path('app/public/profile-photos/' . $filename), public_path('uploads/profile-photos/' . $filename));
                }
                $photoPath = $filename;

                if (!$photoPath) {
                    \Log::error('Profile photo failed to store on disk (Controller Update)');
                    throw new \Exception('The profile photo failed to upload.');
                }
                
                $userData['profile_photo'] = $photoPath;
                \Log::info('New profile photo stored successfully (Controller Update)', ['path' => $photoPath]);
            }

            $student->update($userData);
            
            // Update student profile
            $studentProfile = $student->studentProfile;
            $profileUpdateData = [
                'academic_year_id' => $validated['academic_year_id'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
            ];

            if ($studentProfile->academic_year_id != $validated['academic_year_id']) {
                $academicYear = \App\Models\AcademicYear::find($validated['academic_year_id']);
                if (preg_match('/^(\d{4})\//', $academicYear->name, $matches)) {
                    $year = $matches[1];
                } else {
                    $year = $academicYear->start_date ? $academicYear->start_date->format('Y') : date('Y');
                }
                $prefix = "BIS/{$year}/";
                
                $oldAdmissionNumber = $studentProfile->admission_number;
                if ($oldAdmissionNumber && strpos($oldAdmissionNumber, '/') !== false) {
                    $parts = explode('/', $oldAdmissionNumber);
                    $sequenceString = end($parts);
                    $profileUpdateData['admission_number'] = $prefix . $sequenceString;
                }
            }

            $studentProfile->update($profileUpdateData);
            
            // Update branch assignment if changed
            if ($studentBranch->branch_id != $validated['branch_id']) {
                DB::table('branch_user')
                    ->where('user_id', $student->id)
                    ->where('role', 'student')
                    ->update(['branch_id' => $validated['branch_id']]);
            }

            // Keep one active enrollment per branch and ensure the selected class is assigned.
            Enrollment::where('student_id', $student->id)
                ->whereHas('schoolClass', function ($query) use ($validated) {
                    $query->where('branch_id', $validated['branch_id']);
                })
                ->where('school_class_id', '!=', $validated['school_class_id'])
                ->delete();

            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'school_class_id' => $validated['school_class_id'],
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Student updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified student
     */
    public function destroy(User $student)
    {
        $user = auth()->user();
        $currentBranchId = session('current_branch_id');
        
        // Check if student belongs to current branch
        $studentBranch = DB::table('branch_user')
            ->where('user_id', $student->id)
            ->where('role', 'student')
            ->first();
            
        if (!$studentBranch) {
            abort(404, 'Student not found.');
        }
        
        if (!$user->is_super_admin && $studentBranch->branch_id != $currentBranchId) {
            abort(403, 'Unauthorized access to this student.');
        }
        
        try {
            DB::beginTransaction();
            
            // Remove branch assignment
            DB::table('branch_user')
                ->where('user_id', $student->id)
                ->where('role', 'student')
                ->delete();
            
            // Delete student (this will cascade to related records)
            $student->delete();
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                ->with('success', 'Student deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to delete student: ' . $e->getMessage()]);
        }
    }
}
