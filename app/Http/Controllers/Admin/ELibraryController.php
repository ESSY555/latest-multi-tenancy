<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ELibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('branch')->latest()->paginate(20);
        return view('admin.elibrary.index', compact('books'))->with('currentRole', 'super_admin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.elibrary.create', compact('branches'))->with('currentRole', 'super_admin');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1900|max:2030',
            'publisher' => 'nullable|string|max:255',
            'language' => 'required|string|max:50',
            'status' => 'required|in:available,borrowed,reserved',
            'branch_id' => 'nullable|exists:branches,id',
            'pdf_file' => 'required|file|mimes:pdf|max:51200', // 50MB max
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
            // Store PDF file
            $pdfPath = $request->file('pdf_file')->store('books', 'public');
            
            // Store cover image if provided
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('covers', 'public');
            }

            // Create book record
            Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'category' => $request->category,
                'isbn' => $request->isbn,
                'file_path' => $pdfPath,
                'cover_image' => $coverPath,
                'status' => $request->status,
                'branch_id' => $request->branch_id ?: null,
            ]);

            return redirect()->route('admin.elibrary.index')
                ->with('success', 'Book added successfully!');

        } catch (\Exception $e) {
            // Clean up uploaded files if book creation fails
            if (isset($pdfPath) && Storage::disk('public')->exists($pdfPath)) {
                Storage::disk('public')->delete($pdfPath);
            }
            if (isset($coverPath) && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to add book: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('branch');
        return view('admin.elibrary.show', compact('book'))->with('currentRole', 'super_admin');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $branches = Branch::orderBy('name')->get();
        return view('admin.elibrary.edit', compact('book', 'branches'))->with('currentRole', 'super_admin');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:50',
            'publication_year' => 'nullable|integer|min:1900|max:2030',
            'publisher' => 'nullable|string|max:255',
            'language' => 'required|string|max:50',
            'status' => 'required|in:available,borrowed,reserved',
            'branch_id' => 'nullable|exists:branches,id',
            'pdf_file' => 'nullable|file|mimes:pdf|max:51200', // 50MB max
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
            $data = $request->except(['pdf_file', 'cover_image']);

            // Handle PDF file update
            if ($request->hasFile('pdf_file')) {
                // Delete old PDF
                if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                    Storage::disk('public')->delete($book->file_path);
                }
                // Store new PDF
                $data['file_path'] = $request->file('pdf_file')->store('books', 'public');
            }

            // Handle cover image update
            if ($request->hasFile('cover_image')) {
                // Delete old cover
                if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }
                // Store new cover
                $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
            }

            $book->update($data);

            return redirect()->route('admin.elibrary.index')
                ->with('success', 'Book updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update book: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            // Delete associated files
            if ($book->file_path && Storage::disk('public')->exists($book->file_path)) {
                Storage::disk('public')->delete($book->file_path);
            }
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->delete();

            return redirect()->route('admin.elibrary.index')
                ->with('success', 'Book deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to delete book: ' . $e->getMessage()]);
        }
    }
}
