<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai - LIBRAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>

            <!-- Navbar Links for Large Screens -->
            <div class="hidden sm:flex space-x-4">
                <a href="{{ url('pegawai/dashboard') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
                <a href="dashboard#catalog" class="hover:text-gray-300 transition-all-custom hover:underline">Katalog Buku</a>
                <a href="{{ route('pegawai.books') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Kelola Buku</a>
                <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300 hover:underline">Peminjaman Aktif</a>
                <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-gray-300 transition-all-custom hover:underline">Logout</button>
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
            <a href="{{ url('pegawai/dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
            <a href="#catalog" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
            <a href="{{ route('pegawai.books') }}" class="block text-white py-2 hover:bg-blue-700">Kelola Buku</a>
            <a href="{{ route($userRole . '.active-borrows') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman Aktif</a>
            <a href="{{ route('profile.show') }}" class="block text-white py-2 hover:bg-blue-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="block w-full text-left text-white py-2 hover:bg-blue-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-100 text-center py-20" data-aos="fade-up">
        <h1 class="text-4xl font-semibold text-gray-800">Selamat Datang, Pegawai</h1>
        <p class="mt-4 text-lg text-gray-600 px-4 sm:px-6 md:px-8 mx-auto max-w-screen-lg">
            Kelola koleksi perpustakaan dengan mudah, pantau peminjaman dan pengembalian buku, serta atur stok koleksi secara efisien.
        </p>
    </section>

    <section class="py-8 bg-white shadow-md mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-center">
                <div class="w-full max-w-xl">
                    <h2 class="text-xl font-semibold mb-4 text-center">Pencarian Buku</h2>
                    <form id="searchForm" class="flex items-center">
                        @csrf
                        <input 
                            type="text" 
                            name="query" 
                            id="searchInput"
                            placeholder="Cari buku berdasarkan judul atau penulis..." 
                            class="rounded-full w-full px-4 py-2 border border-gray-300 focus:outline-none focus:border-blue-500"
                        >
                        <button type="button" id="searchButton" class="ml-2 px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all-custom">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <div id="searchResults" class="hidden max-w-7xl mx-auto px-4 mt-4 mb-8">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div id="resultsContent" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section id="loan-management" class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Kelola Peminjaman</h2>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b">Peminjam</th>
                                    <th class="px-6 py-3 border-b">Buku</th>
                                    <th class="px-6 py-3 border-b">Tanggal Pinjam</th>
                                    <th class="px-6 py-3 border-b">Status</th>
                                    <th class="px-6 py-3 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingLoans as $loan)
                                <tr>
                                    <td class="px-6 py-4 border-b">{{ $loan->user->nama }}</td>
                                    <td class="px-6 py-4 border-b">{{ $loan->buku->judul }}</td>
                                    <td class="px-6 py-4 border-b">{{ $loan->tanggal_peminjaman->format('d M Y') }}</td>
                                    <td class="px-6 py-4 border-b">
                                        <span class="px-2 py-1 rounded-full {{ $loan->status_peminjaman === 'Menunggu Konfirmasi' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $loan->status_peminjaman }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 border-b">
                                        @if($loan->status_peminjaman === 'Menunggu Konfirmasi')
                                        <button onclick="confirmReturn({{ $loan->id }})" 
                                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                            Konfirmasi Pengembalian
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="return-management" class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Pengembalian Terbaru</h2>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        @foreach($recentReturns as $return)
                        <div class="border-b pb-4 mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $return->peminjaman->user->nama }}</p>
                                    <p class="text-sm text-gray-600">{{ $return->peminjaman->buku->judul }}</p>
                                    <p class="text-xs text-gray-500">Dikembalikan: {{ $return->tanggal_pengembalian->format('d M Y H:i') }}</p>
                                </div>
                                @if($return->denda > 0)
                                <div class="bg-red-100 text-red-800 px-3 py-1 rounded-full">
                                    Denda: Rp {{ number_format($return->denda, 0, ',', '.') }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

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
                        <p class="text-sm text-gray-600">Stok: {{ $book->stok }}</p>
                        <p class="text-sm text-gray-600">Status: 
                            <span class="{{ $book->stok > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $book->stok > 0 ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </p>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </section>
        </div>
    </div>

    <footer class="bg-blue-600 text-white py-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <script>
        function confirmReturn(loanId) {
            Swal.fire({
                title: 'Konfirmasi Pengembalian',
                text: "Apakah Anda yakin ingin mengkonfirmasi pengembalian buku ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/confirm-return/${loanId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true
        });

        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    </script>

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
</body>
</html>