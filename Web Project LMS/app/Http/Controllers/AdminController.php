<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Menampilkan dashboard untuk pengguna dengan role 'admin'.
        $userRole = Auth::user()->role;

        // Mengambil data peminjaman yang statusnya 'Menunggu Konfirmasi' beserta relasi user dan buku
        $pendingLoans = Peminjaman::with(['user', 'buku'])
            ->where('status_peminjaman', 'Menunggu Konfirmasi')
            ->latest()
            ->get();
            
        // Mengambil data peminjaman terbaru  
        $latestPeminjaman = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        $latestPengembalian = Pengembalian::with(['peminjaman.user', 'peminjaman.buku'])
            ->latest()
            ->take(5)
            ->get();

        // Menampilkan jumlah total pengguna, buku, peminjaman aktif, dan kategori
        $totalUsers = User::count();
        $totalBooks = Buku::count(); 
        $activeLoans = Peminjaman::where('status_peminjaman', 'Dipinjam')->count();
        $totalCategories = Kategori::count();

        return view('admin.dashboard', compact(
            'userRole',
            'pendingLoans',
            'latestPeminjaman',
            'latestPengembalian', 
            'totalUsers',
            'totalBooks',
            'activeLoans',
            'totalCategories'
        ));
    }

    /**
     * Menampilkan daftar pengguna berdasarkan permintaan.
     *
     * Fungsi ini membuat query untuk mengambil data pengguna dari model User.
     * Jika parameter 'role' ada dalam permintaan, query akan difilter berdasarkan peran pengguna.
     * Data pengguna yang diambil kemudian dikirim ke tampilan 'admin.users.index' bersama dengan peran pengguna yang sedang login.
     */
    public function listUsers(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $users = $query->get();
        $userRole = Auth::user()->role;
        
        return view('admin.users.index', compact('users', 'userRole'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     *
     * Fungsi ini mengarahkan ke tampilan 'admin.users.create' yang berisi form untuk menambahkan pengguna baru.
     */
    public function createUser()
    {
        return view('admin.users.create');
    }


    // Update storeUser method in AdminController.php to add debug logging
    /**
     * Menyimpan pengguna baru ke dalam database.
     *
     * Fungsi ini menerima permintaan untuk membuat pengguna baru, memvalidasi data yang diterima,
     * dan menyimpan data pengguna ke dalam database. Jika terjadi kesalahan selama proses penyimpanan,
     * fungsi ini akan menangkap pengecualian dan mengembalikan pesan kesalahan.
     */
    public function storeUser(Request $request)
    {
        // Log permintaan pembuatan pengguna baru, kecuali password dan konfirmasi password
        Log::info('Received user creation request:', $request->except('password', 'password_confirmation'));

        // Validasi data yang diterima
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,pegawai,mahasiswa',
        ]);

        try {
            // Membuat pengguna baru dengan data yang telah divalidasi
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'alamat' => $validated['alamat'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Mengarahkan kembali ke halaman daftar pengguna dengan pesan sukses
            return redirect()->route('admin.users')
                ->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Log kesalahan jika terjadi pengecualian
            Log::error('Error creating user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Mengarahkan kembali dengan pesan kesalahan dan data input kecuali password dan konfirmasi password
            return redirect()->back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()]);
        }
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'required|string|max:255',
            'role' => 'required|string|in:admin,pegawai,mahasiswa',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            // Update data user
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'role' => $request->role,
            ];

            // Jika password diisi, update password
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            Log::info('Updating user:', ['user_id' => $user->id, 'data' => $userData]);

            $user->update($userData);

            Log::info('User updated successfully');

            return redirect()->route('admin.users')
                ->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui user: ' . $e->getMessage()]);
        }
    }


    public function destroyUser(User $user)
    {
        try {
            // Debug logging
            Log::info('Starting user deletion', [
                'user_id' => $user->id,
                'method' => request()->method(),
                'url' => request()->fullUrl()
            ]);

            // Gunakan forceDelete untuk benar-benar menghapus dari database
            $user->forceDelete(); // Ganti dari delete() ke forceDelete()

            Log::info('User deleted successfully', ['user_id' => $user->id]);

            return redirect()->route('admin.users')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('admin.users')
                ->withErrors(['error' => 'Gagal menghapus pengguna']);
        }
    }

    public function listBooks()
    {
        $books = Buku::with('kategori')->get();
        $categories = Kategori::all();
        return view('admin.books.index', compact('books', 'categories'));
    }

    public function createBook()
    {
        $categories = Kategori::all();
        return view('admin.books.create', compact('categories'));
    }

    public function editBook(Buku $book)
    {
        $categories = Kategori::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Menyimpan buku baru ke dalam database.
     *
     * Fungsi ini menerima permintaan untuk membuat buku baru, memvalidasi data yang diterima,
     * dan menyimpan data buku ke dalam database. Jika terjadi kesalahan selama proses penyimpanan,
     * fungsi ini akan menangkap pengecualian dan mengembalikan pesan kesalahan.
     */
    public function storeBook(Request $request)
    {
        try {
            // Validasi data yang diterima
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
                'kategori_id' => 'required|exists:kategoris,id',
                'stok' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Log data buku yang telah divalidasi
            Log::info('Validated book data:', $validated);

            // Jika ada file gambar yang diunggah, proses dan simpan gambar
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/books'), $imageName);
                $validated['image'] = 'images/books/' . $imageName;
            }

            // Membuat buku baru dengan data yang telah divalidasi
            $book = Buku::create($validated);
            Log::info('Book created successfully:', ['book_id' => $book->id]);

            // Mengarahkan kembali ke halaman daftar buku dengan pesan sukses
            return redirect()->route('admin.books')
                ->with('success', 'Buku berhasil ditambahkan.');

        } catch (\Exception $e) {
            // Log kesalahan jika terjadi pengecualian
            Log::error('Error creating book: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan buku: ' . $e->getMessage()]);
        }
    }

    /**
     * Memperbarui data buku yang ada di database.
     *
     * Fungsi ini menerima permintaan untuk memperbarui data buku, memvalidasi data yang diterima,
     * dan memperbarui data buku di dalam database. Jika terjadi kesalahan selama proses pembaruan,
     * fungsi ini akan menangkap pengecualian dan mengembalikan pesan kesalahan.
     */
    public function updateBook(Request $request, Buku $book)
    {
        try {
            // Validasi data yang diterima
            $validated = $request->validate([
                'judul' => 'required|string|max:255',
                'penulis' => 'required|string|max:255',
                'penerbit' => 'required|string|max:255',
                'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
                'kategori_id' => 'required|exists:kategoris,id',
                'stok' => 'required|integer|min:0',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Log sebelum pembaruan
            Log::info('Updating book:', [
                'book_id' => $book->id,
                'data' => $validated
            ]);

            // Menangani unggahan gambar jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($book->image && file_exists(public_path($book->image))) {
                    unlink(public_path($book->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/books'), $imageName);
                $validated['image'] = 'images/books/' . $imageName;
            }

            // Melakukan pembaruan data buku
            $book->update($validated);

            Log::info('Book updated successfully');

            // Mengarahkan kembali ke halaman daftar buku dengan pesan sukses
            return redirect()->route('admin.books')
                ->with('success', 'Buku berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log kesalahan jika terjadi pengecualian
            Log::error('Error updating book: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui buku: ' . $e->getMessage()]);
        }
    }

    public function destroyBook(Request $request, Buku $book)
    {
        try {
            $stokToDelete = $request->input('stok');
            
            if (!$stokToDelete || $stokToDelete < 1 || $stokToDelete > $book->stok) {
                return redirect()->back()->withErrors(['error' => 'Jumlah stok tidak valid']);
            }

            if ($stokToDelete >= $book->stok) {
                // Delete image if exists
                if ($book->image && file_exists(public_path($book->image))) {
                    unlink(public_path($book->image));
                }
                $book->delete();
            } else {
                $book->stok -= $stokToDelete;
                $book->save();
            }

            return redirect()->route('admin.books')
                ->with('success', $stokToDelete >= $book->stok ? 
                    'Buku berhasil dihapus dari perpustakaan.' : 
                    'Stok buku berhasil dikurangi.');
        } catch (\Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus buku: ' . $e->getMessage()]);
        }
    }

    public function getActiveBorrows()
    {
        $activeLoans = Peminjaman::with(['user', 'buku'])
            ->where('status_peminjaman', 'Dipinjam')
            ->orderBy('tanggal_peminjaman', 'desc')
            ->get();
            
        return view('active-borrows', [
            'activeLoans' => $activeLoans,
            'userRole' => Auth::user()->role
        ]);
    }

    public function filter(Request $request)
    {
        $query = Buku::query();
        
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('tahun_terbit') && $request->tahun_terbit != '') {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }
        
        $books = $query->with('kategori')->get();
        $categories = Kategori::all();
        $userRole = Auth::user()->role; // Get user role from Auth
        
        return view($userRole . '.books.index', compact('books', 'categories', 'userRole'));
    }
}