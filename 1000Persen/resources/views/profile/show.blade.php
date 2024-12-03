{{-- resources/views/profile/show.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - LIBRAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>
    
            <!-- Navbar Links for Large Screens -->
            <div class="hidden sm:flex space-x-4">
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
                    <a href="{{ route('admin.users') }}" class="hover:text-gray-300 transition-all-custom">Kelola Pengguna</a>
                    <a href="{{ route('admin.books') }}" class="hover:text-gray-300 transition-all-custom">Kelola Buku</a>
                    <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300">Peminjaman Aktif</a>
                @elseif(Auth::user()->role == 'pegawai')
                    <a href="{{ route('pegawai.dashboard') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
                    <a href="{{ url('pegawai/dashboard/#catalog') }}" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
                    <a href="{{ route('pegawai.books') }}" class="hover:text-gray-300 transition-all-custom">Kelola Buku</a>
                    <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300">Peminjaman Aktif</a>
                @else
                    <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom">Beranda</a>
                    <a href="{{ url('dashboard/mahasiswa#catalog') }}" class="hover:text-gray-300 transition-all-custom">Katalog Buku</a>
                    <a href="{{ route('book.loan') }}" class="hover:text-gray-300 transition-all-custom">Peminjaman</a>
                    <a href="{{ route('book.return') }}" class="hover:text-gray-300 transition-all-custom">Pengembalian</a>
                    <a href="{{ route('book.loanHistory') }}" class="hover:text-gray-300 transition-all-custom">Riwayat Peminjaman</a>
                @endif
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
            @if(Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
                <a href="{{ route('admin.users') }}" class="block text-white py-2 hover:bg-blue-700">Kelola Pengguna</a>
                <a href="{{ route('admin.books') }}" class="block text-white py-2 hover:bg-blue-700">Kelola Buku</a>
                <a href="{{ route($userRole . '.active-borrows') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman Aktif</a>
            @elseif(Auth::user()->role == 'pegawai')
                <a href="{{ route('pegawai.dashboard') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
                <a href="{{ url('pegawai/dashboard/#catalog') }}" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
                <a href="{{ route('pegawai.books') }}" class="block text-white py-2 hover:bg-blue-700">Kelola Buku</a>
                <a href="{{ route($userRole . '.active-borrows') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman Aktif</a>
            @else
                <a href="{{ url('dashboard/mahasiswa') }}" class="block text-white py-2 hover:bg-blue-700">Beranda</a>
                <a href="{{ url('dashboard/mahasiswa#catalog') }}" class="block text-white py-2 hover:bg-blue-700">Katalog Buku</a>
                <a href="{{ route('book.loan') }}" class="block text-white py-2 hover:bg-blue-700">Peminjaman</a>
                <a href="{{ route('book.return') }}" class="block text-white py-2 hover:bg-blue-700">Pengembalian</a>
                <a href="{{ route('book.loanHistory') }}" class="block text-white py-2 hover:bg-blue-700">Riwayat Peminjaman</a>
            @endif
            <a href="{{ route('profile.show') }}" class="block text-white py-2 hover:bg-blue-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="block w-full text-left text-white py-2 hover:bg-blue-700">Logout</button>
            </form>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-semibold mb-6">Profil Saya</h2>

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-md mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama" class="mb-2 block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" 
                           class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                           class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label for="alamat" class="mb-2 block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" 
                              class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" required>{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                    
                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" 
                               class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" id="password" 
                               class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="py-2 px-1 block w-full rounded-md border border-black shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <div class="flex justify-end space-x-4">
                    @php
                        $dashboardRoute = '';
                        if(Auth::user()->role == 'admin') {
                            $dashboardRoute = route('admin.dashboard');
                        } elseif(Auth::user()->role == 'pegawai') {
                            $dashboardRoute = route('pegawai.dashboard');
                        } else {
                            $dashboardRoute = url('dashboard/mahasiswa');
                        }
                    @endphp
                    
                    <a href="{{ $dashboardRoute }}" class="px-4 py-2 rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Kembali
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

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

    <script>        
        // Toggle mobile menu visibility
        document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>