@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Tambah Pengguna</h2>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                   class="px-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('nama')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('alamat')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end mt-6 space-x-3">
            <a href="{{ route('admin.users') }}" 
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection