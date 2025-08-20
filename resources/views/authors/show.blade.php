@extends('layouts.app')
@section('content')
<div class="flex items-start gap-6">
  <img src="{{ $author->photo }}" class="w-32 h-32 object-cover rounded" alt="author">
  <div>
    <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
    <p class="mt-2">{{ $author->biography }}</p>
  </div>
</div>

<h2 class="text-xl font-semibold mt-6 mb-3">Books</h2>
<div class="grid md:grid-cols-4 gap-4">
  @foreach($author->books as $book)
    <a href="{{ route('books.show', $book->id) }}" class="bg-white rounded-lg shadow p-3 block">
      <img src="{{ $book->cover_url }}" class="w-full h-48 object-cover rounded" alt="cover">
      <div class="mt-2 font-semibold">{{ $book->title }}</div>
    </a>
  @endforeach
</div>
@endsection
