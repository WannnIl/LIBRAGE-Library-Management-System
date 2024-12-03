@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Edit Buku</h2>
        
        <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" id="judul" name="judul" 
                           value="{{ old('judul', $book->judul) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                    <input type="text" id="penulis" name="penulis" 
                           value="{{ old('penulis', $book->penulis) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('penulis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" 
                           value="{{ old('penerbit', $book->penerbit) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('penerbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" 
                           value="{{ old('tahun_terbit', $book->tahun_terbit) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('tahun_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('kategori_id', $book->kategori_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" id="stok" name="stok" 
                           value="{{ old('stok', $book->stok) }}" required min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    @error('stok')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('deskripsi', $book->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="mt-1 block w-full border-gray-300">
                    @if($book->image)
                        <div class="mt-2">
                            <img src="{{ asset($book->image) }}" alt="{{ $book->judul }}" 
                                 class="h-32 w-auto object-cover rounded-md">
                        </div>
                    @endif
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.books') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection