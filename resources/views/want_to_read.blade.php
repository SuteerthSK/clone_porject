@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-10 py-8 lg:px-20 xl:px-40">
    <div class="mx-auto max-w-7xl">
        <h1 class="text-3xl font-bold tracking-tight text-stone-900 mb-6" style="font-family: 'Newsreader', serif;">
            Want to Read Shelf 📚
        </h1>

        @if($books->isNotEmpty())
            <div id="wantToReadGrid" class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
                @foreach ($books as $book)
                    <div class="group relative book-card">
                        <div class="relative">
                            <a href="{{ route('books.show', $book->id) }}">
                                <div class="aspect-[2/3] w-full overflow-hidden rounded-lg bg-stone-200">
                                    <img src="{{ $book->image }}" alt="Cover of {{ $book->title }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
                                </div>
                            </a>
                            {{-- REMOVE BUTTON --}}
                            <button data-book-id="{{ $book->id }}" class="remove-btn absolute top-2 right-2 flex h-8 w-8 items-center justify-center rounded-full bg-black/40 text-white backdrop-blur-sm transition-colors hover:bg-black/60" title="Remove from shelf">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                        <a href="{{ route('books.show', $book->id) }}">
                            <h3 class="mt-3 text-base font-bold text-stone-800 truncate">{{ $book->title }}</h3>
                            <p class="text-sm text-stone-600">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 border-2 border-dashed border-stone-300 rounded-lg">
                <p class="text-stone-500">Your "Want to Read" shelf is empty.</p>
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