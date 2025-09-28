<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    
    public function index()
    {
        $favoriteBooks = Auth::user()->favorites()->with('author')->get();
        return view('favourites', ['books' => $favoriteBooks]);
    }

    
    public function toggle(Book $book)
    {
        Auth::user()->favorites()->toggle($book->id);
        return response()->json(['status' => 'success']);
    }
}