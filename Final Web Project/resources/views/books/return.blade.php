<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
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
      <div class="hidden sm:flex space-x-4">
        <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
        <a href="dashboard/mahasiswa#catalog" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
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
      <a href="dashboard/mahasiswa#catalog" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
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

  <!-- Pengembalian Buku Section -->
  <section class="py-16 bg-gray-50" data-aos="fade-up">
    <div class="max-w-7xl mx-auto px-4">
      <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Pengembalian Buku</h2>
      @if($peminjaman->isEmpty())
        <p class="text-center text-gray-600">Tidak ada buku yang dipinjam</p>
      @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          @foreach($peminjaman as $pinjam)
          <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-500">
            <img src="{{ asset($pinjam->buku->image) }}" alt="{{ $pinjam->buku->judul }}" class="w-full h-60 object-cover transition-all-custom">
            <div class="p-4">
              <h3 class="text-xl font-semibold text-gray-800">{{ $pinjam->buku->judul }}</h3>
              <p class="text-gray-600">Penulis: {{ $pinjam->buku->penulis }}</p>
              <p class="text-gray-600">Tanggal Peminjaman: 
                {{ $pinjam->tanggal_peminjaman ? $pinjam->tanggal_peminjaman->format('d F Y - H:i') : 'Tanggal tidak tersedia' }}
              </p>
              
              <!-- Status Peminjaman -->
              <p class="mt-2">
                Status: 
                @if($pinjam->status_peminjaman == 'Menunggu Konfirmasi')
                    <span class="text-yellow-600 font-semibold">Menunggu Konfirmasi Pegawai</span>
                @else
                    <span class="text-blue-600 font-semibold">Dipinjam</span>
                @endif
              </p>

              @if($pinjam->status_peminjaman != 'Menunggu Konfirmasi')
                <form onsubmit="return confirmReturn(event)" action="{{ route('book.returnBook') }}" method="POST" class="mt-4">
                  @csrf
                  <input type="hidden" name="book_id" value="{{ $pinjam->buku->id }}">
                  
                  @php
                      $dueDate = Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->addDays(7);
                      $today = Carbon\Carbon::now();
                      $daysLate = 0;
                      
                      if($today->gt($dueDate)) {
                          $daysLate = $today->diffInDays($dueDate);
                          $isLate = true;
                      } else {
                          $isLate = false;
                      }
                      
                      $denda = $isLate ? 20000 : 0;
                  @endphp

                  @if($isLate)
                      <div class="mb-4">
                          <p class="text-red-600 font-semibold">
                              Terlambat {{ $daysLate }} hari. 
                              Denda: Rp {{ number_format($denda, 0, ',', '.') }}
                          </p>
                          <label for="reason" class="block text-sm font-medium text-gray-700 mt-2">Alasan Keterlambatan:</label>
                          <textarea 
                              name="reason" 
                              id="reason" 
                              rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                              required
                          ></textarea>
                      </div>
                  @else
                      <p class="text-green-600 font-semibold mb-4">
                          Batas waktu pengembalian: {{ $dueDate->format('d F Y - H:i') }}
                      </p>
                  @endif
              
                  <button type="submit" 
                      class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all-custom">
                      Ajukan Pengembalian
                  </button>
                </form>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      @endif
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
      let message = "{{ session('success') }}";
      let icon = message.includes('Denda') ? 'warning' : 'success';
      
      Swal.fire({
          icon: icon,
          title: 'Berhasil!',
          text: message,
          timer: 3000,
          showConfirmButton: true,
          confirmButtonText: 'OK',
          customClass: {
              popup: 'animate__animated animate__fadeInDown'
          }
      });
  </script>
  @endif

  @if(session('error'))
  <script>
      Swal.fire({
          icon: 'error',
          title: 'Error!',
          text: "{{ session('error') }}",
          showConfirmButton: true,
          customClass: {
              popup: 'animate__animated animate__fadeInDown'
          }
      });
  </script>
  @endif

  <script>
    function confirmReturn(event) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Konfirmasi Pengajuan Pengembalian',
            text: "Apakah Anda yakin ingin mengajukan pengembalian buku ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ajukan!',
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