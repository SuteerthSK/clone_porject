{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.bookverse')

@section('content')
<div class="flex flex-1 items-center justify-center py-12 sm:py-16 md:py-24">
    <div class="mx-auto w-full max-w-md px-4 sm:px-6 lg:px-8">
        <div class="space-y-6 rounded-lg bg-white p-8 shadow-lg">
            <div class="text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl" style="font-family: 'Newsreader', serif;">Create your BookVerse account</h1>
                <p class="mt-2 text-sm text-gray-600">Discover and share books you love.</p>
            </div>

            <form method="POST" id="register-form" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="sr-only" for="name">Full Name</label>
                    <input class="form-input h-12 w-full rounded-md border-gray-300 px-4 placeholder-gray-500" id="name" name="name" placeholder="Full Name" required />
                </div>
                <div>
                    <label class="sr-only" for="email">Email address</label>
                    <input autocomplete="email" class="form-input h-12 w-full rounded-md border-gray-300 px-4 placeholder-gray-500" id="email" name="email" placeholder="Email address" required type="email"/>
                </div>
                <div>
                    <label class="sr-only" for="password">Password</label>
                    <input autocomplete="new-password" class="form-input h-12 w-full rounded-md border-gray-300 px-4 placeholder-gray-500" id="password" name="password" placeholder="Password (min 6 characters)" required type="password" minlength="6"/>
                </div>

                <div>
                    <label for="role" class="sr-only">Account Type</label>
                    <select id="role" name="role" class="form-input h-12 w-full rounded-md border-gray-300 px-4 text-gray-500">
                        <option value="user" selected>I am a User</option>
                        <option value="admin">I am an Admin</option>
                    </select>
                </div>

                <div>
                    <button class="flex w-full justify-center rounded-md border border-transparent bg-[var(--primary-color)] py-3 px-4 text-base font-bold text-white shadow-sm hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]" type="submit">
                        Sign Up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
