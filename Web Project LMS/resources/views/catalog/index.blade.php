<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100">
    <!-- Header -->
    <header class="text-white py-7 text-center bg-gradient-to-r from-blue-300 via-blue-500 to-blue-300 shadow-lg">
        <h1 class="text-4xl font-extrabold text-black">Katalog Buku</h1>
        <p class="text-xl text-black opacity-80">Discover our extensive collection of books</p>
        <div class="mt-6">
            <a href="{{ url('/') }}" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 transform hover:scale-105 transition duration-300 shadow-lg hover:shadow-xl">
                Kembali ke Halaman Utama    
            </a>
        </div>
    </header>

    <!-- Book List -->
    <div class="container mx-auto my-12 px-4">
        <h2 class="text-3xl font-semibold text-gray-700 mb-6 text-center">Daftar Buku</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
            <div class="bg-white shadow-2xl rounded-lg overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-500">
                <div class="p-6">
                    <h3 class="text-xl font-medium text-gray-800 mb-2">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-600">Penulis : {{ $book->penulis }}</p>
                    <p class="text-sm text-gray-600">Kategori : {{ $book->kategori->nama }}</p>
                    <p class="text-sm text-gray-600">Tahun Terbit : {{ $book->tahun_terbit }}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="text-gray-700 font-bold">{{ $book->stok > 0 ? 'Tersedia' : 'Stok Tidak Tersedia' }}</span>
                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Login untuk Reservasi</a>
                        @else
                            @if(Auth::user()->role == 'pegawai')
                                <form action="{{ route('books.borrow') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Pinjam Buku</button>    
                                </form>
                            @elseif(Auth::user()->role == 'user')
                                <form action="{{ route('books.reserve') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">Reservasi Buku</button>
                                </form>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
            @endforeach 
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-300 via-blue-500 to-blue-300 text-white text-center py-4 mt-12 shadow-lg">
        <p class="text-lg font-semibold text-black">&copy; 2024 Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>
