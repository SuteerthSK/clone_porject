@extends('layouts.bookverse')

@section('content')
<div class="container mx-auto p-6 lg:p-8 bg-gray-900 min-h-screen text-gray-100">
    <!-- Page Title -->
    <h1 class="text-3xl font-extrabold tracking-tight mb-8 text-white" style="font-family: 'Newsreader', serif;">
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
                <div class="bg-gray-800/40 p-5 rounded-xl border border-gray-700 shadow-md hover:shadow-lg transition">
                    <h2 class="text-2xl font-bold tracking-tight text-white border-b border-gray-700 pb-3 mb-5" style="font-family: 'Newsreader', serif;">
                        {{ $shelfTitle }} ({{ $shelves[$shelfKey]->count() }})
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-x-4 gap-y-6">
                        @foreach($shelves[$shelfKey] as $book)
                            <div class="group">
                                <a href="{{ route('books.show', $book->id) }}">
                                    <!-- Book Cover -->
                                    <div class="mb-3 aspect-[2/3] w-full rounded-md bg-cover bg-center bg-no-repeat shadow-md border border-gray-700 transition-transform duration-300 group-hover:scale-105 group-hover:shadow-xl" style='background-image: url("{{ $book->image }}");'></div>
                                    <!-- Book Title -->
                                    <h3 class="font-semibold text-gray-100 group-hover:text-[var(--primary-color)] text-sm truncate transition">
                                        {{ $book->title }}
                                    </h3>
                                    <!-- Author -->
                                    <p class="text-xs text-gray-400">
                                        {{ $book->author->name }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if(!$hasBooks)
            <!-- Empty Shelf Message -->
            <div class="text-center py-12 border-2 border-dashed border-gray-700 rounded-xl bg-gray-800/40">
                <p class="text-gray-400">You haven't added any books to your shelves yet.</p>
                <a href="{{ route('books.index') }}"
                   class="mt-4 inline-block rounded-md bg-[var(--primary-color)] px-4 py-2 text-sm font-bold text-white shadow-sm transition-transform hover:scale-105 hover:shadow-[0_0_15px_var(--primary-color)]">
                    Browse & Add Books
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
