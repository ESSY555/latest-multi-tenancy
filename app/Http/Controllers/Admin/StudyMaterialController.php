<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    /**
     * Display a listing of study materials.
     */
    public function index()
    {
        $materials = StudyMaterial::with(['uploader', 'branch'])->latest()->paginate(20);
        return view('admin.materials.index', compact('materials'))->with('currentRole', 'super_admin');
    }

    /**
     * Show the form for creating a new study material.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.materials.create', compact('branches'))->with('currentRole', 'super_admin');
    }

    /**
     * Store a newly created study material.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'required|string|max:100',
            'class_level' => 'nullable|string|max:50',
            'type' => 'required|string|max:50',
            'file' => 'required|file|max:102400', // 100MB max
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        try {
            // Store the file
            $filePath = $request->file('file')->store('materials', 'public');
            $fileSize = $request->file('file')->getSize();

            // Calculate duration for videos
            $duration = null;
            if (in_array(strtolower($request->type), ['video', 'mp4', 'avi', 'mov'])) {
                // For now, we'll set a default duration. In production, you might want to use FFmpeg
                $duration = 0; // This would be calculated from the actual video file
            }

            // Create the study material
            StudyMaterial::create([
                'title' => $request->title,
                'description' => $request->description,
                'subject' => $request->subject,
                'class_level' => $request->class_level,
                'type' => $request->type,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'duration' => $duration,
                'uploaded_by' => Auth::id(),
                'branch_id' => $request->branch_id ?: null,
                'is_active' => true,
            ]);

            return redirect()->route('admin.materials.index')
                ->with('success', 'Study material uploaded successfully!');

        } catch (\Exception $e) {
            // Clean up uploaded file if creation fails
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to upload material: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified study material.
     */
    public function show(StudyMaterial $material)
    {
        $material->load(['uploader', 'branch']);
        return view('admin.materials.show', compact('material'))->with('currentRole', 'super_admin');
    }

    /**
     * Show the form for editing the specified study material.
     */
    public function edit(StudyMaterial $material)
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.materials.edit', compact('material', 'branches'))->with('currentRole', 'super_admin');
    }

    /**
     * Update the specified study material.
     */
    public function update(Request $request, StudyMaterial $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'required|string|max:100',
            'class_level' => 'nullable|string|max:50',
            'type' => 'required|string|max:50',
            'file' => 'nullable|file|max:102400', // 100MB max
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
        ]);

        try {
            $data = $request->except(['file']);

            // Handle file update
            if ($request->hasFile('file')) {
                // Delete old file
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }

                // Store new file
                $data['file_path'] = $request->file('file')->store('materials', 'public');
                $data['file_size'] = $request->file('file')->getSize();

                // Calculate duration for videos
                if (in_array(strtolower($request->type), ['video', 'mp4', 'avi', 'mov'])) {
                    $data['duration'] = 0; // This would be calculated from the actual video file
                }
            }

            $material->update($data);

            return redirect()->route('admin.materials.index')
                ->with('success', 'Study material updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update material: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified study material.
     */
    public function destroy(StudyMaterial $material)
    {
        try {
            // Delete associated file
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $material->delete();

            return redirect()->route('admin.materials.index')
                ->with('success', 'Study material deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete material: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle material status (active/inactive).
     */
    public function toggleStatus(StudyMaterial $material)
    {
        $material->update(['is_active' => !$material->is_active]);

        $status = $material->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Material {$status} successfully!");
    }
}
