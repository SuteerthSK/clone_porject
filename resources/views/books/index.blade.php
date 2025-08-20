@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">Books</h1>
<form action="{{ route('books.search') }}" method="GET" class="flex gap-2 mb-4">
  <input name="q" value="{{ $q }}" placeholder="Search by title or author..." class="border rounded p-2 flex-1"/>
  <button class="bg-blue-600 text-white px-4 rounded">Search</button>
</form>

@if($books->count() === 0)
  <p>No books found.</p>
@else
  <div class="grid md:grid-cols-4 gap-4">
    @foreach($books as $book)
      <a href="{{ route('books.show', $book->id) }}" class="bg-white rounded-lg shadow p-3 block">
        <img src="{{ $book->cover_url }}" class="w-full h-48 object-cover rounded" alt="cover">
        <div class="mt-2 font-semibold">{{ $book->title }}</div>
        <div class="text-sm text-gray-600">{{ optional($book->author)->name }}</div>
      </a>
    @endforeach
  </div>
  <div class="mt-4">{{ $books->withQueryString()->links() }}</div>
@endif
@endsection
