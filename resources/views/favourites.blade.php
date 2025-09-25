@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-10 py-8 lg:px-20 xl:px-40">
    <div class="mx-auto max-w-7xl">
        <div class="flex items-center gap-3 mb-8">
            <h1 class="text-3xl font-bold text-stone-900" style="font-family: 'Newsreader', serif;">Your Favourites</h1>
            <span class="material-symbols-outlined text-3xl text-red-500" style="font-variation-settings: 'FILL' 1;">favorite</span>
        </div>

        @if($books->count() > 0)
            <div id="favouritesGrid" class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                @foreach ($books as $book)
                    <div class="group relative book-card">
                        <a href="{{ route('books.show', $book->id) }}">
                            <div class="aspect-[2/3] w-full overflow-hidden rounded-lg bg-stone-200">
                                <img src="{{ $book->image }}" alt="Cover of {{ $book->title }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                            </div>
                        </a>

                        {{-- Button to REMOVE from favorites --}}
                        <button data-book-id="{{ $book->id }}" class="favorite-btn absolute top-2 right-2 flex h-8 w-8 items-center justify-center rounded-full bg-white/70 text-stone-700 backdrop-blur-sm hover:bg-white" title="Remove from Favourites">
                            <span class="material-symbols-outlined text-red-500" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </button>
                        
                        <a href="{{ route('books.show', $book->id) }}">
                            <h3 class="mt-3 text-base font-bold text-stone-800 truncate">{{ $book->title }}</h3>
                            <p class="text-sm text-stone-600">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div id="no-favourites-message" class="text-center py-12 border-2 border-dashed border-stone-300 rounded-lg">
                <p class="text-stone-500">No favourites yet. Go add some! ❤️</p>
            </div>
        @endif
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('favouritesGrid');

    grid.addEventListener('click', function(event) {
        const button = event.target.closest('.favorite-btn');
        if (!button) return;

        const bookId = button.dataset.bookId;
        const card = button.closest('.book-card');

        fetch(`/books/${bookId}/toggle-favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && !data.is_favorited) {
                // Fade out and remove the card for instant feedback
                card.style.transition = 'opacity 0.3s ease';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    // Check if the grid is now empty
                    if (grid.children.length === 0) {
                        const message = document.getElementById('no-favourites-message');
                        if(message) message.style.display = 'block';
                    }
                }, 300);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection