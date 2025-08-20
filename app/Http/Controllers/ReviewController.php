<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use App\Events\ReviewLiked;
use App\Models\ReviewLike;
use App\Models\ReviewComment;

class ReviewController extends Controller
{
    public function store(Request $r, Book $book)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $data = $r->validate(['rating'=>'required|integer|min:0|max:5','body'=>'nullable|string']);
        $review = Review::create([
            'user_id'=>$user->id,
            'book_id'=>$book->id,
            'rating'=>$data['rating'],
            'body'=>$data['body'] ?? null
        ]);
        return redirect()->route('books.show', $book->id)->with('success','Review posted.');
    }

    public function like(Review $review)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $like = ReviewLike::firstOrCreate(['review_id'=>$review->id,'user_id'=>$user->id]);
        broadcast(new ReviewLiked($review, $user->id))->toOthers();
        return response()->json(['liked'=>true]);
    }

    public function comment(Request $r, Review $review)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $data = $r->validate(['body'=>'required|string']);
        $c = ReviewComment::create([
            'review_id'=>$review->id,
            'user_id'=>$user->id,
            'body'=>$data['body']
        ]);
        return response()->json($c,201);
    }
}
