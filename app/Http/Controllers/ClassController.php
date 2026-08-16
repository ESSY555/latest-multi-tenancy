<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);
        
        $class = SchoolClass::create([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'grade_level' => $request->grade_level,
            'academic_year' => $request->academic_year,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Class created successfully'
        ]);
    }

    public function show(SchoolClass $class)
    {
        $class->load([
            'branch',
            'enrollments.student',
            'teachers',
            'subjects'
        ]);

        return response()->json([
            'success' => true,
            'class' => $class
        ]);
    }



    public function assignTeacher(Request $request, SchoolClass $class)
    {
        $request->validate([
            'teacher_id' => ['required', 'exists:users,id'],
        ]);
        $class->teachers()->syncWithoutDetaching([$request->teacher_id]);
        return redirect()->route('class-management')->with('status', 'Teacher assigned');
    }

    public function edit(SchoolClass $class)
    {
        return response()->json([
            'success' => true,
            'class' => $class->load('branch')
        ]);
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:50'],
            'branch_id' => ['required', 'exists:branches,id'],
        ]);

        $class->update([
            'name' => $request->name,
            'grade_level' => $request->grade_level,
            'academic_year' => $request->academic_year,
            'branch_id' => $request->branch_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Class updated successfully'
        ]);
    }

    public function destroy(SchoolClass $class)
    {
        try {
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
    }
}



