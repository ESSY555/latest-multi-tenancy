<?php

namespace App\Http\Controllers;

use App\Models\SchoolNews;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class SchoolNewsController extends Controller
{
    public function __construct()
    {
        // Only require authentication for management operations
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy', 'admin']);
        $this->middleware('super.admin')->only(['create', 'store', 'edit', 'update', 'destroy', 'admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SchoolNews::with(['author', 'branch'])
            ->published()
            ->latest();

        // Filter by branch if specified
        if ($request->has('branch_id') && $request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        $news = $query->paginate(12);
        $branches = Branch::orderBy('name')->get();

        return view('school-news.index', compact('news', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('school-news.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = $request->all();
        $data['author_id'] = Auth::id();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news-images', 'public');
            $data['image_path'] = $imagePath;
        }

        // Set published_at if publishing
        if ($request->boolean('is_published') && !$request->published_at) {
            $data['published_at'] = now();
        }

        SchoolNews::create($data);

        return redirect()->route('school-news.index')
            ->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolNews $schoolNews)
    {
        // Check if user can view this news (published or super admin)
        $user = Auth::user();
        if (!$schoolNews->is_published && (!$user || !$user->is_super_admin)) {
            abort(404);
        }

        $schoolNews->load(['author', 'branch']);
        $relatedNews = SchoolNews::published()
            ->where('id', '!=', $schoolNews->id)
            ->where('branch_id', $schoolNews->branch_id)
            ->latest()
            ->limit(3)
            ->get();

        return view('school-news.show', compact('schoolNews', 'relatedNews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolNews $schoolNews)
    {
        $branches = Branch::orderBy('name')->get();
        return view('school-news.edit', compact('schoolNews', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolNews $schoolNews)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($schoolNews->image_path) {
                Storage::disk('public')->delete($schoolNews->image_path);
            }
            
            $imagePath = $request->file('image')->store('news-images', 'public');
            $data['image_path'] = $imagePath;
        }

        // Set published_at if publishing
        if ($request->boolean('is_published') && !$request->published_at && !$schoolNews->published_at) {
            $data['published_at'] = now();
        }

        $schoolNews->update($data);

        return redirect()->route('school-news.show', $schoolNews)
            ->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolNews $schoolNews)
    {
        // Delete image if exists
        if ($schoolNews->image_path) {
            Storage::disk('public')->delete($schoolNews->image_path);
        }

        $schoolNews->delete();

        return redirect()->route('school-news.index')
            ->with('success', 'News article deleted successfully.');
    }

    /**
     * Admin dashboard for managing all news
     */
    public function admin()
    {
        $news = SchoolNews::with(['author', 'branch'])
            ->latest()
            ->paginate(20);

        return view('school-news.admin', compact('news'));
    }
}
