<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{

    const MAX_BOOKS_BORROWED = 3;

  public function loan()
  {
    $books = Buku::all();
    $currentlyBorrowed = $this->getCurrentlyBorrowedCount();
    $userRole = Auth::user()->role;
    return view('books.loan', compact('books', 'currentlyBorrowed', 'userRole'));
  }

  private function getCurrentlyBorrowedCount()
  {
    return Peminjaman::where('user_id', Auth::id())
            ->where('status_peminjaman', 'Dipinjam')
            ->count();
  }

    public function loanBook(Request $request)
    {
        $currentlyBorrowed = $this->getCurrentlyBorrowedCount();
        
        if ($currentlyBorrowed >= self::MAX_BOOKS_BORROWED) {
            return redirect()->route('book.loan')
                    ->with('error', 'Anda telah mencapai batas maksimal peminjaman buku (' . self::MAX_BOOKS_BORROWED . ' buku)');
        }

        $book = Buku::findOrFail($request->book_id);
        
        // Check if book is available
        if ($book->stok <= 0) {
            return redirect()->route('book.loan')
                    ->with('error', 'Stok buku tidak mencukupi');
        }

        // Begin transaction
        DB::beginTransaction();
        try {
            // Decrease book stock
            $book->decrement('stok', 1);

            // Create loan record
            Peminjaman::create([
                'user_id' => Auth::id(),
                'buku_id' => $book->id,
                'tanggal_peminjaman' => Carbon::now(),
                'status_peminjaman' => 'Dipinjam',
            ]);

            DB::commit();
            return redirect()->route('book.loan')
                    ->with('success', 'Buku berhasil dipinjam');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('book.loan')
                    ->with('error', 'Gagal meminjam buku');
        }
    }

    public function loanHistory()
    {
        try {
            // Ambil semua peminjaman untuk user yang sedang login
            $peminjaman = Peminjaman::where('user_id', Auth::id())
                ->with(['buku', 'pengembalian']) // Eager load relasi
                ->orderBy('created_at', 'desc')
                ->get();

            // Debug untuk memeriksa data
            // dd($peminjaman);

            return view('books.loan_history', compact('peminjaman'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat riwayat peminjaman: ' . $e->getMessage());
        }
    }

    public function destroyHistory(Peminjaman $peminjaman)
    {
        DB::beginTransaction();
        try {
            if ($peminjaman->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized action');
            }

            if ($peminjaman->status_peminjaman !== 'Dikembalikan') {
                throw new \Exception('Hanya riwayat peminjaman yang sudah selesai yang dapat dihapus');
            }

            if ($peminjaman->pengembalian) {
                $peminjaman->pengembalian->delete();
            }

            $peminjaman->delete();

            DB::commit();
            return redirect()->route('book.loanHistory')
                ->with('success', 'Riwayat peminjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('book.loanHistory')
                ->with('error', $e->getMessage());
        }
    }

    public function destroyAllHistory()
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('user_id', Auth::id())
                ->where('status_peminjaman', 'Dikembalikan')
                ->with('pengembalian')
                ->get();

            if ($peminjaman->isEmpty()) {
                throw new \Exception('Tidak ada riwayat yang dapat dihapus');
            }

            foreach ($peminjaman as $pinjam) {
                if ($pinjam->pengembalian) {
                    $pinjam->pengembalian->delete();
                }
                $pinjam->delete();
            }

            DB::commit();
            return redirect()->route('book.loanHistory')
                ->with('success', 'Semua riwayat peminjaman berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('book.loanHistory')
                ->with('error', $e->getMessage());
        }
    }
} 