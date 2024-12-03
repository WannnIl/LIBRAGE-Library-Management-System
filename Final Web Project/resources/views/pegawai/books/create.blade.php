@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Tambah Buku</h2>
        
        <form method="POST" action="{{ route('pegawai.books.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                    <input type="text" id="penulis" name="penulis" value="{{ old('penulis') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('penulis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                    <input type="text" id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('penerbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('tahun_terbit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('kategori_id') == $category->id ? 'selected' : '' }}>
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
                    <input type="number" id="stok" name="stok" value="{{ old('stok') }}" required min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('stok')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="mt-1 block w-full border-gray-300">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end mt-6 space-x-3">
                    <a href="{{ route('pegawai.books') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection