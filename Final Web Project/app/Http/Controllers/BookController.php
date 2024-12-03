<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Menampilkan daftar semua buku di katalog
     */
    public function index()
    {
        $books = Buku::with('kategori')->get(); // Mengambil semua data buku beserta kategori
        return view('welcome', compact('books')); // Mengirim data buku ke view katalog
    }

    /**
     * Menampilkan detail buku berdasarkan ID
     */
    public function show($id)
    {
        $book = Buku::findOrFail($id); // Mengambil buku berdasarkan ID
        return view('books.show', compact('book')); // Mengirim data buku ke view detail buku
    }

    // Halaman untuk mengelola buku
    public function bookManagement()
    {
        $books = Buku::all(); // Ambil semua buku dari database
        return view('admin.book-management', compact('books'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $books = Buku::where('judul', 'LIKE', "%{$query}%")
                    ->orWhere('penulis', 'LIKE', "%{$query}%")
                    ->get();
        
        if($request->ajax()) {
            return response()->json([
                'books' => $books
            ]);
        }

        return view('welcome', compact('books'));
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
        $userRole = Auth::check() ? Auth::user()->role : 'guest';
        
        // Return the correct view based on user role
        return view($userRole . '.books.index', compact('books', 'categories', 'userRole'));
    }
}
