<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Mahasiswa - LIBRAGE</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script> <!-- AOS Library for animations -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Custom transition for hover effects */
    .transition-all-custom {
      transition: all 0.3s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-50 font-sans">

  <!-- Navbar -->
  <nav class="bg-blue-600 p-4 text-white">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>

      <!-- Navbar Links for Large Screens -->
      <div class="hidden sm:flex space-x-4">
        <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
        <a href="#catalog" class="hover:text-gray-300 transition-all-custom hover:underline">Katalog Buku</a>
        <a href="{{ route('book.loan') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Peminjaman</a>
        <a href="{{ route('book.return') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Pengembalian</a>
        <a href="{{ route('book.loanHistory') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Riwayat Peminjaman</a>
        <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Profile</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="hover:text-gray-300 transition-all-custom hover:underline">Logout</button>
        </form>
      </div>

      <!-- Hamburger Menu for Small Screens -->
      <div class="sm:hidden flex items-center">
        <button id="menu-toggle" class="text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu (Hidden by default, shown when toggled) -->
    <div id="mobile-menu" class="sm:hidden hidden bg-blue-600 p-4">
      <a href="{{ url('dashboard/mahasiswa') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
      <a href="#catalog" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
      <a href="{{ route('book.loan') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman</a>
      <a href="{{ route('book.loanHistory') }}" class="block text-white py-2 hover:bg-blue-700">Riwayat Peminjaman</a>
      <a href="{{ route('profile.show') }}" class="block text-white py-2 hover:bg-blue-700">Profile</a>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="block text-white py-2 hover:bg-blue-700">Logout</button>
      </form>
    </div>
  </nav>

  <!-- Hero Section with Fade-in effect -->
  <section class="bg-blue-100 text-center py-20" data-aos="fade-up">
    <h1 class="text-4xl font-semibold text-gray-800">Selamat Datang, Mahasiswa</h1>
    <p class="mt-4 text-lg text-gray-600">Jelajahi koleksi buku dan pinjam dengan mudah.</p>
    <a href="#catalog" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-all-custom">Jelajahi Koleksi Buku</a>
  </section>

  <!-- Search Section -->
  <section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-center">
            <div class="w-full max-w-xl">
                <form action="{{ route('books.search') }}" method="GET" class="flex items-center">
                    <input 
                        type="text" 
                        name="query" 
                        id="searchInput"
                        placeholder="Cari buku berdasarkan judul atau penulis..." 
                        class="rounded-full w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-blue-500"
                    >
                    <button type="submit" class="ml-2 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all-custom">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
  </section>

  <div id="searchResults" class="hidden max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <div id="resultsContent" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        </div>
    </div>
  </div>

  <!-- Koleksi Buku with hover zoom and shadow effect -->
  <section id="catalog" class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Koleksi Buku</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($books as $book)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all-custom hover:scale-105 hover:shadow-2xl">
          <img src="{{ asset($book->image) }}" alt="{{ $book->judul }}" class="w-full h-60 object-cover transition-all-custom">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800">{{ $book->judul }}</h3>
            <p class="text-gray-600">Penulis: {{ $book->penulis }}</p>
            <a href="/book/{{ $book->id }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all-custom">Lihat Detail</a>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Footer with animation -->
  <footer class="bg-blue-600 text-white py-6">
    <div class="max-w-7xl mx-auto text-center">
      <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
    </div>
  </footer>

  <!-- Initialize AOS -->
  <script>
    AOS.init({
      duration: 1000, // Animation duration in ms
    });

    // Toggle mobile menu visibility
    document.getElementById('menu-toggle').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    });
  </script>

  

</body>
</html>
