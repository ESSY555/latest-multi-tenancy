<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource for public viewing.
     */
    public function index()
    {
        $galleries = Gallery::active()
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Gallery::active()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('gallery.index', compact('galleries', 'categories'));
    }

    /**
     * Show the form for creating a new resource (super admin only).
     */
    public function create()
    {
        $this->authorize('super_admin');
        
        $branches = Branch::orderBy('name')->get();
        $categories = ['general', 'events', 'activities', 'facilities', 'students', 'teachers', 'achievements'];
        
        return view('gallery.create', compact('branches', 'categories'));
    }

    /**
     * Store a newly created resource in storage (super admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('super_admin');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:100',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0'
        ]);

        $imagePath = $request->file('image')->store('gallery', 'public');

        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'category' => $request->category,
            'branch_id' => $request->branch_id,
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0
        ]);

        return redirect()->route('gallery.admin')
            ->with('success', 'Gallery item created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        if (!$gallery->is_active) {
            abort(404);
        }

        return view('gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource (super admin only).
     */
    public function edit(Gallery $gallery)
    {
        $this->authorize('super_admin');
        
        $branches = Branch::orderBy('name')->get();
        $categories = ['general', 'events', 'activities', 'facilities', 'students', 'teachers', 'achievements'];
        
        return view('gallery.edit', compact('gallery', 'branches', 'categories'));
    }

    /**
     * Update the specified resource in storage (super admin only).
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('super_admin');
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|max:100',
            'branch_id' => 'nullable|exists:branches,id',
            'is_active' => 'boolean',
            'display_order' => 'nullable|integer|min:0'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'branch_id' => $request->branch_id,
            'is_active' => $request->has('is_active'),
            'display_order' => $request->display_order ?? 0
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('gallery.admin')
            ->with('success', 'Gallery item updated successfully!');
    }

    /**
     * Remove the specified resource from storage (super admin only).
     */
    public function destroy(Gallery $gallery)
    {
        $this->authorize('super_admin');
        
        // Delete image file
        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return redirect()->route('gallery.admin')
            ->with('success', 'Gallery item deleted successfully!');
    }

    /**
     * Admin panel for managing gallery (super admin only).
     */
    public function admin()
    {
        $this->authorize('super_admin');
        
        $galleries = Gallery::with('branch')
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        $branches = Branch::orderBy('name')->get();
        $categories = ['general', 'events', 'activities', 'facilities', 'students', 'teachers', 'achievements'];
        
        // Get current branch from session
        $branchId = session('current_branch_id');
        $branch = null;
        if ($branchId) {
            $branch = Branch::find($branchId);
        }
        
        return view('gallery.admin', compact('galleries', 'branches', 'categories', 'branch'));
    }

    /**
     * Toggle gallery item status (super admin only).
     */
    public function toggleStatus(Gallery $gallery)
    {
        $this->authorize('super_admin');
        
        $gallery->update(['is_active' => !$gallery->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $gallery->is_active
        ]);
    }
}
