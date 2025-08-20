<?php

namespace App\Http\Controllers;

use App\Models\Author;

class AuthorController extends Controller
{
    public function show(Author $author)
    {
        $author->load('books');
        return view('authors.show', compact('author'));
    }
}
