<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::latest()->paginate(20);
        return view('branches.index', compact('branches'));
    }

    public function create()
    {
        return view('branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:branches,code'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
            'admin_user_id' => ['nullable', 'exists:users,id'],
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueBranchCode($validated['name']);
        }

        $branch = Branch::create($validated);

        if ($request->filled('admin_user_id')) {
            $user = User::find($request->admin_user_id);
            $branch->users()->syncWithoutDetaching([$user->id => ['role' => 'admin']]);
        }

        return redirect()->route('branches.index')->with('status', 'Branch created');
    }

    public function show(Branch $branch)
    {
        // Get branch statistics
        $stats = [
            'classes' => $branch->classes()->count(),
            'teachers' => $branch->users()->where('role', 'teacher')->count(),
            'students' => $branch->users()->where('role', 'student')->count(),
            'parents' => $branch->users()->where('role', 'parent')->count(),
            'admissions' => \App\Models\AdmissionApplication::where('branch_id', $branch->id)->count(),
            'pending_admissions' => \App\Models\AdmissionApplication::where('branch_id', $branch->id)->where('status', 'pending')->count(),
        ];

        // Get recent activities
        $recentClasses = $branch->classes()->latest()->take(5)->get();
        $recentAdmissions = \App\Models\AdmissionApplication::where('branch_id', $branch->id)->latest()->take(5)->get();
        $recentUsers = $branch->users()->latest()->take(5)->get();
        $recentResults = \App\Models\Result\Result::whereHas('schoolClass', function($query) use ($branch) {
            $query->where('branch_id', $branch->id);
        })->with(['student', 'schoolClass'])->latest()->take(5)->get();

        return view('branches.show', compact('branch', 'stats', 'recentClasses', 'recentAdmissions', 'recentUsers', 'recentResults'));
    }

    private function generateUniqueBranchCode(string $name): string
    {
        $base = Str::upper(Str::substr(Str::slug($name, ''), 0, 6));
        $base = $base !== '' ? $base : 'BRANCH';
        $code = $base;
        $counter = 1;

        while (Branch::where('code', $code)->exists()) {
            $code = $base . $counter;
            $counter++;
        }

        return $code;
    }
    public function edit(Branch $branch)
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:branches,code,' . $branch->id],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $branch->code;
        }

        $branch->update($validated);

        return redirect()->route('branches.index')->with('status', 'Branch updated successfully');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->exists() || $branch->classes()->exists()) {
            return redirect()->route('branches.index')->with('error', 'Cannot delete branch with existing users or classes.');
        }

        $branch->delete();
        return redirect()->route('branches.index')->with('status', 'Branch deleted successfully');
    }
}


