<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ELibraryController extends Controller
{
    /**
     * Display all books for the E-Library interface.
     */
    public function index(Request $request)
    {
        $query = Book::with('branch');

        // Filter by search query
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->get();
        $categories = Book::distinct()->pluck('category')->filter()->values();

        return view('resources.elibrary', compact('books', 'categories'));
    }

    /**
     * Stream the actual PDF file for reading.
     */
    public function read(Book $book)
    {
        // Check if file exists
        if (!Storage::exists($book->file_path)) {
            abort(404, 'File not found');
        }

        // Get the file content
        $file = Storage::get($book->file_path);
        $filename = Str::slug($book->title) . '.pdf';

        return response($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    /**
     * Download the actual PDF file.
     */
    public function download(Book $book)
    {
        // Check if file exists
        if (!Storage::exists($book->file_path)) {
            abort(404, 'File not found');
        }

        $filename = Str::slug($book->title) . '.pdf';
        return Storage::download($book->file_path, $filename);
    }

    /**
     * Fallback method for title-based access (keeping old routes working).
     */
    public function readByTitle(string $title)
    {
        $humanTitle = Str::of($title)->replace('-', ' ')->title();
        
        // Try to find book by title
        $book = Book::where('title', 'like', "%{$humanTitle}%")->first();
        
        if ($book && Storage::exists($book->file_path)) {
            return $this->read($book);
        }

        // Fallback to generated PDF
        $pdf = Pdf::loadView('exports.elibrary-book', [
            'title' => (string) $humanTitle,
            'mode' => 'preview',
        ]);

        $filename = Str::slug($humanTitle) . '.pdf';
        return $pdf->stream($filename);
    }

    /**
     * Fallback method for title-based download (keeping old routes working).
     */
    public function downloadByTitle(string $title)
    {
        $humanTitle = Str::of($title)->replace('-', ' ')->title();
        
        // Try to find book by title
        $book = Book::where('title', 'like', "%{$humanTitle}%")->first();
        
        if ($book && Storage::exists($book->file_path)) {
            return $this->download($book);
        }

        // Fallback to generated PDF
        $pdf = Pdf::loadView('exports.elibrary-book', [
            'title' => (string) $humanTitle,
            'mode' => 'download',
        ]);

        $filename = Str::slug($humanTitle) . '.pdf';
        return $pdf->download($filename);
    }
}


