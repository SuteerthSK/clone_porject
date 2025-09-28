<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 👈 Import the DB facade

class ReviewController extends Controller
{
    
    public function index(Book $book)
    {
        
        $reviews = $book->reviews()->with('user')->latest()->get();

        
        $stats = $book->reviews()
            ->select(
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(rating) as average_rating')
            )
            ->first();

        
        $breakdown = $book->reviews()
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->keyBy('rating'); 

        
        $rating_breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $rating_breakdown[$i] = [
                'count' => $breakdown->has($i) ? $breakdown[$i]->count : 0,
                'percentage' => $stats->total_reviews > 0 ? ($breakdown->has($i) ? $breakdown[$i]->count / $stats->total_reviews * 100 : 0) : 0,
            ];
        }

        
        return response()->json([
            'reviews' => $reviews,
            'stats' => [
                'total_reviews' => (int)$stats->total_reviews,
                'average_rating' => number_format($stats->average_rating, 1),
                'breakdown' => $rating_breakdown,
            ]
        ]);
    }

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:10|max:5000', 
        ]);

        $review = $book->reviews()->create([
            'user_id' => $request->user()->id,
            'rating'  => $validated['rating'],
            'body'    => $validated['body'],
        ]);

        $review->load('user');

        return response()->json($review, 201);
    }
}