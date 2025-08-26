@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">Login</h1>
@if ($errors->any())
  <div class="p-3 mb-4 bg-red-100 border border-red-300 rounded">
    {{ $errors->first() }}
  </div>
@endif

<form method="POST" id="login-form" action="" class="bg-white p-6 rounded shadow max-w-md">
  @csrf
  <label class="block text-sm">Email</label>
  <input type="email" name="email" class="border rounded p-2 w-full" required />

  <label class="block text-sm mt-3">Password</label>
  <input type="password" name="password" class="border rounded p-2 w-full" required />

  <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Login</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function setWithExpiry(key, value, ttl) {
    const now = Date.now();
    const item = {
      value: value,
      expiry: now + ttl
    };
    localStorage.setItem(key, JSON.stringify(item));
  }

  document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    $.ajax({
      url: '{{ route('login') }}',
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function(response) {
        alert('Login successful!');
        console.log('User logged in:', response);

        // Store with expiry (1 hour)
        const ttl = 1000 * 60 * 60;
        setWithExpiry('token', response.token, ttl);
        setWithExpiry('user', response.user, ttl);

        window.location.href = '{{ route('books.index') }}';
      },
      error: function(xhr) {
        alert('Login failed: ' + xhr.responseText);
      }
    });
  });
</script>
@endsection
