<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - LIBRAGE</title>
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
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="text-2xl font-bold">LIBRAGE</div>
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-white py-2 hover:bg-blue-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Peminjaman Buku Section -->
    <section class="py-16 bg-gray-50" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Peminjaman Buku</h2>
            
            <!-- Display borrowed count -->
            <div class="text-center mb-4">
                <p class="font-bold text-lg text-gray-600">
                    Buku yang sedang dipinjam: {{ $currentlyBorrowed }} / {{ App\Http\Controllers\PeminjamanController::MAX_BOOKS_BORROWED }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($books as $book)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-500">
                    <img src="{{ asset($book->image) }}" alt="{{ $book->judul }}" class="w-full h-60 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $book->judul }}</h3>
                        <p class="text-gray-600">Penulis: {{ $book->penulis }}</p>
                        <p class="text-gray-600">Stok: {{ $book->stok }}</p>
                        <form action="{{ route('book.loanBook') }}" method="POST" class="mt-4 loan-form">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="button" 
                                onclick="checkBorrowLimit(this.form, {{ $currentlyBorrowed }}, {{ App\Http\Controllers\PeminjamanController::MAX_BOOKS_BORROWED }})"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all-custom
                                {{ $currentlyBorrowed >= App\Http\Controllers\PeminjamanController::MAX_BOOKS_BORROWED ? 'opacity-50 cursor-not-allowed' : '' }}">
                                Pinjam Buku
                            </button>
                        </form>
                    </div>
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
            title: 'Oops...',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        });
    </script>
    @endif

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true
        });

        // Toggle mobile menu
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Check borrow limit function
        function checkBorrowLimit(form, currentlyBorrowed, maxLimit) {
            if(currentlyBorrowed >= maxLimit) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Peminjaman Tercapai',
                    text: `Anda telah mencapai batas maksimal peminjaman buku (${maxLimit} buku)`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            } else {
                form.submit();
            }
        }
    </script>

</body>
</html>