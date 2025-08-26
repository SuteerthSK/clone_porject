<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Jobs\RecalculateRecommendationsJob;

class BookController extends Controller
{
    public function index(Request $r)
{
    $q = $r->get('q','');
    $books = Book::with('author')
        ->when($q, function($query) use ($q) {
            $query->where('title','like',"%{$q}%")
                  ->orWhereHas('author', fn($q2)=> $q2->where('name','like',"%{$q}%"));
        })
        ->orderBy('created_at','desc')
        ->paginate(12);

    return view('books.index', compact('books','q'));
}

    public function search(Request $r)
    {
        $q = $r->get('q','');
        $books = Book::with('author')
            ->when($q, function($query) use ($q) {
                $query->where('title','like',"%{$q}%")
                    ->orWhereHas('author', fn($q2)=> $q2->where('name','like',"%{$q}%"));
            })
            ->orderBy('created_at','desc')
            ->paginate(12);

        return view('books.index', compact('books','q'));
    }

    public function show(Book $book)
    {
        $cacheKey = "book:{$book->id}";
        $book = Cache::remember($cacheKey, 3600, function() use ($book) {
            return $book->load(['author','reviews.user']);
        });
        return view('books.show', compact('book'));
    }

    public function recalcRecommendations(Request $r)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'], 401);
        RecalculateRecommendationsJob::dispatch($user->id);
        return response()->json(['message'=>'Recommendation recalculation queued.']);
    }
}
