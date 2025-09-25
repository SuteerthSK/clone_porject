@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-10 py-8 lg:px-20 xl:px-40">
    <div class="mx-auto max-w-5xl">
        {{-- Book Details Section (Unchanged) --}}
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
            <div class="md:col-span-1">
                <div class="aspect-[2/3] w-full max-w-xs mx-auto rounded-lg bg-cover bg-center bg-no-repeat shadow-lg" style='background-image: url("{{ $book->image ?? '' }}");'></div>
            </div>
            <div class="md:col-span-2">
                <h2 class="text-3xl font-bold text-stone-900" style="font-family: 'Newsreader', serif;">{{ $book->title ?? 'Untitled' }}</h2>
                <p class="mt-1 text-lg text-stone-600">by <a class="font-medium text-stone-700 hover:text-stone-900" href="#">{{ $book->author->name ?? 'Unknown' }}</a></p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button id="want-to-read-btn" class="flex min-w-[160px] items-center justify-center gap-2 rounded-md bg-[var(--primary-color)] px-4 py-2 text-sm font-bold text-white shadow-sm transition-transform hover:scale-105"></button>
                    <button id="mark-as-read-btn" class="flex min-w-[160px] items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition-transform hover:scale-105"></button>
                </div>
                <div class="py-6 mt-4">
                    <h3 class="text-xl font-bold text-stone-900 mb-2" style="font-family: 'Newsreader', serif;">About the Book</h3>
                    <p class="text-stone-700 leading-relaxed">{{ $book->description ?? 'No description available.' }}</p>
                </div>
            </div>
        </div>

        {{-- START: DYNAMIC REVIEWS AND RATINGS SECTION --}}
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-stone-900 mb-6" style="font-family: 'Newsreader', serif;">Ratings & Reviews</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Left Side: Stats (Populated by JS) --}}
                <div id="review-stats-container" class="md:col-span-1">
                    <p id="stats-placeholder">Loading stats...</p>
                </div>

                {{-- Right Side: Form and List (Populated by JS) --}}
                <div class="md:col-span-2 space-y-8">
                    {{-- Review Form --}}
                    <div>
                        <h4 class="text-lg font-bold text-stone-800 mb-3">Write a Review</h4>
                        <form id="review-form" class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-stone-700 mb-1">Your Rating</label>
                                <div id="star-rating" class="flex items-center gap-1 text-3xl text-stone-300 cursor-pointer">
                                    <span class="star" data-value="1">★</span> <span class="star" data-value="2">★</span> <span class="star" data-value="3">★</span> <span class="star" data-value="4">★</span> <span class="star" data-value="5">★</span>
                                </div>
                                <input type="hidden" name="rating" id="rating-value">
                                <p id="rating-error" class="text-red-500 text-xs mt-1 hidden">Please select a rating.</p>
                            </div>
                            <div>
                                <label for="body" class="block text-sm font-medium text-stone-700 mb-1">Your Review</label>
                                <textarea id="body" name="body" rows="4" class="w-full rounded-md border-stone-300 focus:border-green-500 focus:ring-green-500" placeholder="What did you think?"></textarea>
                                <p id="body-error" class="text-red-500 text-xs mt-1 hidden">Review must be at least 10 characters.</p>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="rounded-md bg-stone-800 px-4 py-2 text-sm font-bold text-white hover:bg-stone-700">Submit Review</button>
                            </div>
                        </form>
                    </div>
                    
                    {{-- Reviews List (Populated by JS) --}}
                    <div id="reviews-list" class="space-y-6">
                        <p id="reviews-placeholder">Loading reviews...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Final, corrected script --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const bookId = {{ $book->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' };

    // --- Shelf Button Logic (Unchanged) ---
    const wantToReadBtn=document.getElementById("want-to-read-btn"),markAsReadBtn=document.getElementById("mark-as-read-btn");let bookShelfState=@json($bookShelfState);function updateAllButtonStates(){let e=bookShelfState==="want_to_read";wantToReadBtn.innerHTML=e?`<span class="material-symbols-outlined">bookmark_added</span><span>On Your Shelf</span>`:`<span class="material-symbols-outlined">bookmark_add</span><span>Want to Read</span>`,wantToReadBtn.classList.toggle("bg-stone-600",e),wantToReadBtn.classList.toggle("bg-[var(--primary-color)]",!e);let t=bookShelfState==="read";markAsReadBtn.innerHTML=t?`<span class="material-symbols-outlined">check_circle</span><span>Read</span>`:`<span class="material-symbols-outlined">done_all</span><span>Mark as Read</span>`,markAsReadBtn.classList.toggle("bg-gray-500",t),markAsReadBtn.classList.toggle("bg-blue-600",!t),wantToReadBtn.disabled=t,wantToReadBtn.classList.toggle("cursor-not-allowed",t),wantToReadBtn.classList.toggle("opacity-60",t)}async function updateShelf(e){try{let t=await fetch(`{{route("shelves.add",$book)}}`,{method:"POST",headers:headers,body:JSON.stringify({shelf:e})});if(!t.ok)throw new Error("Request failed");let o=await t.json();bookShelfState=o.shelf,updateAllButtonStates()}catch(e){console.error("Failed to update shelf:",e)}}async function removeShelf(){try{let e=await fetch(`{{route("shelves.remove",$book)}}`,{method:"DELETE",headers:headers});if(!e.ok)throw new Error("Request failed");let t=await e.json();bookShelfState=t.shelf,updateAllButtonStates()}catch(e){console.error("Failed to remove from shelf:",e)}}async function toggleReadStatus(){let e=bookShelfState==="read"?`{{route("shelves.unmark_as_read",$book)}}`:`{{route("shelves.mark_as_read",$book)}}`;try{let t=await fetch(e,{method:"POST",headers:headers});if(!t.ok)throw new Error("Request failed");let o=await t.json();bookShelfState=o.shelf,updateAllButtonStates()}catch(e){console.error("Failed to toggle read status:",e)}}wantToReadBtn&&wantToReadBtn.addEventListener("click",()=>{bookShelfState==="want_to_read"?removeShelf():updateShelf("want_to_read")}),markAsReadBtn&&markAsReadBtn.addEventListener("click",toggleReadStatus),updateAllButtonStates();


    // --- NEW, CORRECTED REVIEWS SCRIPT ---
    const statsContainer = document.getElementById('review-stats-container');
    const reviewsList = document.getElementById('reviews-list');
    const reviewForm = document.getElementById('review-form');

    function renderStats(stats){if(!stats||0===stats.total_reviews){statsContainer.innerHTML="<p>No ratings yet.</p>";return}let e="";for(let t=5;t>=1;t--){let o=stats.breakdown[t];e+=`\n                <div class="flex items-center gap-2 text-sm">\n                    <span class="text-stone-600">${t} star</span>\n                    <div class="flex-1 rounded-full bg-stone-200 h-2">\n                        <div class="bg-yellow-500 h-2 rounded-full" style="width: ${o.percentage}%;"></div>\n                    </div>\n                    <span class="text-stone-500 w-8 text-right">${Math.round(o.percentage)}%</span>\n                </div>\n            `}statsContainer.innerHTML=`\n            <div class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">\n                <div class="flex items-baseline gap-2">\n                    <h2 class="text-4xl font-bold text-stone-800">${stats.average_rating}</h2>\n                    <span class="text-stone-500">out of 5</span>\n                </div>\n                <p class="text-sm text-stone-600 mt-1">${stats.total_reviews} ratings</p>\n                <div class="mt-4 space-y-2">${e}</div>\n            </div>\n        `}
    function renderReviews(reviews){if(!reviews||0===reviews.length){reviewsList.innerHTML='<p class="text-stone-500">No reviews yet. Be the first to write one!</p>';return}let e="";reviews.forEach(t=>{let o="";for(let e=1;e<=5;e++)o+=`<span class="material-symbols-outlined text-sm ${e<=t.rating?"text-yellow-500 is-favorited":"text-stone-300"}">star</span>`;e+=`\n                <div class="flex items-start gap-4">\n                    <div class="h-12 w-12 flex-shrink-0 rounded-full bg-stone-200 text-stone-600 flex items-center justify-center font-bold text-xl" title="${t.user.name}">\n                        ${t.user.name.charAt(0).toUpperCase()}\n                    </div>\n                    <div class="flex-1 rounded-lg border border-stone-200 bg-white p-4 shadow-sm min-w-0">\n                        <div class="flex items-baseline justify-between mb-1">\n                            <p class="font-semibold text-stone-800">${t.user.name}</p>\n                            <div class="flex items-center gap-0.5">${o}</div>\n                        </div>\n                        <p class="text-xs text-stone-500 mb-3">${new Date(t.created_at).toLocaleDateString("en-US",{year:"numeric",month:"long",day:"numeric"})}</p>\n                        <p class="text-stone-700 leading-relaxed break-words">${t.body}</p>\n                    </div>\n                </div>\n            `}),reviewsList.innerHTML=e}

    async function fetchAndRenderReviews() {
        try {
            // This now points to the web route, not the API route
            const response = await fetch(`{{ route('reviews.index', $book) }}`, { headers: headers });
            if (!response.ok) throw new Error('Failed to fetch reviews');
            const data = await response.json();
            renderStats(data.stats);
            renderReviews(data.reviews);
        } catch (error) {
            console.error(error);
            statsContainer.innerHTML = '<p class="text-red-500">Could not load stats.</p>';
            reviewsList.innerHTML = '<p class="text-red-500">Could not load reviews.</p>';
        }
    }

    const stars = document.querySelectorAll('#star-rating .star');
    const ratingValueInput = document.getElementById('rating-value');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            ratingValueInput.value = star.dataset.value;
            stars.forEach(s => s.classList.toggle('text-yellow-400', s.dataset.value <= ratingValueInput.value));
        });
    });

    reviewForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const rating = ratingValueInput.value;
        const body = reviewForm.querySelector('#body').value;
        const submitButton = reviewForm.querySelector('button[type="submit"]');

        document.getElementById('rating-error').classList.add('hidden');
        document.getElementById('body-error').classList.add('hidden');
        if(!rating){document.getElementById('rating-error').classList.remove('hidden');return}
        if(body.length<10){document.getElementById('body-error').classList.remove('hidden');return}

        submitButton.disabled = true;
        submitButton.textContent = 'Submitting...';

        try {
            // This fetch call now sends the CSRF token automatically via the `headers` constant
            // No more Authorization Bearer token is needed!
            const response = await fetch(`{{ route('reviews.store', $book) }}`, {
                method: 'POST',
                headers: headers, // Uses the headers with CSRF token
                body: JSON.stringify({ rating, body })
            });

            if (response.status === 422) {
                const result = await response.json();
                alert(`Validation failed: ${Object.values(result.errors).join(', ')}`);
            } else if (!response.ok) {
                throw new Error('Failed to submit review');
            } else {
                reviewForm.reset();
                stars.forEach(s => s.classList.remove('text-yellow-400'));
                await fetchAndRenderReviews(); // Success! Refresh the list.
            }
        } catch (error) {
            console.error(error);
            alert('An error occurred. Please try again.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit Review';
        }
    });

    // Initial load of all review data
    fetchAndRenderReviews();
});
</script>
@endsection