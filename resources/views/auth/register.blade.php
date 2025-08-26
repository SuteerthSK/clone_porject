@extends('layouts.app')
@section('content')
<h1 class="text-2xl font-bold mb-4">Register</h1>
<form method="POST" id="register-form" action="" class="bg-white p-6 rounded shadow max-w-md">
  @csrf
  <label class="block text-sm">Name</label>
  <input name="name" class="border rounded p-2 w-full" required />
  <label class="block text-sm mt-3">Email</label>
  <input type="email" name="email" class="border rounded p-2 w-full" required />
  <label class="block text-sm mt-3">Password</label>
  <input type="password" name="password" class="border rounded p-2 w-full" required minlength="6"/>
  <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Create Account</button>
</form>
<script>
  // Client-side form validation
  document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent default form submission
    const password = this.querySelector('input[name="password"]').value;
    if (password.length < 6) {
      event.preventDefault();
      alert('Password must be at least 6 characters long.');
    }
    // Additional validation can be added here
    $.ajax({
      url: '{{ route('register') }}',
      method: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function(response) {
        alert('Registration successful!');
        window.location.href = '{{ route('auth.login.view') }}';
      },
      error: function(xhr) {
        alert('Registration failed: ' + xhr.responseText);
      }
    });

  });
</script>
@endsection
