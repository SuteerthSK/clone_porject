<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ config('app.name', 'GoodreadsClone') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900">
  <nav class="bg-white border-b">
    <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
      <a href="{{ route('home') }}" class="text-xl font-bold">📚 Goodreads Clone</a>
      <div class="flex gap-4">
        <a href="{{ route('books.search') }}" class="hover:underline">Books</a>
        <a href="{{ route('auth.register.view') }}" class="hover:underline">Register</a>
        <a href="{{ route('auth.login.view') }}" class="hover:underline">Login</a>
      </div>
    </div>
  </nav>

  <main class="max-w-6xl mx-auto p-4">
    @if(session('success'))
      <div class="p-3 mb-4 bg-green-100 border border-green-300 rounded">{{ session('success') }}</div>
    @endif
    @yield('content')
  </main>

  @yield('scripts')
</body>
</html>
