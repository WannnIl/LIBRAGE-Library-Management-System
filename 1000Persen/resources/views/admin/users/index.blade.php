<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - LIBRAGE</title>
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

    <!-- Kelola Pengguna Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Kelola Pengguna</h2>
            <button onclick="openModal('addUserModal')" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Tambah Pengguna
            </button>
            <div class="flex items-center mt-4">
                <div class="w-full max-w-xs">
                    <label for="role_filter" class="block text-gray-700 text-sm font-bold mb-2">Filter by Role:</label>
                    <form action="{{ route('admin.users') }}" method="GET" class="flex items-center">
                        <div class="relative w-full">
                            <select name="role" id="role_filter" 
                                class="appearance-none w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 bg-white text-gray-700">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="pegawai" {{ request('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.88l3.71-3.67a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">Filter</button>
                    </form>
                </div>
            </div>
            <div class="mt-8">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">No</th>
                            <th class="py-2 px-4 border-b">Nama</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Role</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td class="py-2 px-4 border-b text-center">{{ $index + 1 }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $user->nama }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $user->email }}</td>
                            <td class="py-2 px-4 border-b text-center">{{ $user->role }}</td>
                            <td class="py-2 px-4 border-b text-center">
                              <button onclick='openEditUserModal(@json($user))' 
                                      class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                  Edit
                              </button>
                              <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                  @csrf
                                  @method('DELETE')
                                  <button onclick="confirmDelete({{ $user->id }})" 
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

    <!-- Add/Edit User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mb-4" id="modalTitle">
                    Tambah Pengguna
                </h3>
                <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <input type="hidden" id="userId" name="userId">
                    <div class="space-y-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" id="nama" name="nama" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" id="alamat" name="alamat" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" id="password" name="password" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role" name="role" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="admin">Admin</option>
                                <option value="pegawai">Pegawai</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addUserModal')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            html: '{!! implode("<br>", $errors->all()) !!}',
            confirmButtonText: 'Ok'
        });
    </script>
    @endif


    
    <footer class="bg-blue-600 text-white py-6">
      <div class="max-w-7xl mx-auto text-center">
        <p>&copy; 2024 LIBRAGE. Semua hak cipta dilindungi.</p>
      </div>
    </footer>
    
    <!-- Form Submission Loading States -->
    <script>
      // Add User Form
      document.getElementById('userForm').addEventListener('submit', function() {
          Swal.fire({
              title: 'Memproses...',
              text: 'Mohon tunggu sebentar',
              showConfirmButton: false,
              allowOutsideClick: false,
              didOpen: () => {
                  Swal.showLoading();
              }
          });
      });
    </script>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            if (modalId === 'addUserModal') {
                document.getElementById('userForm').reset();
                const methodField = document.getElementById('method_put');
                if (methodField) methodField.remove();
                document.getElementById('modalTitle').innerText = 'Tambah Pengguna';
                document.getElementById('userForm').action = "{{ route('admin.users.store') }}";
                
                // Reset required attributes for password fields
                document.getElementById('password').required = true;
                document.getElementById('password_confirmation').required = true;
            }
        }
        
        function openEditUserModal(user) {
            console.log('Editing user:', user);
            
            document.getElementById('modalTitle').innerText = 'Edit Pengguna';
            const form = document.getElementById('userForm');
            form.action = `/admin/users/${user.id}`;
            
            // Add PUT method
            if (!document.getElementById('method_put')) {
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                methodField.id = 'method_put';
                form.appendChild(methodField);
            }
            
            // Set form values
            document.getElementById('userId').value = user.id;
            document.getElementById('nama').value = user.nama;
            document.getElementById('email').value = user.email;
            document.getElementById('alamat').value = user.alamat;
            document.getElementById('role').value = user.role;
            
            // Make password fields optional for edit
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            
            openModal('addUserModal');
        }

        function confirmDelete(userId) {
          event.preventDefault();
          
          Swal.fire({
              title: 'Konfirmasi Hapus',
              text: 'Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan!',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Ya, Hapus!',
              cancelButtonText: 'Batal'
          }).then((result) => {
              if (result.isConfirmed) {
                  const form = document.createElement('form');
                  form.method = 'POST';
                  form.action = `{{ url('/admin/users') }}/${userId}`;
                  
                  const csrfToken = document.createElement('input');
                  csrfToken.type = 'hidden';
                  csrfToken.name = '_token';
                  csrfToken.value = '{{ csrf_token() }}';
                  form.appendChild(csrfToken);
                  
                  const methodField = document.createElement('input');
                  methodField.type = 'hidden';
                  methodField.name = '_method';
                  methodField.value = 'DELETE';
                  form.appendChild(methodField);
                  

                  Swal.fire({
                      title: 'Memproses...',
                      text: 'Mohon tunggu sebentar',
                      showConfirmButton: false,
                      allowOutsideClick: false,
                      didOpen: () => {
                          Swal.showLoading();
                      }
                  });
                  
                  document.body.appendChild(form);
                  form.submit();
              }
          });
      }


        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted', this.action);
            this.submit();
        });

        // Mobile Menu Toggle
        document.getElementById('menuButton').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        });
    </script>

  </body>
</html>