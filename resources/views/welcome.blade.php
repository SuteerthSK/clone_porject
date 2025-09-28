@extends('layouts.bookverse')

@section('content')
<div class="relative text-center bg-gray-900 text-gray-100 py-24 px-4 sm:px-6 lg:px-8">
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" 
             src="https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=3000&auto=format&fit=crop" 
             alt="Library background">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    </div>

    <!-- Content -->
    <div class="relative max-w-2xl mx-auto">
        <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl md:text-6xl" 
            style="font-family: 'Newsreader', serif;">
            Discover & Read More
        </h1>
        <p class="mt-6 text-lg sm:text-xl text-gray-300 leading-relaxed">
            Tell us what books you love, and we’ll help you discover new favorites. 
            Connect with friends and see what they’re reading on <span class="text-[var(--primary-color)] font-semibold">BookVerse</span>.
        </p>

        <!-- Buttons -->
        <div class="mt-10 flex justify-center gap-4">
            <a href="{{ route('auth.register.view') }}"
               class="inline-block rounded-lg bg-[var(--primary-color)] px-7 py-3 text-base font-semibold text-white shadow-xl transition-all duration-200 hover:scale-105 hover:shadow-[0_0_20px_var(--primary-color)]">
                Get Started
            </a>
            <a href="{{ route('auth.login.view') }}"
               class="inline-block rounded-lg bg-gray-800/70 px-7 py-3 text-base font-semibold text-gray-200 shadow-md border border-gray-700 transition-all duration-200 hover:scale-105 hover:bg-gray-700">
                Sign In
            </a>
        </div>
    </div>
</div>
@endsection
