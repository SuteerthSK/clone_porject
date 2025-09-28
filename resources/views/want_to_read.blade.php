@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-6 py-8 lg:px-16 xl:px-32 bg-gray-900 text-gray-100 min-h-screen">
    <div class="mx-auto max-w-7xl">
        <!-- Page Title -->
        <h1 class="text-3xl font-extrabold tracking-tight mb-6 text-white" style="font-family: 'Newsreader', serif;">
            Want to Read Shelf 📚
        </h1>

        @if($books->isNotEmpty())
            <!-- Books Grid -->
            <div id="wantToReadGrid" class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                @foreach ($books as $book)
                    <div class="group relative book-card bg-gray-800/50 rounded-xl p-3 shadow-lg hover:shadow-xl transition duration-300 border border-gray-700">
                        <div class="relative">
                            <a href="{{ route('books.show', $book->id) }}">
                                <div class="aspect-[2/3] w-full overflow-hidden rounded-lg bg-gray-700">
                                    <img src="{{ $book->image }}" alt="Cover of {{ $book->title }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                                </div>
                            </a>
                            {{-- REMOVE BUTTON --}}
                            <button data-book-id="{{ $book->id }}" class="remove-btn absolute top-2 right-2 flex h-8 w-8 items-center justify-center rounded-full bg-black/50 text-white backdrop-blur-sm transition hover:bg-red-600 hover:text-white" title="Remove from shelf">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <a href="{{ route('books.show', $book->id) }}">
                            <h3 class="mt-3 text-base font-bold text-gray-100 truncate group-hover:text-[var(--primary-color)] transition">{{ $book->title }}</h3>
                            <p class="text-sm text-gray-400">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty Shelf Message -->
            <div class="text-center py-12 border-2 border-dashed border-gray-700 rounded-lg bg-gray-800/40">
                <p class="text-gray-400">Your "Want to Read" shelf is empty.</p>
            </div>
        @endif
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const grid = document.getElementById('wantToReadGrid');
    if (!grid) return;

    grid.addEventListener('click', function(event) {
        const button = event.target.closest('.remove-btn');
        if (!button) return;

        const bookId = button.dataset.bookId;
        const card = button.closest('.book-card');

        if (!confirm('Are you sure you want to remove this book from your shelf?')) {
            return;
        }

        fetch(`{{ url('/books') }}/${bookId}/shelves`, { // Using url() helper for safety
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                // Fade out and remove the card for instant feedback
                card.style.transition = 'opacity 0.3s ease';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            } else {
                alert('Failed to remove the book. Please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
@endsection
