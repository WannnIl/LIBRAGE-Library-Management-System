<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - LIBRAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
          <div class="text-2xl font-bold">LIBRAGE</div>
          <div class="space-x-4 hidden sm:flex">
              <a href="{{ url('/') }}" class="hover:text-gray-300 hover:underline">Beranda</a>
              <a href="{{ route('admin.users') }}" class="hover:text-gray-300 hover:underline">Kelola Pengguna</a>
              <a href="{{ route('admin.books') }}" class="hover:text-gray-300 hover:underline">Kelola Buku</a>
              <a href="{{ route(Auth::user()->role . '.active-borrows') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Peminjaman Aktif</a>
              <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Profile</a>
              <form method="POST" action="{{ route('logout') }}" class="inline">
                  @csrf
                  <button type="submit" class="hover:text-gray-300 hover:underline">Logout</button>
              </form>
          </div>
          <div class="sm:hidden">
              <button id="menuButton" class="text-white">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                  </svg>
              </button>
          </div>
      </div>
  </nav>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="bg-blue-600 text-white hidden sm:hidden">
      <a href="{{ url('/') }}" class="block px-4 py-2">Beranda</a>
      <a href="{{ route('admin.users') }}" class="block px-4 py-2">Kelola Pengguna</a>
      <a href="{{ route('admin.books') }}" class="block px-4 py-2">Kelola Buku</a>
      <a href="{{ route(Auth::user()->role . '.active-borrows') }}" class="block px-4 py-2">Peminjaman Aktif</a>
      <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-all-custom">Profile</a>
      <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2">
          @csrf
          <button type="submit" class="w-full text-left">Logout</button>
      </form>
  </div>

    <!-- Dashboard Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Dashboard Admin</h2>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Total Pengguna</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalUsers }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Total Buku</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalBooks }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Peminjaman Aktif</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $activeLoans }}</p>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700">Total Kategori</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCategories }}</p>
                </div>
            </div>

            <section id="pending-returns" class="mb-8">
                <h2 class="text-2xl font-bold mb-4">Konfirmasi Pengembalian Buku</h2>
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

            <!-- Recent Activities -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Latest Loans -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Peminjaman Terbaru</h3>
                    <div class="space-y-4">
                        @forelse($latestPeminjaman as $pinjam)
                            <div class="border-b pb-4">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="font-medium">{{ $pinjam->user->nama }}</p>
                                        <p class="text-sm text-gray-600">{{ $pinjam->buku->judul }}</p>
                                        <p class="text-xs text-gray-500">{{ $pinjam->tanggal_peminjaman->diffForHumans() }}</p>
                                    </div>
                                    <span class="px-2 py-4 text-sm rounded-full {{ $pinjam->status_peminjaman === 'Dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $pinjam->status_peminjaman }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center">Tidak ada peminjaman terbaru</p>
                        @endforelse
                    </div>
                </div>

                <!-- Latest Returns -->
                <div class="bg-white rounded-lg shadow-md p-6">
                  <h3 class="text-xl font-semibold text-gray-800 mb-4">Pengembalian Terbaru</h3>
                  <div class="space-y-4">
                      @forelse($latestPengembalian as $kembali)
                          <div class="border-b pb-4">
                              <div class="flex justify-between">
                                  <div>
                                      <p class="font-medium">{{ $kembali->peminjaman->user->nama }}</p>
                                      <p class="text-sm text-gray-600">{{ $kembali->peminjaman->buku->judul }}</p>
                                      <p class="text-xs text-gray-500">{{ $kembali->tanggal_pengembalian->diffForHumans() }}</p>
                                      @if($kembali->denda > 0 && $kembali->reason)
                                          <p class="text-xs text-red-600 mt-1">
                                              <span class="font-semibold">Alasan Keterlambatan:</span> {{ $kembali->reason }}
                                          </p>
                                      @endif
                                  </div>
                                  @if($kembali->denda > 0)
                                      <span class="px-2 py-6 bg-red-100 text-red-800 text-sm rounded-full h-fit">
                                          Denda: Rp {{ number_format($kembali->denda, 0, ',', '.') }}
                                      </span>
                                  @endif
                              </div>
                          </div>
                      @empty
                          <p class="text-gray-500 text-center">Tidak ada pengembalian terbaru</p>
                      @endforelse
                    </div>
                </div>  
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-8">
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
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/pengembalian/confirm/${loanId}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);

                    // Submit form
                    form.submit();

                    // Show success message after submission
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Pengembalian buku berhasil dikonfirmasi',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    }, 1000);
                }
            });
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        @endif


      // Mobile Menu Toggle
      document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>