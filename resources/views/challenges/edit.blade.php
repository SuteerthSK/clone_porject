@extends('layouts.bookverse')

@section('content')
<main class="flex-1 px-6 py-10 lg:px-20 xl:px-40 bg-[#0f0f10] text-gray-200 min-h-screen">
    <div class="mx-auto max-w-lg">
        <h1 class="text-3xl font-bold mb-6 text-white tracking-tight" style="font-family: 'Newsreader', serif;">
            📈 Set Your Reading Goal
        </h1>
        
        <div class="rounded-xl border border-gray-800 bg-[#1a1b1e] p-6 shadow-md">
            <form action="{{ route('challenges.update') }}" method="POST">
                @csrf
                
                {{-- This hidden input sends the year we are editing --}}
                <input type="hidden" name="year" value="{{ $challenge->year }}">

                <div>
                    <label for="goal_count" class="block text-sm font-medium text-gray-300 mb-1">
                        How many books do you want to read in <span class="font-semibold text-green-400">{{ $challenge->year }}</span>?
                    </label>
                    <div class="mt-2">
                        <input type="number" 
                               name="goal_count" 
                               id="goal_count" 
                               class="block w-full rounded-lg border border-gray-700 bg-[#2a2b2f] text-gray-100 placeholder-gray-400 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm transition-colors" 
                               value="{{ $challenge->goal_count }}"
                               min="1"
                               required>
                    </div>
                </div>

                <div class="mt-6 flex items-center">
                    <button type="submit" class="rounded-lg bg-green-600 px-5 py-2 text-sm font-bold text-white shadow hover:bg-green-700 transition-colors">
                        Save Goal
                    </button>
                    <a href="{{ route('books.index') }}" class="ml-4 text-sm text-gray-400 hover:text-gray-200 hover:underline transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
