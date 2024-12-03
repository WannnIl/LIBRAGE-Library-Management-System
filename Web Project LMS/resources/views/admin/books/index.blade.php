<!-- resources/views/admin/books/index.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Buku - LIBRAGE</title>
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
              <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300 hover:underline">Peminjaman Aktif</a>
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
      <a href="{{ route($userRole . '.active-borrows') }}" class="hover:text-gray-300">Peminjaman Aktif</a>
      <a href="{{ route('profile.show') }}" class="block px-4 py-2">Profile</a>
      <form method="POST" action="{{ route('logout') }}" class="block px-4 py-2">
          @csrf
          <button type="submit" class="w-full text-left">Logout</button>
      </form>
  </div>

    <!-- Kelola Buku Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Kelola Buku</h2>
            <button onclick="openModal('addBookModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Tambah Buku</button>
            <!-- Bagian filter form -->
            <div class="flex items-center mt-4">    
                <div class="w-full">
                    <form action="{{ route($userRole . '.books.filter') }}" method="GET" class="flex items-end space-x-4">
                        <div class="relative">
                            <label for="kategori_filter" class="block text-gray-700 text-sm font-bold mb-2">Filter by Kategori:</label>
                            <select name="kategori" id="kategori_filter" 
                                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 bg-white text-gray-700">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('kategori') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-9 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.88l3.71-3.67a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <div class="relative">
                            <label for="tahun_terbit_filter" class="block text-gray-700 text-sm font-bold mb-2">Filter by Tahun Terbit:</label>
                            <input type="number" name="tahun_terbit" id="tahun_terbit_filter" 
                                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 bg-white text-gray-700"
                                value="{{ request('tahun_terbit') }}" placeholder="Tahun Terbit">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt-8">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">No</th>
                            <th class="py-2 px-4 border-b">Judul</th>
                            <th class="py-2 px-4 border-b">Penulis</th>
                            <th class="py-2 px-4 border-b">Penerbit</th>
                            <th class="py-2 px-4 border-b">Kategori</th>
                            <th class="py-2 px-4 border-b">Tahun Terbit</th>
                            <th class="py-2 px-4 border-b">Stok</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td class="py-2 px-4 border-b text-center">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->judul }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->penulis }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->penerbit }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->kategori->nama }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->tahun_terbit }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $book->stok }}</td>
                            <td class="py-2 px-4 border-b text-center">
                                <button onclick='openEditModal(@json($book))' class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Edit</button> 
                                <form action="{{ route($userRole . '.books.destroy', $book) }}" method="POST" class="inline" onsubmit="return confirmDeleteBook(event, {{ $book->stok }});">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="confirmDeleteBook({{ $book->id }}, {{ $book->stok }})" 
                                      class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                      Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Add Book Modal -->
    <div id="addBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4" id="modalTitle">Tambah Buku</h3>
                <form id="bookForm" method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700">Judul</label>
                            <input type="text" id="judul" name="judul" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                            <input type="text" id="penulis" name="penulis" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                            <input type="text" id="penerbit" name="penerbit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select id="kategori_id" name="kategori_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                            <input type="number" id="stok" name="stok" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Gambar</label>
                            <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addBookModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Book Modal -->
    <div id="editBookModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="mt-3">
              <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4" id="modalTitle">Edit Buku</h3>
              <form id="editBookForm" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="space-y-4">
                      <div>
                          <label for="edit_judul" class="block text-sm font-medium text-gray-700">Judul</label>
                          <input type="text" id="edit_judul" name="judul" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                      </div>
                      <div>
                          <label for="edit_penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                          <input type="text" id="edit_penulis" name="penulis" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                      </div>
                      <div>
                          <label for="edit_penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                          <input type="text" id="edit_penerbit" name="penerbit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                      </div>
                      <div>
                          <label for="edit_tahun_terbit" class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                          <input type="number" id="edit_tahun_terbit" name="tahun_terbit" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                      </div>
                      <div>
                          <label for="edit_kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                          <select id="edit_kategori_id" name="kategori_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                              @foreach($categories as $category)
                                  <option value="{{ $category->id }}">{{ $category->nama }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div>
                          <label for="edit_stok" class="block text-sm font-medium text-gray-700">Stok</label>
                          <input type="number" id="edit_stok" name="stok" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                      </div>
                      <div>
                          <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                          <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                      </div>
                      <div>
                          <label for="edit_image" class="block text-sm font-medium text-gray-700">Gambar</label>
                          <input type="file" id="edit_image" name="image" accept="image/*" class="mt-1 block w-full">
                          <div id="current_image" class="mt-2"></div>
                      </div>
                  </div>
                  <div class="mt-6 flex justify-end space-x-3">
                      <button type="button" onclick="closeModal('editBookModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                          Batal
                      </button>
                      <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                          Simpan Perubahan
                      </button>
                  </div>
              </form>
          </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- Notifications -->
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

    @if($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            showConfirmButton: true
        });
    </script>
    @endif

    <!-- Additional JavaScript for Modal Actions -->
    <script>
      document.getElementById('bookForm').addEventListener('submit', function() {
          Swal.fire({
              icon: 'info',
              title: 'Memproses...',
              text: 'Mohon tunggu sebentar',
              showConfirmButton: false,
              allowOutsideClick: false
          });
      });

      document.getElementById('editBookForm').addEventListener('submit', function() {
          Swal.fire({
              icon: 'info',
              title: 'Memproses...',
              text: 'Mohon tunggu sebentar',
              showConfirmButton: false,
              allowOutsideClick: false
          });
      });
    
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function confirmDeleteBook(bookId, maxStok) {

          event.preventDefault(); // Prevent any default action

          Swal.fire({
              title: 'Masukkan jumlah stok',
              input: 'number',
              inputLabel: 'Jumlah stok yang akan dihapus',
              inputValue: 1, // Set default value
              inputAttributes: {
                  min: 1,
                  max: maxStok,
                  step: 1
              },
              showCancelButton: true,
              confirmButtonText: 'Lanjutkan',
              cancelButtonText: 'Batal',
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showLoaderOnConfirm: true,
              didOpen: () => {
                  const input = Swal.getInput();
                  input.addEventListener('input', (e) => {
                      const value = parseInt(e.target.value);
                      if (value < 1 || value > maxStok) {
                          Swal.showValidationMessage(`Jumlah stok harus antara 1 dan ${maxStok}`);
                      } else {
                          Swal.resetValidationMessage();
                      }
                  });
              },
              preConfirm: (value) => {
                  const stok = parseInt(value);
                  if (!stok || isNaN(stok)) {
                      Swal.showValidationMessage('Masukkan jumlah stok');
                      return false;
                  }
                  if (stok < 1 || stok > maxStok) {
                      Swal.showValidationMessage(`Jumlah stok harus antara 1 dan ${maxStok}`);
                      return false;
                  }
                  return stok;
              }
          }).then((result) => {
              if (result.isConfirmed) {
                  // Second confirmation
                  Swal.fire({
                      title: 'Konfirmasi Hapus',
                      text: `Apakah Anda yakin ingin menghapus ${result.value} stok buku ini?`,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Ya, Hapus!',
                      cancelButtonText: 'Batal'
                  }).then((finalResult) => {
                      if (finalResult.isConfirmed) {
                          // Create form
                          const form = document.createElement('form');
                          form.method = 'POST';
                          form.action = `{{ url('/admin/books') }}/${bookId}`;
                          
                          // Add CSRF token
                          const csrfToken = document.createElement('input');
                          csrfToken.type = 'hidden';
                          csrfToken.name = '_token';
                          csrfToken.value = '{{ csrf_token() }}';
                          form.appendChild(csrfToken);
                          
                          // Add method spoofing
                          const methodField = document.createElement('input');
                          methodField.type = 'hidden';
                          methodField.name = '_method';
                          methodField.value = 'DELETE';
                          form.appendChild(methodField);
                          
                          // Add stok input
                          const stokInput = document.createElement('input');
                          stokInput.type = 'hidden';
                          stokInput.name = 'stok';
                          stokInput.value = result.value;
                          form.appendChild(stokInput);
                          
                          // Show loading state
                          Swal.fire({
                              title: 'Memproses...',
                              text: 'Mohon tunggu sebentar',
                              showConfirmButton: false,
                              allowOutsideClick: false,
                              didOpen: () => {
                                  Swal.showLoading();
                              }
                          });
                          
                          // Submit form
                          document.body.appendChild(form);
                          form.submit();
                      }
                  });
              }
          });
      }
    

    
      function openEditModal(book) {
          document.getElementById('modalTitle').innerText = 'Edit Buku';
          const form = document.getElementById('editBookForm');
          form.action = `/admin/books/${book.id}`;
          
          // Pre-fill form fields
          document.getElementById('edit_judul').value = book.judul;
          document.getElementById('edit_penulis').value = book.penulis;
          document.getElementById('edit_penerbit').value = book.penerbit;
          document.getElementById('edit_tahun_terbit').value = book.tahun_terbit;
          document.getElementById('edit_kategori_id').value = book.kategori_id;
          document.getElementById('edit_stok').value = book.stok;
          document.getElementById('edit_deskripsi').value = book.deskripsi;
          
          // Show current image if exists
          const currentImageDiv = document.getElementById('current_image');
          if (book.image) {
              currentImageDiv.innerHTML = `<img src="${book.image}" alt="${book.judul}" class="h-32 w-auto object-cover rounded-md">`;
          } else {
              currentImageDiv.innerHTML = '';
          }
          
          openModal('editBookModal');
      }

      // Update table button onclick
      document.querySelectorAll('.edit-book-btn').forEach(btn => {
          btn.onclick = (e) => {
              const book = JSON.parse(e.target.dataset.book);
              openEditModal(book);
          };
      });

      // Mobile Menu Toggle
      document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>