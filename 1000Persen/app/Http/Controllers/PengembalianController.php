<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function return()
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())
            ->where('status_peminjaman', 'Dipinjam')
            ->with('buku')
            ->get();
        return view('books.return', compact('peminjaman'));
    }

    /**
     * Mengajukan permintaan pengembalian buku.
     *
     * Metode ini memulai transaksi database untuk mengubah status peminjaman buku
     * menjadi 'Menunggu Konfirmasi'. Jika terjadi kesalahan selama proses, transaksi
     * akan dibatalkan dan pengguna akan diarahkan kembali dengan pesan error.
     */
    public function returnBook(Request $request)
    {
        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::where('buku_id', $request->book_id)
                ->where('user_id', Auth::id())
                ->where('status_peminjaman', 'Dipinjam')
                ->firstOrFail();

            // Update status to pending return
            $peminjaman->status_peminjaman = 'Menunggu Konfirmasi';
            $peminjaman->save();

            DB::commit();
            return redirect()->route('book.return')
                ->with('success', 'Permintaan pengembalian buku berhasil diajukan. Menunggu konfirmasi pegawai.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('book.return')
                ->with('error', 'Gagal mengajukan pengembalian buku: ' . $e->getMessage());
        }
    }

    /**
     * Mengonfirmasi pengembalian buku.
     *
     * Metode ini memeriksa peran pengguna untuk memastikan hanya admin atau pegawai
     * yang dapat mengonfirmasi pengembalian. Jika status peminjaman tidak valid,
     * akan dilemparkan pengecualian. Transaksi database dimulai untuk mengubah status
     * peminjaman menjadi 'Dikembalikan', menambah stok buku, dan menghitung denda
     * jika ada keterlambatan. Jika terjadi kesalahan selama proses, transaksi akan
     * dibatalkan dan pengguna akan diarahkan kembali dengan pesan error.
     */
    public function confirmReturn(Request $request, $loanId)
    {
        try {
            // Periksa peran pengguna
            if (!in_array(Auth::user()->role, ['admin', 'pegawai'])) {
                throw new \Exception('Unauthorized action');
            }

            $peminjaman = Peminjaman::findOrFail($loanId);
            
            // Periksa status peminjaman
            if ($peminjaman->status_peminjaman !== 'Menunggu Konfirmasi') {
                throw new \Exception('Status peminjaman tidak valid');
            }

            DB::beginTransaction();
            
            // Ubah status peminjaman menjadi 'Dikembalikan'
            $peminjaman->status_peminjaman = 'Dikembalikan';
            $peminjaman->save();

            // Tambah stok buku
            $book = Buku::findOrFail($peminjaman->buku_id);
            $book->increment('stok', 1);

            // Hitung denda keterlambatan
            $dueDate = Carbon::parse($peminjaman->tanggal_peminjaman)->addDays(7);
            $today = Carbon::now();
            $daysLate = 0;
            $denda = 0;
            
            if ($today->gt($dueDate)) {
                $daysLate = $today->diffInDays($dueDate);
                $denda = 20000;
            }

            // Buat catatan pengembalian
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tanggal_pengembalian' => $today,
                'denda' => $denda,
                'reason' => $request->reason
            ]);

            DB::commit();

            // Pesan sukses
            $message = 'Buku berhasil dikembalikan.';
            if ($denda > 0) {
                $message .= " Denda: Rp " . number_format($denda, 0, ',', '.');
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getPendingReturns()
    {
        $pendingReturns = Peminjaman::where('status_peminjaman', 'Menunggu Konfirmasi')
            ->with(['user', 'buku'])
            ->get();
        return view('pegawai.pending-returns', compact('pendingReturns'));
    }
}