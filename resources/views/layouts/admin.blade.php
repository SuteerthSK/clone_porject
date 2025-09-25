<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>BookVerse Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        :root { --primary-color: #17cf17; }
        body { font-family: 'Public Sans', sans-serif; }
    </style>
</head>
<body class="bg-stone-50">
<div class="flex min-h-screen">
    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white p-6 border-r border-stone-200">
        <h1 class="text-2xl font-bold text-black mb-8">Admin Panel</h1>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors 
               {{ request()->routeIs('admin.books.*') ? 'font-bold bg-green-100 text-green-800' : 'text-black hover:bg-stone-100' }}" 
               href="{{ route('admin.books.index') }}">
                <span>Books</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors
               {{ request()->routeIs('admin.authors.*') ? 'font-bold bg-green-100 text-green-800' : 'text-black hover:bg-stone-100' }}" 
               href="{{ route('admin.authors.index') }}">
                <span>Authors</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8">
        {{-- 👇 NEW DYNAMIC HEADER 👇 --}}
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-4xl font-bold text-black">
                @yield('header-title')
            </h2>
            
            <div class="flex items-center gap-4">
                {{-- This is where page-specific buttons like "Add Book" will go --}}
                @yield('header-actions')

                {{-- This Sign Out button will now appear on every page --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-stone-700 text-white px-6 py-3 rounded-lg font-bold hover:bg-stone-800 transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

        @yield('content')
    </main>
</div>
</body>
</html>