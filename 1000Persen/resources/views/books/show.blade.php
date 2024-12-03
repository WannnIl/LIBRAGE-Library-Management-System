<!-- resources/views/books/show.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

  <!-- Navbar -->
  <nav class="bg-blue-600 p-4 text-white">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
          <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>
          
          <!-- Dynamic Navigation Links -->
          @guest
              <div class="space-x-4">
                  <a href="{{ url('/') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
                  <a href="#catalog" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
                  <a href="{{ route('login') }}" class="hover:text-gray-300 transition-all-custom">Login</a>
                  <a href="{{ route('register') }}" class="hover:text-gray-300 transition-all-custom">Daftar</a>
              </div>
          @else
              <div class="hidden sm:flex space-x-4">
                  <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
                  <a href="#catalog" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
                  <a href="{{ route('book.loan') }}" class="hover:text-gray-300 transition-all-custom">Peminjaman</a>
                  <a href="{{ route('book.return') }}" class="hover:text-gray-300 transition-all-custom">Pengembalian</a>
                  <a href="{{ route('book.loanHistory') }}" class="hover:text-gray-300 transition-all-custom">Riwayat Peminjaman</a>
                  <form method="POST" action="{{ route('logout') }}" class="inline">
                      @csrf
                      <button type="submit" class="hover:text-gray-300 transition-all-custom">Logout</button>
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
          @endguest
      </div>

      <!-- Mobile Menu (Only shown for authenticated users) -->
      @auth
          <div id="mobile-menu" class="sm:hidden hidden bg-blue-600 p-4">
              <a href="{{ url('dashboard/mahasiswa') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
              <a href="#catalog" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
              <a href="{{ route('book.loan') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman</a>
              <a href="{{ route('book.return') }}" class="block text-white py-2 hover:bg-blue-700">Pengembalian</a>
              <a href="{{ route('book.loanHistory') }}" class="block text-white py-2 hover:bg-blue-700">Riwayat Peminjaman</a>
              <form method="POST" action="{{ route('logout') }}" class="inline">
                  @csrf
                  <button type="submit" class="block w-full text-left text-white py-2 hover:bg-blue-700">Logout</button>
              </form>
          </div>
      @endauth
  </nav>

  <!-- Detail Buku -->
  <section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
      <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <img src="{{ asset($book->image) }}" alt="{{ $book->judul }}" class="w-full h-96 object-cover">
        <div class="p-6">
          <h2 class="text-3xl font-semibold text-gray-800">{{ $book->judul }}</h2>
          <p class="text-gray-600 mt-4">Penulis: {{ $book->penulis }}</p>
          <p class="text-gray-600 mt-2">Penerbit: {{ $book->penerbit }}</p>
          <p class="text-gray-600 mt-2">Tahun Terbit: {{ $book->tahun_terbit }}</p>
          <p class="text-gray-600 mt-2">Stok: {{ $book->stok }}</p>
          <p class="text-gray-600 mt-2">Deskripsi: {{ $book->deskripsi }}</p>
          <a href="{{ url('/') }}" class="inline-block mt-6 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Kembali ke Katalog</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-blue-600 text-white py-6">
    <div class="max-w-7xl mx-auto text-center">
      <p>&copy; 2024 Perpustakaan Digital. Semua hak cipta dilindungi.</p>
    </div>
  </footer>
  
</body>
</html>