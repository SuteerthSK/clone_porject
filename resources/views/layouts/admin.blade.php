<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>BookVerse Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        :root { --primary-color: #17cf17; }
        body { font-family: 'Public Sans', sans-serif; }

        /* Dark theme base */
        body {
            background-color: #0f0f10;
            color: #e5e7eb;
        }

        /* Sidebar styling */
        aside {
            background-color: #1a1b1e;
            border-right: 1px solid rgba(255,255,255,0.08);
        }

        /* Active nav item */
        .nav-active {
            background-color: rgba(23,207,23,0.15);
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Hover nav item */
        .nav-link {
            color: #d1d5db;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: rgba(255,255,255,0.05);
            color: #fff;
        }

        /* Header */
        header {
            background-color: rgba(26,27,30,0.7);
            backdrop-filter: blur(8px);
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
        .btn-signout {
            background-color: #27282b;
            color: #e5e7eb;
            font-weight: 600;
        }
        .btn-signout:hover {
            background-color: #3a3b3f;
            color: #fff;
        }
    </style>
</head>
<body class="text-gray-200">
<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 p-6">
        <h1 class="text-2xl font-bold text-white mb-8 tracking-wide">Admin Panel</h1>
        <nav class="flex flex-col gap-2">
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.books.*') ? 'nav-active' : 'nav-link' }}"
               href="{{ route('admin.books.index') }}">
                <span>📚 Books</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-2 rounded-lg
               {{ request()->routeIs('admin.authors.*') ? 'nav-active' : 'nav-link' }}"
               href="{{ route('admin.authors.index') }}">
                <span>✍️ Authors</span>
            </a>
        </nav>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 p-8">
        {{-- Dynamic Header --}}
        <div class="flex items-center justify-between mb-8 border-b border-gray-700 pb-4">
            <h2 class="text-4xl font-bold text-white">
                @yield('header-title')
            </h2>
            <div class="flex items-center gap-4">
                {{-- Page-specific buttons --}}
                @yield('header-actions')

                {{-- Sign Out --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-signout px-6 py-3 rounded-lg transition-colors">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="bg-[#1a1b1e] rounded-xl p-6 shadow-md border border-gray-800">
            @yield('content')
        </div>
    </main>
</div>
</body>
</html>
