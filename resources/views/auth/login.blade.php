{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.bookverse')

@section('content')
<div class="flex flex-1 items-center justify-center py-12 sm:py-16 md:py-24 bg-gray-900">
    <div class="mx-auto w-full max-w-md px-4 sm:px-6 lg:px-8">
        <div class="space-y-6 rounded-xl bg-gray-800 p-8 shadow-xl border border-gray-700">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl" style="font-family: 'Newsreader', serif;">
                    Welcome back to BookVerse
                </h1>
                <p class="mt-2 text-sm text-gray-300">Sign in to continue.</p>
            </div>

            @if (session('success'))
                <div class="rounded-md bg-green-700/20 p-4 text-sm font-medium text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="sr-only" for="email">Email address</label>
                    <input 
                        autocomplete="email" 
                        class="form-input h-12 w-full rounded-md border-gray-600 bg-gray-700 px-4 placeholder-gray-400 text-gray-100 focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" 
                        id="email" 
                        name="email" 
                        placeholder="Email address" 
                        required 
                        type="email" 
                        value="{{ old('email') }}"
                    />
                    @error('email')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="sr-only" for="password">Password</label>
                    <input 
                        autocomplete="current-password" 
                        class="form-input h-12 w-full rounded-md border-gray-600 bg-gray-700 px-4 placeholder-gray-400 text-gray-100 focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" 
                        id="password" 
                        name="password" 
                        placeholder="Password" 
                        required 
                        type="password"
                    />
                </div>
                <div>
                    <button 
                        class="flex w-full justify-center rounded-md border border-transparent bg-[var(--primary-color)] py-3 px-4 text-base font-bold text-white shadow-sm hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]" 
                        type="submit"
                    >
                        Sign In
                    </button>
                </div>
            </form>
            
            <p class="text-center text-sm text-gray-300">
                Don't have an account? 
                <a href="{{ route('auth.register.view') }}" class="font-medium text-green-400 hover:underline">Sign up</a>
            </p>
        </div>
    </div>
</div>
@endsection
