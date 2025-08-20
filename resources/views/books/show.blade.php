@extends('layouts.app')
@section('content')
<div class="flex gap-6">
  <img src="{{ $book->cover_url }}" class="w-48 h-72 object-cover rounded" alt="cover">
  <div class="flex-1">
    <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
    <p class="text-sm text-gray-600">by 
      @if($book->author)
        <a class="underline" href="{{ route('authors.show', $book->author->id) }}">{{ $book->author->name }}</a>
      @else
        Unknown
      @endif
    </p>
    <p class="mt-3">{{ Str::limit($book->description, 400) }}</p>

    <form class="mt-4 flex gap-2" method="POST" action="{{ route('shelves.add', $book->id) }}">
      @csrf
      <select name="shelf" class="border rounded p-2">
        <option value="want_to_read">Want to Read</option>
        <option value="currently_reading">Currently Reading</option>
        <option value="read">Read</option>
      </select>
      <button class="bg-blue-600 text-white px-3 py-2 rounded">Add to Shelf</button>
    </form>

    <form class="mt-2" method="POST" action="{{ route('shelves.remove', $book->id) }}">
      @csrf
      <button class="bg-gray-200 px-3 py-2 rounded">Remove from Shelves</button>
    </form>
  </div>
</div>

<hr class="my-6">

<div>
  <h2 class="text-xl font-semibold">Reviews</h2>

  <form class="bg-white p-4 rounded shadow mt-3" method="POST" action="{{ route('reviews.store', $book->id) }}">
    @csrf
    <label class="block text-sm">Rating (0-5)</label>
    <input type="number" name="rating" min="0" max="5" class="border rounded p-2 w-24" required />
    <label class="block text-sm mt-2">Your review</label>
    <textarea name="body" class="w-full border rounded p-2" rows="3"></textarea>
    <button class="mt-3 bg-green-600 text-white px-3 py-2 rounded">Submit Review</button>
    <p class="text-xs text-gray-500 mt-2">Note: Requires JWT auth; use Postman to login and set your local demoUserId to see Pusher demo.</p>
  </form>

  @forelse($book->reviews as $review)
    <div class="border p-4 rounded mt-3 bg-white">
      <div class="flex justify-between">
        <div><strong>{{ $review->user->name }}</strong> — {{ $review->rating }}/5</div>
        <div>{{ $review->created_at->diffForHumans() }}</div>
      </div>
      <p class="mt-2">{{ $review->body }}</p>
    </div>
  @empty
    <p class="text-sm text-gray-600 mt-2">No reviews yet.</p>
  @endforelse
</div>
@endsection
