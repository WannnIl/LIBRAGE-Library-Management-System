<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LIBRAGE</title>
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

  @auth
    @if(Auth::user()->role == 'admin')
      <script>window.location = "{{ route('admin.dashboard') }}";</script>
    @elseif(Auth::user()->role == 'pegawai')
      <script>window.location = "{{ route('pegawai.dashboard') }}";</script>
    @elseif(Auth::user()->role == 'mahasiswa')
      <script>window.location = "{{ route('mahasiswa.dashboard') }}";</script>
    @endif
  @endauth

  <!-- Navbar -->
  <nav class="bg-blue-600 p-4 text-white">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>
      <div class="flex space-x-4 items-center sm:hidden">
        <button id="menu-toggle" class="text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
      <div class="hidden sm:flex space-x-4">
        <a href="{{ url('/') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
        <a href="#catalog" class="hover:text-gray-300 transition-all-custom hover:underline">Katalog Buku</a>
        @auth
          @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition-all-custom">Dashboard Admin</a>
          @elseif(Auth::user()->role == 'pegawai')
            <a href="{{ route('pegawai.dashboard') }}" class="hover:text-gray-300 transition-all-custom">Dashboard Pegawai</a>
          @elseif(Auth::user()->role == 'mahasiswa')
            <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-gray-300 transition-all-custom">Dashboard Mahasiswa</a>
          @endif
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="hover:text-gray-300 transition-all-custom hover:underline">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Login</a>
          <a href="{{ route('register') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Daftar</a>
        @endauth
      </div>
    </div>
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-blue-600 p-4">
      <a href="{{ url('/') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
      <a href="#catalog" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
      @auth
        @if(Auth::user()->role == 'admin')
          <a href="{{ route('admin.dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Dashboard Admin</a>
        @elseif(Auth::user()->role == 'pegawai')
          <a href="{{ route('pegawai.dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Dashboard Pegawai</a>
        @elseif(Auth::user()->role == 'mahasiswa')
          <a href="{{ route('mahasiswa.dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Dashboard Mahasiswa</a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="block text-white py-2 hover:bg-blue-700">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block text-white py-2 hover:bg-blue-700">Login</a>
        <a href="{{ route('register') }}" class="block text-white py-2 hover:bg-blue-700">Daftar</a>
      @endauth
    </div>
  </nav>

  <!-- Hero Section with Fade-in effect -->
  <section class="bg-blue-100 text-center py-20" data-aos="fade-up">
    <h1 class="text-4xl font-semibold text-gray-800">Selamat Datang di LIBRAGE</h1>
    <p class="mt-4 text-lg text-gray-600">Jelajahi koleksi buku terbaru dan pinjam dengan mudah.</p>
    <p class="mt-4 text-md text-gray-600 px-4 sm:px-6 md:px-8 mx-auto max-w-screen-lg">
      LIBRAGE adalah sistem manajemen perpustakaan modern yang memungkinkan Anda untuk menjelajahi koleksi buku kami dengan mudah, meminjamnya kapan saja, <br>
      dan menikmati pengalaman membaca tanpa hambatan. Dengan LIBRAGE, mengakses buku-buku favorit Anda menjadi lebih praktis, cepat, dan efisien.
    </p>
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
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all-custom">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>
  </section>

  <!-- Live Search Results -->
  <div id="searchResults" class="hidden max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-white rounded-md shadow-lg p-4">
        <div id="resultsContent" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        </div>
    </div>
</div>

  <!-- Koleksi Buku with hover zoom and shadow effect -->
  <section id="catalog" class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Koleksi Buku Terbaru</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($books as $book)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all-custom hover:scale-105 hover:shadow-2xl">
          <img src="{{ asset($book->image) }}" alt="{{ $book->judul }}" class="w-full h-60 object-cover transition-all-custom">
          <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800">{{ $book->judul }}</h3>
            <p class="text-gray-600">Penulis: {{ $book->penulis }}</p>
            <a href="/book/{{ $book->id }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all-custom">Lihat Detail</a>
            @guest
              <a href="{{ route('login') }}" class="inline-block mt-4 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-all-custom">Login untuk Meminjam</a>
            @endguest
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <footer class="bg-blue-600 text-white py-6">
    <div class="max-w-7xl mx-auto text-center">
      <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
    </div>
  </footer>

  <script>
    AOS.init({
      duration: 1000,
    });

    document.getElementById('menu-toggle').addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    });
  </script>

<script>
  let searchTimeout;
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');
  const resultsContent = document.getElementById('resultsContent');
  
  searchInput.addEventListener('input', function() {
      clearTimeout(searchTimeout);
      const query = this.value;
      
      if(query.length >= 2) {
          searchTimeout = setTimeout(() => {
              fetch(`/search?query=${query}`, {
                  headers: {
                      'X-Requested-With': 'XMLHttpRequest'
                  }
              })
              .then(response => response.json())
              .then(data => {
                  resultsContent.innerHTML = '';
                  
                  if(data.books.length > 0) {
                      data.books.forEach(book => {
                          resultsContent.innerHTML += `
                              <div class="bg-white p-4 rounded-lg shadow">
                                  <img src="${book.image}" alt="${book.judul}" class="w-full h-48 object-cover rounded mb-4">
                                  <h3 class="text-lg font-semibold">${book.judul}</h3>
                                  <p class="text-gray-600">Penulis: ${book.penulis}</p>
                                  <a href="/book/${book.id}" class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                                      Lihat Detail
                                  </a>
                              </div>
                          `;
                      });
                      searchResults.classList.remove('hidden');
                  } else {
                      resultsContent.innerHTML = `
                          <div class="col-span-full text-center text-gray-500">
                              Tidak ada buku yang ditemukan
                          </div>
                      `;
                      searchResults.classList.remove('hidden');
                  }
              });
          }, 300);
      } else {
          searchResults.classList.add('hidden');
      }
  });
  
  document.addEventListener('click', function(e) {
      if (!searchResults.contains(e.target) && e.target !== searchInput) {
          searchResults.classList.add('hidden');
      }
  });
  </script>
</body>
</html>
