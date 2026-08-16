<?php

namespace App\Http\Controllers;

use App\Models\Syllabus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyllabusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branchId = session('selected_branch_id');
        
        if (!$branchId) {
            // Try to get the first available branch
            $firstBranch = \App\Models\Branch::first();
            if ($firstBranch) {
                $branchId = $firstBranch->id;
            }
        }
        
        $syllabi = $branchId ? Syllabus::where('branch_id', $branchId)->get() : collect();
        return view('admin.syllabus.index', compact('syllabi'))->with('currentRole', 'super_admin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.syllabus.create')->with('currentRole', 'super_admin');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'class' => 'required|string',
            'subject' => 'required|string',
            'term' => 'required|string',
            'topics' => 'required|string',
            'duration' => 'required|string',
            'objectives' => 'nullable|string',
        ]);

        // Get branch_id from session or use a default branch
        $branchId = session('selected_branch_id');
        
        if (!$branchId) {
            // Try to get the first available branch
            $firstBranch = \App\Models\Branch::first();
            if ($firstBranch) {
                $branchId = $firstBranch->id;
            } else {
                return redirect()->back()->withErrors(['error' => 'No branch available. Please contact administrator.']);
            }
        }

        $syllabus = Syllabus::create([
            'class' => $request->class,
            'subject' => $request->subject,
            'term' => $request->term,
            'topics' => $request->topics,
            'duration' => $request->duration,
            'objectives' => $request->objectives,
            'branch_id' => $branchId,
        ]);

        return redirect()->route('admin.syllabus.index')
            ->with('success', 'Syllabus created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $syllabus = Syllabus::findOrFail($id);
        return view('admin.syllabus.show', compact('syllabus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $syllabus = Syllabus::findOrFail($id);
        return view('admin.syllabus.edit', compact('syllabus'))->with('currentRole', 'super_admin');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'class' => 'required|string',
            'subject' => 'required|string',
            'term' => 'required|string',
            'topics' => 'required|string',
            'duration' => 'required|string',
            'objectives' => 'nullable|string',
        ]);

        $syllabus = Syllabus::findOrFail($id);
        $syllabus->update($request->all());

        return redirect()->route('admin.syllabus.index')
            ->with('success', 'Syllabus updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $syllabus = Syllabus::findOrFail($id);
        $syllabus->delete();

        return redirect()->route('admin.syllabus.index')
            ->with('success', 'Syllabus deleted successfully!');
    }
}
