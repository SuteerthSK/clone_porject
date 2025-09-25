@extends('layouts.admin')

@section('header-title', isset($book) ? 'Edit Book' : 'Add New Book')

@section('header-actions')
    <a href="{{ route('admin.books.index') }}" 
       class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors">
        Back to Books
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form method="POST" 
          action="{{ isset($book) ? route('admin.books.update', $book->id) : route('admin.books.store') }}" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @if(isset($book))
            @method('PUT')
        @endif

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" value="{{ old('title', $book->title ?? '') }}" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
        </div>

        {{-- Author --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Author</label>
            <select name="author_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                <option value="">Select an author</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" 
                        {{ old('author_id', $book->author_id ?? '') == $author->id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="4" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('description', $book->description ?? '') }}</textarea>
        </div>

        {{-- Book Cover --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Book Cover</label>
            @if(isset($book) && $book->image)
                <img src="{{ $book->image }}" alt="Current cover" class="w-24 h-32 object-cover rounded mb-2">
            @endif
            <input type="file" name="cover_image" class="mt-1 block w-full text-sm text-gray-500">
        </div>
        @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-3 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        {{-- Submit --}}
        <div>
            <button type="submit" 
                    class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors">
                {{ isset($book) ? 'Update Book' : 'Add Book' }}
            </button>
        </div>
    </form>
</div>
@endsection
