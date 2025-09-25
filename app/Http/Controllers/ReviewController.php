<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 👈 Import the DB facade

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews and stats for a specific book.
     */
    public function index(Book $book)
    {
        // 1. Get all reviews with user data, newest first
        $reviews = $book->reviews()->with('user')->latest()->get();

        // 2. Calculate statistics
        $stats = $book->reviews()
            ->select(
                DB::raw('COUNT(*) as total_reviews'),
                DB::raw('AVG(rating) as average_rating')
            )
            ->first();

        // 3. Get the count for each star rating (1 to 5)
        $breakdown = $book->reviews()
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->keyBy('rating'); // Use rating as the key for easy access

        // Ensure all star levels are present in the breakdown, even if count is 0
        $rating_breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $rating_breakdown[$i] = [
                'count' => $breakdown->has($i) ? $breakdown[$i]->count : 0,
                'percentage' => $stats->total_reviews > 0 ? ($breakdown->has($i) ? $breakdown[$i]->count / $stats->total_reviews * 100 : 0) : 0,
            ];
        }

        // 4. Combine all data into a single response
        return response()->json([
            'reviews' => $reviews,
            'stats' => [
                'total_reviews' => (int)$stats->total_reviews,
                'average_rating' => number_format($stats->average_rating, 1),
                'breakdown' => $rating_breakdown,
            ]
        ]);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:10|max:5000', // Made body required and added min length
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