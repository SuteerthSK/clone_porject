<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BookVerse') }}</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@400;500;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root { --primary-color: #17cf17; }
        body { font-family: 'Public Sans', sans-serif; }

        /* Dark Mode Base */
        body {
            background: #0f0f10;
            color: #e5e7eb;
        }

        /* Navbar glass effect */
        header {
            background: rgba(20,20,22,0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        /* Hover links */
        a {
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        /* Favourites heart icon when active */
        .is-favorited {
            font-variation-settings: 'FILL' 1 !important;
            color: #ef4444 !important;
        }

        /* Book Cover hover */
        .book-cover {
            box-shadow: 0 10px 20px -3px rgba(0,0,0,0.4), 0 4px 6px -4px rgba(0,0,0,0.4);
            transition: all 0.3s ease-in-out;
        }
        .book-cover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -5px rgba(0,0,0,0.5), 0 8px 10px -6px rgba(0,0,0,0.5);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            color: #fff;
            font-weight: 700;
        }
        .btn-primary:hover {
            background-color: #13a413;
        }
    </style>
</head>
<body class="text-gray-200">

<div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
<header class="sticky top-0 z-10 w-full">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center gap-8">
                <a class="flex flex-shrink-0 items-center gap-2 text-xl font-bold text-white hover:text-[var(--primary-color)]" 
                   href="{{ auth()->check() ? route('books.index') : route('home') }}">
                    <svg class="h-8 w-8 text-[var(--primary-color)]" fill="currentColor" viewBox="0 0 48 48">
                        <path d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z"></path>
                    </svg>
                    <span class="hidden sm:inline font-serif" style="font-family: 'Newsreader', serif;">BookVerse</span>
                </a>
            </div>

            <!-- Nav Links -->
            <div class="flex items-center gap-6">
                @auth
                    <nav class="hidden items-center gap-6 md:flex">
                        <a class="text-sm font-medium text-gray-300 transition-colors hover:text-white" href="{{ route('my-books.view') }}">My Books</a>
                        <a class="text-sm font-medium text-gray-300 transition-colors hover:text-white" href="{{ route('want_to_read.view') }}">Want to Read</a>
                        <a class="text-sm font-medium text-gray-300 transition-colors hover:text-white" href="{{ route('favourites.view') }}">Favourites</a>
                    </nav>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-200">Hi, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-400 hover:text-white">Sign Out</button>
                        </form>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('auth.login.view') }}" class="text-sm font-medium text-gray-300 hover:text-white">Sign In</a>
                    <a href="{{ route('auth.register.view') }}" 
                       class="btn-primary flex items-center justify-center rounded-md px-4 py-2 text-sm shadow-sm transition-colors">
                        <span>Sign Up</span>
                    </a>
                @endguest
            </div>

        </div>
    </div>
</header>

    <!-- Main Content -->
    <main class="flex-1 py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>
</div>

</body>
</html>
