<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GolInfo</title>
  <!-- <link href="style.css" rel="stylesheet"> -->
  <link href="{{ asset('style.css') }}" rel="stylesheet">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  @stack('styles')
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">GolInfo</div>
    <div class="menu">
      <a href="{{ route('leagues') }}">Przeglądaj</a>
      <a href="{{ route('matches') }}">Mecze</a>
      <a href="{{ route('favourite_clubs') }}">Ulubione kluby</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="menu-link" style="background:none;border:none;padding:0;margin:0;color:inherit;cursor:pointer;font:inherit;">
          Wyloguj
        </button>
      </form>
    </div>
  </nav>

  <main class="container mt-4">
    @yield('content')
  </main>

  @stack('scripts')
</body>
</html>