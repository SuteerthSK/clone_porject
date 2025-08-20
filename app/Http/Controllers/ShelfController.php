<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class ShelfController extends Controller
{
    public function create(Request $r)
    {
        $r->validate(['name'=>'required|string']);
        return response()->json(['message'=>'Use addBook with shelf param to create/assign.']);
    }

    public function addBook(Request $r, Book $book)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $shelf = $r->get('shelf','want_to_read');
        $user->shelves()->syncWithoutDetaching([$book->id => ['shelf'=>$shelf]]);
        return redirect()->route('books.show', $book->id)->with('success','Added to shelf: '.$shelf);
    }

    public function removeBook(Request $r, Book $book)
    {
        $user = auth('api')->user();
        if (!$user) return response()->json(['error'=>'Unauthorized'],401);

        $user->shelves()->detach($book->id);
        return redirect()->route('books.show', $book->id)->with('success','Removed from shelves');
    }
}
