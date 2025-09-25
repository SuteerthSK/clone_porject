@extends('layouts.bookverse')

@section('content')
<div class="relative text-center bg-stone-100 py-24 px-4 sm:px-6 lg:px-8">
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=3000&auto=format&fit=crop" alt="Library background">
        <div class="absolute inset-0 bg-white/60 backdrop-blur-sm"></div>
    </div>
    <div class="relative max-w-2xl mx-auto">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl" style="font-family: 'Newsreader', serif;">
            Discover & Read More
        </h1>
        <p class="mt-6 text-xl text-gray-700">
            Tell us what books you love, and we’ll help you discover new favorites. Connect with friends and see what they’re reading on BookVerse.
        </p>
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('auth.register.view') }}" class="inline-block rounded-md bg-[var(--primary-color)] px-6 py-3 text-base font-bold text-white shadow-lg transition-transform hover:scale-105">
                Get Started
            </a>
            <a href="{{ route('auth.login.view') }}" class="inline-block rounded-md bg-white px-6 py-3 text-base font-bold text-gray-800 shadow-lg transition-transform hover:scale-105">
                Sign In
            </a>
        </div>
    </div>
</div>
@endsection