@extends('layouts.app')
@section('content')
<div class="flex flex-col lg:flex-row items-start gap-6 p-6 bg-gray-900 text-gray-100 rounded-lg shadow-md">
  {{-- Author Photo --}}
  <img src="{{ $author->photo }}" class="w-32 h-32 object-cover rounded-lg border border-gray-700 shadow-md" alt="author">

  {{-- Author Info --}}
  <div class="flex-1">
    <h1 class="text-3xl font-bold text-white">{{ $author->name }}</h1>
    <p class="mt-2 text-gray-300 leading-relaxed">{{ $author->biography }}</p>
  </div>
</div>

{{-- Books Section --}}
<h2 class="text-2xl font-semibold mt-8 mb-4 text-white">Books</h2>
<div class="grid md:grid-cols-4 gap-6">
  @foreach($author->books as $book)
    <a href="{{ route('books.show', $book->id) }}" class="bg-gray-800 rounded-xl shadow-md p-3 block hover:scale-105 hover:shadow-lg transition-transform duration-200">
      <img src="{{ $book->cover_url }}" class="w-full h-48 object-cover rounded-md border border-gray-700" alt="cover">
      <div class="mt-2 font-semibold text-white truncate">{{ $book->title }}</div>
    </a>
  @endforeach
</div>
@endsection
