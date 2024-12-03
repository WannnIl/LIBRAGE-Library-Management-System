<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PegawaiController extends Controller
{

    /**
     * Menampilkan dashboard untuk pengguna dengan role 'pegawai'.
     * Jika pengguna yang sedang login memiliki role 'pegawai', fungsi ini akan:
     * - Mengambil data peminjaman yang statusnya 'Menunggu Konfirmasi' beserta relasi user dan buku.
     * - Mengambil data pengembalian terbaru beserta relasi peminjaman, user, dan buku, dibatasi 5 data terbaru.
     * - Mengambil semua data buku.
     * - Mengembalikan tampilan view 'pegawai/dashboard' dengan data-data tersebut.
     *
     * Jika pengguna yang login bukan 'pegawai', fungsi ini akan mengarahkan kembali ke halaman sebelumnya.
     */
    public function dashboard()
    {
        if (Auth::user()->role == 'pegawai') {
            $pendingLoans = Peminjaman::with(['user', 'buku'])
                ->where('status_peminjaman', 'Menunggu Konfirmasi') // Update this line
                ->latest()
                ->get();
                
            $recentReturns = Pengembalian::with(['peminjaman.user', 'peminjaman.buku'])
                ->latest()
                ->take(5)
                ->get();
                
            $books = Buku::all();
            
            return view('pegawai/dashboard', compact('pendingLoans', 'recentReturns', 'books'));
        }
        return redirect()->back();
    }

    /**
     * Menampilkan daftar buku beserta kategori terkait.
     * Fungsi ini mengambil semua data buku beserta kategori terkait 
     * dan mengirimkan data tersebut ke view 'pegawai.books.index'.
     */
    public function listBooks()
    {
        $books = Buku::with('kategori')->get();
        $categories = Kategori::all();
        return view('pegawai.books.index', compact('books', 'categories'));
    }

    /**
     * Menampilkan halaman untuk membuat buku baru.
     *
     * Fungsi ini mengambil semua kategori dari model Kategori dan 
     * mengirimkannya ke tampilan 'pegawai.books.create'.
     *
     */
    public function createBook()
    {
        $categories = Kategori::all();
        return view('pegawai.books.create', compact('categories'));
    }

    /**
     * Menampilkan halaman untuk mengedit data buku.
     */
    public function editBook(Buku $book)
    {
        $categories = Kategori::all();
        return view('pegawai.books.edit', compact('book', 'categories'));
    }

    /**
     * Menyimpan data buku baru ke dalam database.
     * Redirect ke halaman daftar buku dengan pesan sukses atau kembali dengan pesan error.
     * 
     * Jika ada file gambar yang diunggah, file tersebut akan dipindahkan ke direktori 'public/images/books' 
     * dan nama file akan disimpan dalam data buku.
     *
     * Jika terjadi kesalahan selama proses penyimpanan, akan ditangkap dan dicatat dalam log, 
     * kemudian pengguna akan diarahkan kembali dengan pesan error.
     */
    public function storeBook(Request $request)
    {
        try {
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

            Log::info('Validated book data:', $validated);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/books'), $imageName);
                $validated['image'] = 'images/books/' . $imageName;
            }

            $book = Buku::create($validated);
            Log::info('Book created successfully:', ['book_id' => $book->id]);

            return redirect()->route('pegawai.books')
                ->with('success', 'Buku berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error creating book: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan buku: ' . $e->getMessage()]);
        }
    }

    public function updateBook(Request $request, Buku $book)
    {
        try {
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

            // Log sebelum update data buku
            Log::info('Updating book:', [
                'book_id' => $book->id,
                'data' => $validated
            ]);

            if ($request->hasFile('image')) {
                // Untuk menghapus gambar lama jika ada
                if ($book->image && file_exists(public_path($book->image))) {
                    unlink(public_path($book->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/books'), $imageName);
                $validated['image'] = 'images/books/' . $imageName;
            }

            $book->update($validated);

            Log::info('Book updated successfully');

            return redirect()->route('pegawai.books')
                ->with('success', 'Buku berhasil diperbarui.');
        } catch (\Exception $e) {
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

            return redirect()->route('pegawai.books')
                ->with('success', $stokToDelete >= $book->stok ? 
                    'Buku berhasil dihapus dari perpustakaan.' : 
                    'Stok buku berhasil dikurangi.');
        } catch (\Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus buku: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan daftar peminjaman yang sedang aktif.
     * Fungsi ini mengambil data peminjaman yang statusnya 'Dipinjam' beserta relasi user dan buku,
     * kemudian mengirimkan data tersebut ke view 'active-borrows'.
     */
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

}

//