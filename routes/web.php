<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ReadingChallengeController;

// Home
Route::get('/', function() { return view('welcome'); })->name('home');
// Route::view('/register', 'auth.register')->name('auth.register');
// Route::view('/login', 'auth.login')->name('auth.login');


// // Public browsing
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
 Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
 Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');

// // Auth (JWT)
Route::view('/auth/register', 'auth.register')->name('auth.register.view');
 Route::view('/auth/login', 'auth.login')->name('auth.login.view');
// Route::get('/auth/login', [AuthController::class, 'register'])->name('auth.register');
// Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

// // Protected API-like routes guarded by JWT
 Route::middleware(['jwt.auth'])->group(function () {
     Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
     Route::get('/me', [AuthController::class, 'me'])->name('auth.me');

//     // Shelves
     Route::post('/shelves', [ShelfController::class, 'create'])->name('shelves.create');
     Route::post('/shelves/{book}/add', [ShelfController::class, 'addBook'])->name('shelves.add');
     Route::post('/shelves/{book}/remove', [ShelfController::class, 'removeBook'])->name('shelves.remove');

//     // Reviews
     Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
     Route::post('/reviews/{review}/like', [ReviewController::class, 'like'])->name('reviews.like');
     Route::post('/reviews/{review}/comment', [ReviewController::class, 'comment'])->name('reviews.comment');

//     // Recommendations trigger (enqueue recalculation)
     Route::post('/recommendations/recalc', [BookController::class, 'recalcRecommendations'])->name('recs.recalc');

//     // Groups
     Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
     Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
     Route::post('/groups/{group}/posts', [GroupController::class, 'post'])->name('groups.post');

//     // Reading challenge
     Route::post('/reading-challenges', [ReadingChallengeController::class, 'createOrUpdate'])->name('challenge.upsert');
 });
