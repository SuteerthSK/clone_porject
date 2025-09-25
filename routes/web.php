<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReadingChallengeController;
use App\Http\Controllers\ReviewController;

// This main group will now attempt to authenticate the user on every request,
// allowing the header to correctly show the user's logged-in state.
Route::middleware('jwt.optional')->group(function () {
    
    Route::get('/', fn() => view('welcome'))->name('home');

    // Auth Views
    Route::view('/register', 'auth.register')->name('auth.register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::view('/login', 'auth.login')->name('auth.login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Public Books routes
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // Protected User Routes (these require a valid login)
    // The inner 'jwt' middleware will redirect to login if the user is not authenticated.
    Route::middleware('jwt')->group(function () {
        Route::get('/my-books', [ShelfController::class, 'myBooksIndex'])->name('my-books.view');
        Route::get('/favourites', [FavoriteController::class, 'index'])->name('favourites.view');
        Route::get('/want-to-read', [ShelfController::class, 'wantToReadIndex'])->name('want_to_read.view');

        Route::post('/books/{book}/toggle-favorite', [FavoriteController::class, 'toggle'])->name('books.toggle_favorite');
        Route::post('/books/{book}/shelves', [ShelfController::class, 'addBook'])->name('shelves.add');
        Route::delete('/books/{book}/shelves', [ShelfController::class, 'removeBook'])->name('shelves.remove');
        Route::get('/shelves', [ShelfController::class, 'listShelves'])->name('shelves.list');
        Route::post('/books/{book}/mark-as-read', [ShelfController::class, 'markAsRead'])->name('shelves.mark_as_read');
        Route::post('/books/{book}/unmark-as-read', [ShelfController::class, 'unmarkAsRead'])->name('shelves.unmark_as_read');

        // Reviews
        Route::get('/books/{book}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

        // Challenge
        Route::get('/challenge', [ReadingChallengeController::class, 'edit'])->name('challenges.edit');
        Route::post('/challenge', [ReadingChallengeController::class, 'update'])->name('challenges.update');

        // Admin Routes (these require both a valid login and admin role)
        Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
            Route::get('/books', [AdminController::class, 'index'])->name('books.index');
            Route::get('/books/create', [AdminController::class, 'create'])->name('books.create');
            Route::post('/books', [AdminController::class, 'store'])->name('books.store');
            Route::delete('/books/{book}', [AdminController::class, 'destroy'])->name('books.destroy');
            Route::resource('authors', AuthorController::class)->except(['show']);
            Route::get('/books/{book}/edit', [AdminController::class, 'edit'])->name('books.edit');
            Route::put('/books/{book}', [AdminController::class, 'update'])->name('books.update');
        });
    });
});