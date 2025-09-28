@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-10 py-8 lg:px-20 xl:px-40 bg-gray-900 text-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl">
        <!-- Page Title -->
        <div class="flex items-center gap-3 mb-8">
            <h1 class="text-3xl font-extrabold text-white" style="font-family: 'Newsreader', serif;">
                Your Favourites
            </h1>
            <span class="material-symbols-outlined text-3xl text-red-500" style="font-variation-settings: 'FILL' 1;">favorite</span>
        </div>

        @if($books->count() > 0)
            <!-- Favourites Grid -->
            <div id="favouritesGrid" class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                @foreach ($books as $book)
                    <div class="group relative book-card bg-gray-800/50 rounded-xl p-3 shadow-md hover:shadow-xl transition duration-300 border border-gray-700">
                        <a href="{{ route('books.show', $book->id) }}">
                            <div class="aspect-[2/3] w-full overflow-hidden rounded-lg bg-gray-700">
                                <img src="{{ $book->image }}" alt="Cover of {{ $book->title }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                            </div>
                        </a>

                        {{-- Button to REMOVE from favorites --}}
                        <button data-book-id="{{ $book->id }}"
                                class="favorite-btn absolute top-3 right-3 flex h-9 w-9 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur-sm hover:bg-red-600 hover:text-white transition"
                                title="Remove from Favourites">
                            <span class="material-symbols-outlined text-red-500" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        </button>

                        <a href="{{ route('books.show', $book->id) }}">
                            <h3 class="mt-3 text-base font-bold text-gray-100 truncate group-hover:text-[var(--primary-color)] transition">
                                {{ $book->title }}
                            </h3>
                            <p class="text-sm text-gray-400">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Message -->
            <div id="no-favourites-message" class="text-center py-12 border-2 border-dashed border-gray-700 rounded-xl bg-gray-800/40">
                <p class="text-gray-400">No favourites yet. Go add some! ❤️</p>
            </div>
        @endif
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('favouritesGrid');
    if (!grid) return;

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
                        if (message) message.style.display = 'block';
                    }
                }, 300);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection
