<?php

namespace App\Http\Controllers;

use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudyMaterialController extends Controller
{
    /**
     * Display a listing of study materials with search and filters.
     */
    public function index(Request $request)
    {
        $query = StudyMaterial::with(['uploader', 'branch'])->active();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($request->filled('subject') && $request->subject !== 'All Subjects') {
            $query->bySubject($request->subject);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'All Types') {
            $query->byType($request->type);
        }

        $materials = $query->latest()->paginate(12);

        // Get stats for the page
        $stats = [
            'total' => StudyMaterial::active()->count(),
            'pdfs' => StudyMaterial::active()->byType('PDF')->count(),
            'videos' => StudyMaterial::active()->byType('Video')->count(),
            'worksheets' => StudyMaterial::active()->byType('Worksheet')->count(),
        ];

        return view('resources.materials', compact('materials', 'stats'));
    }

    /**
     * Download a study material.
     */
    public function download(StudyMaterial $material)
    {
        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File not found');
        }

        // Increment download count
        $material->incrementDownloads();

        return Storage::disk('public')->download($material->file_path, $material->title . '.' . $this->getFileExtension($material->type));
    }

    /**
     * View/Stream a study material (for videos, PDFs, etc.).
     */
    public function view(StudyMaterial $material)
    {
        if (!Storage::disk('public')->exists($material->file_path)) {
            abort(404, 'File not found');
        }

        // Increment view count
        $material->incrementViews();

        $filePath = $material->file_path;
        $fileName = basename($filePath);
        $mimeType = Storage::disk('public')->mimeType($filePath);

        return response()->file(storage_path('app/public/' . $filePath), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    /**
     * Get file extension based on type.
     */
    private function getFileExtension($type): string
    {
        $extensions = [
            'PDF' => 'pdf',
            'Video' => 'mp4',
            'Presentation' => 'pptx',
            'Worksheet' => 'docx',
            'Document' => 'docx',
            'Image' => 'jpg',
            'Audio' => 'mp3',
        ];

        return $extensions[$type] ?? 'bin';
    }

    /**
     * Get materials for the StudyMaterials component.
     */
    public function getMaterials(Request $request)
    {
        $query = StudyMaterial::with(['uploader', 'branch'])->active();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($request->filled('subject') && $request->subject !== 'All Subjects') {
            $query->bySubject($request->subject);
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== 'All Types') {
            $query->byType($request->type);
        }

        $materials = $query->latest()->get();

        // Get stats
        $stats = [
            'total' => StudyMaterial::active()->count(),
            'pdfs' => StudyMaterial::active()->byType('PDF')->count(),
            'videos' => StudyMaterial::active()->byType('Video')->count(),
            'worksheets' => StudyMaterial::active()->byType('Worksheet')->count(),
        ];

        return response()->json([
            'materials' => $materials,
            'stats' => $stats
        ]);
    }
}
