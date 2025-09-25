@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-10 py-8 lg:px-20 xl:px-40">
    <div class="mx-auto max-w-lg">
        <h1 class="text-3xl font-bold text-stone-900 mb-6" style="font-family: 'Newsreader', serif;">
            Set Your Reading Goal
        </h1>
        
        <div class="rounded-lg border border-stone-200 bg-white p-6 shadow-sm">
            <form action="{{ route('challenges.update') }}" method="POST">
                @csrf
                
                {{-- This hidden input sends the year we are editing --}}
                <input type="hidden" name="year" value="{{ $challenge->year }}">

                <div>
                    <label for="goal_count" class="block text-sm font-medium text-stone-700">
                        How many books do you want to read in {{ $challenge->year }}?
                    </label>
                    <div class="mt-1">
                        <input type="number" 
                               name="goal_count" 
                               id="goal_count" 
                               class="block w-full rounded-md border-stone-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" 
                               value="{{ $challenge->goal_count }}"
                               min="1"
                               required>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="rounded-md bg-stone-800 px-4 py-2 text-sm font-bold text-white hover:bg-stone-700">
                        Save Goal
                    </button>
                    <a href="{{ route('books.index') }}" class="ml-2 text-sm text-stone-600 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection