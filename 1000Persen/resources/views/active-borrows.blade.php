<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - LIBRAGE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold hover:scale-110 transform transition-all-custom">LIBRAGE</div>
    
            <!-- Navbar Links for Large Screens -->
            <div class="hidden sm:flex space-x-4">
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
                    <a href="{{ route('admin.users') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Kelola Pengguna</a>
                    <a href="{{ route('admin.books') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Kelola Buku</a>
                    <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300 hover:underline">Peminjaman Aktif</a>
                @elseif(Auth::user()->role == 'pegawai')
                    <a href="{{ route('pegawai.dashboard') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
                    <a href="{{ url('pegawai/dashboard/#catalog') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Katalog Buku</a>
                    <a href="{{ route('pegawai.books') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Kelola Buku</a>
                    <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300 hover:underline">Peminjaman Aktif</a>
                @else
                    <a href="{{ url('dashboard/mahasiswa') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Beranda</a>
                    <a href="{{ url('dashboard/mahasiswa#catalog') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Katalog Buku</a>
                    <a href="{{ route('book.loan') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Peminjaman</a>
                    <a href="{{ route('book.return') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Pengembalian</a>
                    <a href="{{ route('book.loanHistory') }}" class="hover:text-gray-300 transition-all-custom hover:underline">Riwayat Peminjaman</a>
                @endif
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

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-2xl font-bold mb-6">Daftar Peminjaman Aktif</h2>

        @if($activeLoans->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">Tidak ada peminjaman aktif saat ini</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Pengembalian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activeLoans as $loan)
                        @php
                            $dueDate = \Carbon\Carbon::parse($loan->tanggal_peminjaman)->addDays(7);
                            $isOverdue = $dueDate->isPast();
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->user->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->buku->judul }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->tanggal_peminjaman->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $isOverdue ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $dueDate->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $isOverdue ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $isOverdue ? 'Terlambat' : 'Dipinjam' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6 mt-8">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
        </div>
    </footer>
</body>
</html>