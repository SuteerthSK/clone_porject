<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the list of all books in the admin panel.
     */
    public function index()
    {
        $books = Book::with('author')->latest()->get();
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $authors = Author::orderBy('name')->get();
        return view('admin.books.create', compact('authors'));
    }

    /**
     * Store a newly created book in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author_id'   => 'required|exists:authors,id',
            'description' => 'required|string',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('cover_image')) {
            // Upload file to Cloudinary using the uploadApi
            $uploadedFile = cloudinary()->uploadApi()->upload(
                $request->file('cover_image')->getRealPath()
            );

            $imageUrl = $uploadedFile['secure_url'];
        }

        // Generate a unique 13-digit ISBN
        do {
            $isbn = (string) random_int(1000000000000, 9999999999999);
        } while (Book::where('isbn', $isbn)->exists());

        // Create the new book record with the Cloudinary URL + auto ISBN
        Book::create([
            'title'       => $validated['title'],
            'author_id'   => $validated['author_id'],
            'description' => $validated['description'],
            'image'       => $imageUrl,
            'isbn'        => $isbn,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy(Book $book)
    {
        if ($book->image) {
            // Extract the public ID from the Cloudinary URL (handles nested paths)
            $parsedUrl = parse_url($book->image, PHP_URL_PATH);
            $publicIdWithExtension = basename($parsedUrl);
            $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);

            if ($publicId) {
                cloudinary()->uploadApi()->destroy($publicId);
            }
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }

    /**
     * Show the form for editing a book.
     */
    public function edit(Book $book)
    {
        $authors = Author::orderBy('name')->get();
        // ✅ Reuse the create.blade.php form
        return view('admin.books.create', compact('book', 'authors'));
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'author_id'   => 'required|exists:authors,id',
            'description' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $imageUrl = $book->image;

        if ($request->hasFile('cover_image')) {
            // Optional: delete old image from Cloudinary
            if ($book->image) {
                $parsedUrl = parse_url($book->image, PHP_URL_PATH);
                $publicIdWithExtension = basename($parsedUrl);
                $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);
                if ($publicId) {
                    cloudinary()->uploadApi()->destroy($publicId);
                }
            }

            $uploadedFile = cloudinary()->uploadApi()->upload(
                $request->file('cover_image')->getRealPath()
            );
            $imageUrl = $uploadedFile['secure_url'];
        }

        $book->update([
            'title'       => $validated['title'],
            'author_id'   => $validated['author_id'],
            'description' => $validated['description'],
            'image'       => $imageUrl,
        ]);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }
}
