<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Goodreads Clone - Landing Page</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #fff;
      color: #333;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #f5f5f5;
      border-bottom: 1px solid #ddd;
    }
    header h1 {
      font-size: 24px;
      color: #2c2c2c;
    }
    nav a {
      margin: 0 15px;
      text-decoration: none;
      font-weight: bold;
      color: #333;
    }
    nav a:hover {
      color: #0073e6;
    }
    .hero {
      display: flex;
      justify-content: center;
      align-items: center;
      background: #fdf0c2;
      padding: 60px 20px;
    }
    .hero-content {
      max-width: 600px;
      text-align: center;
    }
    .hero-content h2 {
      font-size: 36px;
      margin-bottom: 20px;
    }
    .hero-content p {
      font-size: 18px;
      margin-bottom: 30px;
      line-height: 1.5;
    }
    .btn {
      display: inline-block;
      margin: 10px;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .btn-primary {
      background: #0073e6;
      color: #fff;
    }
    .btn-dark {
      background: #333;
      color: #fff;
    }
    .btn-light {
      background: #fff;
      color: #333;
      border: 1px solid #333;
    }
    footer {
      text-align: center;
      padding: 20px;
      background: #f5f5f5;
      border-top: 1px solid #ddd;
      margin-top: 40px;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <h1>📚 Goodreads Clone</h1>
    <nav>
      {{-- <a href="{{ route('books.index') }}">Books</a> --}}
      <a href="{{ route('auth.register.view') }}">Register</a>
      <a href="{{ route('auth.login.view') }}">Login</a>
    </nav>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h2>Discover & Read More</h2>
      <p>Tell us what books you love, and we’ll help you discover new favorites.  
      Connect with friends and see what they’re reading on Goodreads Clone.</p>

      <!-- Buttons -->
      <button class="btn btn-dark"><a href="{{ route('auth.register.view') }}">Register</a></button>
      <button class="btn btn-primary"><a href="{{ route('auth.login.view') }}">Login</a></button>
      {{-- <button class="btn btn-light" onclick="redirect('/books')">Browse Books</button> --}}
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 Goodreads Clone. Built for learning.</p>
  </footer>

  <script>
    function redirect(path) {
      window.location.href = path;
    }
  </script>
</body>
</html>
