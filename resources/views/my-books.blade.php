@extends('layouts.bookverse')

@section('content')
<div class="container mx-auto p-6 lg:p-8">
    <h1 class="text-3xl font-bold tracking-tight text-stone-900 mb-8" style="font-family: 'Newsreader', serif;">
        My Books 📖
    </h1>
    <div class="space-y-12">
        @php
            // Define the order and titles for your shelves
            $shelfOrder = [
                'want_to_read' => 'Want to Read',
                'read' => 'Read',
            ];
            $hasBooks = false;
        @endphp

        @foreach($shelfOrder as $shelfKey => $shelfTitle)
            @if(isset($shelves[$shelfKey]) && $shelves[$shelfKey]->isNotEmpty())
                @php $hasBooks = true; @endphp
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-stone-800 border-b pb-2 mb-4" style="font-family: 'Newsreader', serif;">
                        {{ $shelfTitle }} ({{ $shelves[$shelfKey]->count() }})
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-x-4 gap-y-6">
                        @foreach($shelves[$shelfKey] as $book)
                            <div class="group">
                                <a href="{{ route('books.show', $book->id) }}">
                                    <div class="mb-2 aspect-[2/3] w-full rounded-md bg-cover bg-center bg-no-repeat book-cover" style='background-image: url("{{ $book->image }}");'></div>
                                    <h3 class="font-semibold text-stone-800 group-hover:text-green-600 text-sm truncate">{{ $book->title }}</h3>
                                    <p class="text-xs text-stone-500">{{ $book->author->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if(!$hasBooks)
            <div class="text-center py-12 border-2 border-dashed border-stone-300 rounded-lg">
                <p class="text-stone-500">You haven't added any books to your shelves yet.</p>
                <a href="{{ route('books.index') }}" class="mt-4 inline-block rounded-md bg-green-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition-colors hover:bg-green-700">
                    Browse & Add Books
                </a>
            </div>
        @endif
    </div>
</div>
@endsection