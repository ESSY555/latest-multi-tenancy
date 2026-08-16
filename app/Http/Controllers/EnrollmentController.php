<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function create(SchoolClass $class)
    {
        $students = User::whereHas('branches', function ($q) use ($class) {
            $q->where('branches.id', $class->branch_id)->where('branch_user.role', 'student');
        })->orderBy('name')->get();
        return view('enrollments.create', compact('class', 'students'));
    }

    public function store(Request $request, SchoolClass $class)
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
        ]);
        Enrollment::firstOrCreate([
            'school_class_id' => $class->id,
            'student_id' => $request->student_id,
        ]);
        return redirect()->route('class-management')->with('status', 'Student enrolled');
    }
}



