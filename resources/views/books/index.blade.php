@extends('layouts.bookverse')

@section('content')
<main class="flex-1">
    <div class="flex flex-1 justify-center gap-8 p-6 lg:p-8">
        {{-- START: LEFT SIDEBAR --}}
        <aside class="hidden lg:block w-80 flex-shrink-0 space-y-8">
            
            {{-- Reading Challenge Widget --}}
            <div class="space-y-4 rounded-lg border border-stone-200 bg-white p-6">
                {{-- 👇 THIS SECTION HAS BEEN UPDATED 👇 --}}
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-stone-900" style="font-family: 'Newsreader', serif;">
                        Reading Challenge
                    </h3>
                    @auth
                        <a href="{{ route('challenges.edit') }}" class="text-xs font-medium text-green-600 hover:underline">
                            Edit Goal
                        </a>
                    @endauth
                </div>

                @auth
                    @if(session('status'))
                        <div class="mb-2 text-sm font-medium text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($challenge)
                        @php
                            $percentage = $challenge->goal_count > 0 ? ($challenge->progress_count / $challenge->goal_count) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <p class="font-medium text-stone-800">{{ $challenge->year }} Challenge</p>
                                <p class="text-stone-500">{{ $challenge->progress_count }} / {{ $challenge->goal_count }} books</p>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-stone-200">
                                <div class="h-2 rounded-full bg-green-500" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-stone-500">You haven't started a reading challenge for {{ now()->year }} yet. Mark a book as "Read" to begin!</p>
                    @endif
                @else
                    <p class="text-sm text-stone-500"><a href="{{ route('auth.login.view') }}" class="underline">Log in</a> to start your reading challenge.</p>
                @endauth
            </div>
            
            {{-- You can add other sidebar widgets here in the future --}}

        </aside>
        {{-- END: LEFT SIDEBAR --}}

        {{-- MAIN CONTENT: BOOKS FEED --}}
        <div class="flex-1 max-w-4xl">
            <h2 class="text-2xl font-bold text-stone-900 mb-6" style="font-family: 'Newsreader', serif;">Books Feed</h2>

            <div class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 md:grid-cols-4">
                @forelse ($books as $book)
                    <div class="group relative">
                        <a href="{{ route('books.show', $book->id) }}">
                            <div class="aspect-[2/3] w-full overflow-hidden rounded-lg bg-stone-200">
                                <img src="{{ $book->image }}" alt="Cover of {{ $book->title }}" class="h-full w-full object-cover object-center book-cover">
                            </div>
                        </a>

                        <button data-book-id="{{ $book->id }}" class="favorite-btn absolute top-2 right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/70 text-stone-700 backdrop-blur-sm hover:bg-white">
                            <span class="material-symbols-outlined @if(in_array($book->id, $favoriteBookIds)) is-favorited @endif">
                                favorite
                            </span>
                        </button>
                        
                        <a href="{{ route('books.show', $book->id) }}">
                            <h3 class="mt-3 text-base font-bold text-stone-800 truncate">{{ $book->title }}</h3>
                            <p class="text-sm text-stone-600">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center text-stone-500">
                        <p>No books have been added yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
        {{-- END: MAIN CONTENT --}}
    </div>
</main>

{{-- SCRIPT (no changes needed) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    
    favoriteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const bookId = this.dataset.bookId;
            const icon = this.querySelector('.material-symbols-outlined');

            fetch(`/books/${bookId}/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) { throw new Error('Request failed'); }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    icon.classList.toggle('is-favorited', data.is_favorited);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endsection