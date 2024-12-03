<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      .transition-all-custom {
        transition: all 0.3s ease-in-out;
      }
    </style>
</head>
<body class="bg-gray-50">

  <!-- Navbar -->
  <nav class="bg-blue-600 p-4 text-white">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>

      <!-- Navbar Links for Large Screens -->
      <div class="hidden sm:flex space-x-4">
        <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
        <a href="{{ url('dashboard/mahasiswa#catalog') }}" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
        <a href="{{ route('book.loan') }}" class="hover:text-gray-300 transition-all-custom">Peminjaman</a>
        <a href="{{ route('book.return') }}" class="hover:text-gray-300 transition-all-custom">Pengembalian</a>
        <a href="{{ route('book.loanHistory') }}" class="hover:text-gray-300 transition-all-custom">Riwayat Peminjaman</a>
        <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-all-custom">Profile</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button type="submit" class="hover:text-gray-300 transition-all-custom">Logout</button>
        </form>
      </div>

      <!-- Hamburger Menu -->
      <div class="sm:hidden flex items-center">
        <button id="menu-toggle" class="text-white">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="sm:hidden hidden bg-blue-600 p-4">
      <a href="{{ url('dashboard/mahasiswa') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
      <a href="{{ url('dashboard/mahasiswa#catalog') }}" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
      <a href="{{ route('book.loan') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman</a>
      <a href="{{ route('book.return') }}" class="block text-white py-2 hover:bg-blue-700">Pengembalian</a>
      <a href="{{ route('book.loanHistory') }}" class="block text-white py-2 hover:bg-blue-700">Riwayat Peminjaman</a>
      <a href="{{ route('profile.show') }}" class="block text-white py-2 hover:bg-blue-700">Profile</a>
      <form method="POST" action="{{ route('logout') }}" class="inline">
        @csrf
        <button type="submit" class="block w-full text-left text-white py-2 hover:bg-blue-700">Logout</button>
      </form>
    </div>
  </nav>

  <!-- Riwayat Peminjaman Buku -->
  <section class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Riwayat Peminjaman Buku</h2>
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-semibold text-gray-800">Riwayat Peminjaman Buku</h2>
        @if(!$peminjaman->isEmpty())
            <form onsubmit="return confirmDeleteAll(event)" 
                  action="{{ route('book.destroyAllHistory') }}" 
                  method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition-colors">
                    Hapus Semua Riwayat
                </button>
            </form>
        @endif
    </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($peminjaman as $pinjam)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-500">
          @if($pinjam->buku)
            <img src="{{ asset($pinjam->buku->image) }}" alt="{{ $pinjam->buku->judul }}" class="w-full h-60 object-cover">
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ $pinjam->buku->judul }}</h3>
              <p class="text-gray-600">Penulis: {{ $pinjam->buku->penulis }}</p>
              <p class="text-gray-600">Tanggal Peminjaman: 
                {{ $pinjam->tanggal_peminjaman ? $pinjam->tanggal_peminjaman->format('d F Y - H:i') : 'Tanggal tidak tersedia' }}
              </p>
              
              <!-- Status Badge -->
              <div class="mt-2">
                <span class="px-2 py-1 rounded-full text-sm font-semibold
                  @if($pinjam->status_peminjaman == 'Dipinjam') 
                    bg-blue-100 text-blue-800
                  @elseif($pinjam->status_peminjaman == 'Menunggu Konfirmasi')
                    bg-yellow-100 text-yellow-800
                  @elseif($pinjam->status_peminjaman == 'Dikembalikan')
                    bg-green-100 text-green-800
                  @endif
                ">
                  {{ $pinjam->status_peminjaman }}
                </span>
              </div>

              @if($pinjam->pengembalian)
                <p class="text-gray-600 mt-2">Tanggal Pengembalian: 
                    {{ $pinjam->pengembalian->tanggal_pengembalian->format('d F Y - H:i') }}
                </p>
                @if($pinjam->pengembalian->denda > 0)
                    <p class="text-red-600 font-semibold mt-2">
                        Denda: Rp {{ number_format($pinjam->pengembalian->denda, 0, ',', '.') }}
                    </p>
                    @if($pinjam->pengembalian->reason)
                        <p class="text-gray-600 mt-1">
                            Alasan: {{ $pinjam->pengembalian->reason }}
                        </p>
                    @endif
                @endif
              @endif

              @if($pinjam->status_peminjaman === 'Dikembalikan')
                  <div class="mt-4">
                      <form onsubmit="return confirmDelete(event)" 
                            action="{{ route('book.destroyHistory', $pinjam->id) }}" 
                            method="POST" 
                            class="inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" 
                              class="w-full bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition-colors">
                              <i class="fas fa-trash-alt mr-2"></i>
                              Hapus Riwayat
                          </button>
                      </form>
                  </div>
              @endif
            </div>
          @else
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">Buku tidak ditemukan</h3>
              <p class="text-gray-600">Tanggal Peminjaman: {{ $pinjam->tanggal_peminjaman->format('d F Y - H:i') }}</p>
              <p class="text-gray-600">Status: {{ $pinjam->status_peminjaman }}</p>
              @if($pinjam->pengembalian)
                <p class="text-gray-600">Tanggal Pengembalian: {{ $pinjam->pengembalian->tanggal_pengembalian->format('d F Y - H:i') }}</p>
              @endif
            </div>
          @endif
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-blue-600 text-white py-6">
    <div class="max-w-7xl mx-auto text-center">
      <p>&copy; 2024 Perpustakaan Digital. Semua hak cipta dilindungi.</p>
    </div>
  </footer>

  <!-- Scripts -->
  @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            showConfirmButton: true
        });
    </script>
    @endif

  <script>
    function confirmDeleteAll(event) {
      event.preventDefault();
      
      Swal.fire({
          title: 'Konfirmasi Hapus Semua',
          text: "Apakah Anda yakin ingin menghapus semua riwayat peminjaman? Tindakan ini tidak dapat dibatalkan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus Semua!',
          cancelButtonText: 'Batal'
      }).then((result) => {
          if (result.isConfirmed) {
              event.target.submit();
          }
      });
      
      return false;
  }

    // Initialize AOS
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true
    });

    // Mobile menu toggle
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
  </script>
  
</body>
</html>