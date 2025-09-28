@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-6 py-8 lg:px-20 xl:px-40 bg-gray-900 text-gray-100 min-h-screen">
    <div class="mx-auto max-w-5xl">
        {{-- 📚 Book Details Section --}}
        <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
            {{-- Book Cover --}}
            <div class="md:col-span-1">
                <div class="aspect-[2/3] w-full max-w-xs mx-auto rounded-xl bg-cover bg-center bg-no-repeat shadow-2xl border border-gray-700" 
                     style='background-image: url("{{ $book->image ?? '' }}");'>
                </div>
            </div>

            {{-- Book Info --}}
            <div class="md:col-span-2">
                <h2 class="text-4xl font-extrabold text-white tracking-tight" style="font-family: 'Newsreader', serif;">
                    {{ $book->title ?? 'Untitled' }}
                </h2>
                <p class="mt-2 text-lg text-gray-400">
                    by 
                    <a class="font-medium text-green-400 hover:text-green-300 transition-colors" href="#">
                        {{ $book->author->name ?? 'Unknown' }}
                    </a>
                </p>

                {{-- Shelf Buttons --}}
                <div class="mt-6 flex flex-wrap gap-4">
                    <button id="want-to-read-btn" 
                        class="flex min-w-[160px] items-center justify-center gap-2 rounded-lg 
                               bg-green-600 px-5 py-2 text-sm font-semibold text-white 
                               shadow-md hover:scale-105 hover:bg-green-500 transition-transform duration-200"></button>

                    <button id="mark-as-read-btn" 
                        class="flex min-w-[160px] items-center justify-center gap-2 rounded-lg 
                               bg-blue-600 px-5 py-2 text-sm font-semibold text-white 
                               shadow-md hover:scale-105 hover:bg-blue-500 transition-transform duration-200"></button>
                </div>

                {{-- Description --}}
                <div class="py-6 mt-6 border-t border-gray-700">
                    <h3 class="text-2xl font-bold text-white mb-3" style="font-family: 'Newsreader', serif;">
                        About the Book
                    </h3>
                    <p class="text-gray-300 leading-relaxed">
                        {{ $book->description ?? 'No description available.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ⭐ Ratings & Reviews Section --}}
        <div class="mt-16">
            <h3 class="text-3xl font-bold text-white mb-8" style="font-family: 'Newsreader', serif;">
                Ratings & Reviews
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Stats Panel --}}
                <div id="review-stats-container" class="md:col-span-1">
                    <p id="stats-placeholder" class="text-gray-400">Loading stats...</p>
                </div>

                {{-- Review Form + Reviews List --}}
                <div class="md:col-span-2 space-y-10">
                    {{-- Review Form --}}
                    <div>
                        <h4 class="text-xl font-semibold text-gray-100 mb-4">Write a Review</h4>
                        <form id="review-form" 
                              class="rounded-xl border border-gray-700 bg-gray-800 p-6 shadow-md">
                            {{-- Rating Stars --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Your Rating</label>
                                <div id="star-rating" class="flex items-center gap-1 text-3xl text-gray-500 cursor-pointer">
                                    <span class="star" data-value="1">★</span>
                                    <span class="star" data-value="2">★</span>
                                    <span class="star" data-value="3">★</span>
                                    <span class="star" data-value="4">★</span>
                                    <span class="star" data-value="5">★</span>
                                </div>
                                <input type="hidden" name="rating" id="rating-value">
                                <p id="rating-error" class="text-red-400 text-xs mt-1 hidden">
                                    Please select a rating.
                                </p>
                            </div>

                            {{-- Review Text --}}
                            <div>
                                <label for="body" class="block text-sm font-medium text-gray-300 mb-2">Your Review</label>
                                <textarea id="body" name="body" rows="4"
                                    class="w-full rounded-lg border border-gray-600 bg-gray-900 text-gray-100
                                           focus:border-green-500 focus:ring-green-500 p-3"
                                    placeholder="What did you think?"></textarea>
                                <p id="body-error" class="text-red-400 text-xs mt-1 hidden">
                                    Review must be at least 10 characters.
                                </p>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="rounded-lg bg-green-600 px-5 py-2 text-sm font-bold text-white
                                           hover:bg-green-500 transition-colors shadow-md">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Reviews List --}}
                    <div id="reviews-list" class="space-y-6">
                        <p id="reviews-placeholder" class="text-gray-400">Loading reviews...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- ⚡ Script (Functionality Unchanged) --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const bookId = {{ $book->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' };

    // --- Shelf Button Logic (Unchanged) ---
    const wantToReadBtn=document.getElementById("want-to-read-btn"),
          markAsReadBtn=document.getElementById("mark-as-read-btn");
    let bookShelfState=@json($bookShelfState);
    function updateAllButtonStates(){
        let e=bookShelfState==="want_to_read";
        wantToReadBtn.innerHTML=e?
        `<span class="material-symbols-outlined">bookmark_added</span><span>On Your Shelf</span>`:
        `<span class="material-symbols-outlined">bookmark_add</span><span>Want to Read</span>`;
        wantToReadBtn.classList.toggle("bg-gray-700",e);
        wantToReadBtn.classList.toggle("bg-green-600",!e);
        let t=bookShelfState==="read";
        markAsReadBtn.innerHTML=t?
        `<span class="material-symbols-outlined">check_circle</span><span>Read</span>`:
        `<span class="material-symbols-outlined">done_all</span><span>Mark as Read</span>`;
        markAsReadBtn.classList.toggle("bg-gray-700",t);
        markAsReadBtn.classList.toggle("bg-blue-600",!t);
        wantToReadBtn.disabled=t;
        wantToReadBtn.classList.toggle("cursor-not-allowed",t);
        wantToReadBtn.classList.toggle("opacity-60",t)
    }
    async function updateShelf(e){try{
        let t=await fetch(`{{route("shelves.add",$book)}}`,{method:"POST",headers:headers,body:JSON.stringify({shelf:e})});
        if(!t.ok)throw new Error("Request failed");
        let o=await t.json();bookShelfState=o.shelf,updateAllButtonStates()
    }catch(e){console.error("Failed to update shelf:",e)}}
    async function removeShelf(){try{
        let e=await fetch(`{{route("shelves.remove",$book)}}`,{method:"DELETE",headers:headers});
        if(!e.ok)throw new Error("Request failed");
        let t=await e.json();bookShelfState=t.shelf,updateAllButtonStates()
    }catch(e){console.error("Failed to remove from shelf:",e)}}
    async function toggleReadStatus(){
        let e=bookShelfState==="read"?`{{route("shelves.unmark_as_read",$book)}}`:`{{route("shelves.mark_as_read",$book)}}`;
        try{
            let t=await fetch(e,{method:"POST",headers:headers});
            if(!t.ok)throw new Error("Request failed");
            let o=await t.json();bookShelfState=o.shelf,updateAllButtonStates()
        }catch(e){console.error("Failed to toggle read status:",e)}
    }
    wantToReadBtn&&wantToReadBtn.addEventListener("click",()=>{bookShelfState==="want_to_read"?removeShelf():updateShelf("want_to_read")});
    markAsReadBtn&&markAsReadBtn.addEventListener("click",toggleReadStatus);
    updateAllButtonStates();

    // --- Reviews Script ---
    const statsContainer=document.getElementById('review-stats-container'),
          reviewsList=document.getElementById('reviews-list'),
          reviewForm=document.getElementById('review-form');

    function renderStats(stats){
        if(!stats||0===stats.total_reviews){statsContainer.innerHTML="<p class='text-gray-400'>No ratings yet.</p>";return}
        let e="";for(let t=5;t>=1;t--){let o=stats.breakdown[t];
        e+=`<div class="flex items-center gap-2 text-sm text-gray-300">
                <span>${t} star</span>
                <div class="flex-1 rounded-full bg-gray-700 h-2">
                    <div class="bg-yellow-500 h-2 rounded-full" style="width:${o.percentage}%;"></div>
                </div>
                <span class="w-8 text-right">${Math.round(o.percentage)}%</span>
            </div>`}
        statsContainer.innerHTML=`
            <div class="rounded-xl border border-gray-700 bg-gray-800 p-6 shadow-md">
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold text-white">${stats.average_rating}</h2>
                    <span class="text-gray-400">out of 5</span>
                </div>
                <p class="text-sm text-gray-400 mt-1">${stats.total_reviews} ratings</p>
                <div class="mt-4 space-y-2">${e}</div>
            </div>`
    }

    function renderReviews(reviews){
        if(!reviews||0===reviews.length){
            reviewsList.innerHTML='<p class="text-gray-400">No reviews yet. Be the first to write one!</p>';
            return
        }
        let e="";reviews.forEach(t=>{
            let o="";for(let e=1;e<=5;e++)
            o+=`<span class="material-symbols-outlined text-sm ${e<=t.rating?"text-yellow-400":"text-gray-600"}">star</span>`;
            e+=`<div class="flex items-start gap-4">
                    <div class="h-12 w-12 flex-shrink-0 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center font-bold text-xl" title="${t.user.name}">
                        ${t.user.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 rounded-lg border border-gray-700 bg-gray-800 p-4 shadow-sm min-w-0">
                        <div class="flex items-baseline justify-between mb-1">
                            <p class="font-semibold text-white">${t.user.name}</p>
                            <div class="flex items-center gap-0.5">${o}</div>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">${new Date(t.created_at).toLocaleDateString("en-US",{year:"numeric",month:"long",day:"numeric"})}</p>
                        <p class="text-gray-200 leading-relaxed break-words">${t.body}</p>
                    </div>
                </div>`
        });
        reviewsList.innerHTML=e
    }

    async function fetchAndRenderReviews(){
        try{
            const response=await fetch(`{{ route('reviews.index', $book) }}`,{headers:headers});
            if(!response.ok)throw new Error('Failed to fetch reviews');
            const data=await response.json();
            renderStats(data.stats);
            renderReviews(data.reviews);
        }catch(error){
            console.error(error);
            statsContainer.innerHTML='<p class="text-red-400">Could not load stats.</p>';
            reviewsList.innerHTML='<p class="text-red-400">Could not load reviews.</p>';
        }
    }

    const stars=document.querySelectorAll('#star-rating .star');
    const ratingValueInput=document.getElementById('rating-value');
    stars.forEach(star=>{
        star.addEventListener('click',()=>{
            ratingValueInput.value=star.dataset.value;
            stars.forEach(s=>s.classList.toggle('text-yellow-400',s.dataset.value<=ratingValueInput.value));
        });
    });

    reviewForm.addEventListener('submit',async e=>{
        e.preventDefault();
        const rating=ratingValueInput.value;
        const body=reviewForm.querySelector('#body').value;
        const submitButton=reviewForm.querySelector('button[type="submit"]');
        document.getElementById('rating-error').classList.add('hidden');
        document.getElementById('body-error').classList.add('hidden');
        if(!rating){document.getElementById('rating-error').classList.remove('hidden');return}
        if(body.length<10){document.getElementById('body-error').classList.remove('hidden');return}
        submitButton.disabled=true;submitButton.textContent='Submitting...';
        try{
            const response=await fetch(`{{ route('reviews.store', $book) }}`,{
                method:'POST',headers:headers,body:JSON.stringify({rating,body})
            });
            if(response.status===422){
                const result=await response.json();
                alert(`Validation failed: ${Object.values(result.errors).join(', ')}`);
            }else if(!response.ok){
                throw new Error('Failed to submit review');
            }else{
                reviewForm.reset();
                stars.forEach(s=>s.classList.remove('text-yellow-400'));
                await fetchAndRenderReviews();
            }
        }catch(error){
            console.error(error);
            alert('An error occurred. Please try again.');
        }finally{
            submitButton.disabled=false;
            submitButton.textContent='Submit Review';
        }
    });

    // Initial load
    fetchAndRenderReviews();
});
</script>
@endsection
