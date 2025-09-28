<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingChallenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('author')->latest()->get();
        $favoriteBookIds = [];
        $challenge = null;

        if (Auth::check()) {
            $favoriteBookIds = Auth::user()
                ->favorites()
                ->pluck('books.id')
                ->toArray();
            
            
            $challenge = Auth::user()
                ->readingChallenges()
                ->where('year', now()->year)
                ->first();
        }
        
        return view('books.index', compact('books', 'favoriteBookIds', 'challenge'));
    }

    public function show(Book $book)
    {
        
        $book->load(['author']);
        
        $bookShelfState = null;
        if (Auth::check()) {
            $shelfBook = Auth::user()
                ->shelves()
                ->where('book_id', $book->id)
                ->first();
                
            if ($shelfBook) {
                $bookShelfState = $shelfBook->pivot->shelf;
            }
        }
        
        return view('books.show', compact('book', 'bookShelfState'));
    }
}
