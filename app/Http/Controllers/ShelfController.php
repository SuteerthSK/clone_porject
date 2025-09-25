<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\ReadingChallenge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShelfController extends Controller
{
    // ... (myBooksIndex, wantToReadIndex, addBook, removeBook methods are fine) ...
    public function myBooksIndex()
    {
        $shelves = Auth::user()->shelves()->with('author')->get()->groupBy('pivot.shelf');
        return view('my-books', compact('shelves'));
    }

    public function wantToReadIndex()
    {
        $books = Auth::user()->shelves()->where('shelf', 'want_to_read')->with('author')->get();
        return view('want_to_read', compact('books'));
    }

    public function addBook(Request $request, Book $book)
    {
        $user = Auth::user();
        $shelf = $request->get('shelf', 'want_to_read');
        $user->shelves()->syncWithoutDetaching([$book->id => ['shelf' => $shelf]]);
        return response()->json(['message' => 'Book added to shelf.', 'shelf' => $shelf]);
    }

    public function removeBook(Book $book)
    {
        Auth::user()->shelves()->detach($book->id);
        return response()->json(['message' => 'Book removed from shelves.', 'shelf' => null]);
    }

    /**
     * 👇 REPLACE THIS ENTIRE METHOD 👇
     */
    public function markAsRead(Request $request, Book $book)
    {
        $user = Auth::user();
        $currentStatus = $user->shelves()->where('book_id', $book->id)->first();

        // Prevent double counting if already marked as read
        if ($currentStatus && $currentStatus->pivot->shelf === 'read') {
            return response()->json(['message' => 'Book is already read.', 'shelf' => 'read']);
        }

        DB::transaction(function () use ($user, $book) {
            // Update the shelf status
            $user->shelves()->syncWithoutDetaching([$book->id => ['shelf' => 'read']]);

            // Get or create the reading challenge for the current year
            $challenge = ReadingChallenge::firstOrCreate(
                ['user_id' => $user->id, 'year' => now()->year],
                ['goal_count' => 20] // Default goal if creating new
            );

            // Increment the progress
            $challenge->increment('progress_count');
        });

        return response()->json(['message' => 'Book marked as read!', 'shelf' => 'read']);
    }

    /**
     * 👇 REPLACE THIS ENTIRE METHOD 👇
     */
    public function unmarkAsRead(Request $request, Book $book)
    {
        $user = Auth::user();
        $currentStatus = $user->shelves()->where('book_id', $book->id)->first();

        if (!$currentStatus || $currentStatus->pivot->shelf !== 'read') {
            return response()->json(['message' => 'This book is not on your read shelf.'], 409);
        }

        DB::transaction(function () use ($user, $book) {
            // Remove the book from all shelves
            $user->shelves()->detach($book->id);

            // Find the reading challenge for the current year
            $challenge = ReadingChallenge::where('user_id', $user->id)
                                         ->where('year', now()->year)
                                         ->first();

            // If a challenge exists and progress is > 0, decrement it
            if ($challenge && $challenge->progress_count > 0) {
                $challenge->decrement('progress_count');
            }
        });

        return response()->json(['message' => 'Book removed from read list.', 'shelf' => null]);
    }
}