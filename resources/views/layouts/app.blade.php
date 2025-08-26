<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ config('app.name', 'GoodreadsClone') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-lg font-bold">GoodreadsClone</a>

            <!-- Guest menu (Register/Login) -->
            <div id="guest-menu" class="flex gap-4 items-center">
                <a href="{{ route('auth.register.view') }}" class="hover:underline">Register</a>
                <a href="{{ route('auth.login.view') }}" class="hover:underline">Login</a>
            </div>

            <!-- Authenticated menu -->
            <div id="auth-menu" class="hidden flex gap-4 items-center">
                <a href="{{ route('books.index') }}" class="hover:underline">Books</a>
                <span id="user-name" class="font-semibold"></span>

                <!-- Account dropdown -->
                <div id="account-dropdown" class="relative">
                    <button id="dropdown-btn" class="flex items-center gap-2 font-semibold" aria-expanded="false" aria-controls="dropdown-menu">
                        <svg id="dropdown-icon" class="w-4 h-4 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdown-menu" class="absolute right-0 mt-2 hidden bg-white shadow-lg rounded w-48 z-50">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Friends</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Groups</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Discussions</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Comments</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Quotes</a>
                        <div class="border-t"></div>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Account settings</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Help</a>

                        <!-- Logout -->
                        <form id="logout-form">
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash + content -->
    <main class="max-w-6xl mx-auto p-4">
        @if(session('success'))
            <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Scripts -->
    <script>
(function () {
    // --- Storage with Expiry ---
    function setWithExpiry(key, value, ttl) {
        const now = Date.now();
        const item = {
            value: value,
            expiry: now + ttl
        };
        localStorage.setItem(key, JSON.stringify(item));
    }

    function getWithExpiry(key) {
        const itemStr = localStorage.getItem(key);
        if (!itemStr) return null;
        try {
            const item = JSON.parse(itemStr);
            const now = Date.now();
            if (now > item.expiry) {
                localStorage.removeItem(key);
                return null;
            }
            return item.value;
        } catch {
            return null;
        }
    }

    // --- Nav toggle ---
    const guestMenu = document.getElementById('guest-menu');
    const authMenu  = document.getElementById('auth-menu');
    const userName  = document.getElementById('user-name');

    const user = getWithExpiry('user');
    const token = getWithExpiry('token');

    if (user && user.name && token) {
        userName.textContent = user.name;
        guestMenu.classList.add('hidden');
        authMenu.classList.remove('hidden');
    } else {
        guestMenu.classList.remove('hidden');
        authMenu.classList.add('hidden');
    }

    // --- Dropdown ---
    const container = document.getElementById('account-dropdown');
    const btn   = document.getElementById('dropdown-btn');
    const menu  = document.getElementById('dropdown-menu');
    const icon  = document.getElementById('dropdown-icon');

    function openMenu() {
        menu.classList.remove('hidden');
        btn.setAttribute('aria-expanded', 'true');
        if (icon) icon.classList.add('rotate-180');
    }
    function closeMenu() {
        menu.classList.add('hidden');
        btn.setAttribute('aria-expanded', 'false');
        if (icon) icon.classList.remove('rotate-180');
    }

    if (btn && menu && container) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (menu.classList.contains('hidden')) openMenu(); else closeMenu();
        });
        document.addEventListener('click', function (e) {
            if (!menu.classList.contains('hidden') && !container.contains(e.target)) {
                closeMenu();
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    }

    // --- Logout ---
    const logoutForm = document.getElementById('logout-form');
    if (logoutForm) {
        logoutForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const token = getWithExpiry('token');
            if (token) {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': 'Bearer ' + token,
                            'Content-Type': 'application/json'
                        }
                    });
                } catch (_) {}
            }
            localStorage.removeItem('user');
            localStorage.removeItem('token');
            window.location.href = "{{ route('auth.login.view') }}";
        });
    }

    // --- Guard for restricted pages ---
    document.addEventListener('DOMContentLoaded', function () {
        const restricted = ['/books'];
        const token = getWithExpiry('token'); // ✅ explicitly check value
        if (restricted.includes(window.location.pathname) && !token) {
            window.location.href = "{{ route('auth.login.view') }}";
        }
    });

    // ❌ Removed beforeunload auto-clear (it was wiping token on refresh!)
})();
</script>

</body>
</html>
