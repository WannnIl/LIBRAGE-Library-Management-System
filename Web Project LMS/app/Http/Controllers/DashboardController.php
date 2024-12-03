<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function mahasiswa()
    {
        if (Auth::user()->role == 'mahasiswa') {
            $books = Buku::all(); // Mengambil semua data buku
            return view('dashboard.mahasiswa', compact('books'));
        }

        return redirect()->back();
    }
}