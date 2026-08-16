<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $branchId = session('current_branch_id');
        $subjects = Subject::where('branch_id', $branchId)
            ->with(['branch', 'classes', 'teachers'])
            ->orderBy('name')
            ->paginate(20);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Super admin can see all branches, branch admin only sees their own
        $branches = $user->is_super_admin 
            ? \App\Models\Branch::orderBy('name')->get()
            : \App\Models\Branch::where('id', $branchId)->get();
        
        // If branch admin, get classes from their branch
        if (!$user->is_super_admin) {
            $classes = SchoolClass::where('branch_id', $branchId)->orderBy('name')->get();
        } else {
            // For super admin, start with empty classes (they'll select branch first)
            $classes = collect();
        }
        
        return view('subjects.create', compact('classes', 'branches'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $branchId = session('current_branch_id');
        
        // Super admin can specify branch, others use their current branch
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['exists:school_classes,id'],
        ];
        
        if ($user->is_super_admin) {
            $validationRules['branch_id'] = ['required', 'exists:branches,id'];
        }
        
        $request->validate($validationRules);
        
        // Determine the branch for the subject
        if ($user->is_super_admin) {
            $subjectBranchId = $request->branch_id;
        } else {
            $subjectBranchId = $branchId;
        }

        try {
            DB::beginTransaction();

            $subject = Subject::create([
                'branch_id' => $subjectBranchId,
                'name' => $request->name,
                'code' => $request->code,
            ]);

            // Attach classes to the subject
            if ($request->has('class_ids') && !empty($request->class_ids)) {
                $subject->classes()->attach($request->class_ids);
            }

            DB::commit();

            return redirect()->route('subjects.index')->with('status', 'Subject created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create subject: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function edit(Subject $subject)
    {
        $branchId = session('current_branch_id');
        
        // Ensure the subject belongs to the current branch
        if ($subject->branch_id != $branchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $classes = SchoolClass::where('branch_id', $branchId)->orderBy('name')->get();
        $subject->load('classes');
        
        return view('subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $branchId = session('current_branch_id');
        
        // Ensure the subject belongs to the current branch
        if ($subject->branch_id != $branchId) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['exists:school_classes,id'],
        ]);

        try {
            DB::beginTransaction();

            $subject->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);

            // Sync classes (this will remove old assignments and add new ones)
            if ($request->has('class_ids')) {
                $subject->classes()->sync($request->class_ids);
            } else {
                $subject->classes()->detach();
            }

            DB::commit();

            return redirect()->route('subjects.index')->with('status', 'Subject updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update subject: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Subject $subject)
    {
        $branchId = session('current_branch_id');
        
        // Ensure the subject belongs to the current branch
        if ($subject->branch_id != $branchId) {
            abort(403, 'Unauthorized access.');
        }

        try {
            DB::beginTransaction();

            // Detach all relationships first
            $subject->classes()->detach();
            $subject->teachers()->detach();

            // Delete the subject
            $subject->delete();

            DB::commit();

            return redirect()->route('subjects.index')->with('status', 'Subject deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete subject: ' . $e->getMessage()]);
        }
    }
}


