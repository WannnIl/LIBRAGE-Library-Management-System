<?php
// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'buku_id' => 'required|exists:bukus,id',
                'rating' => 'required|integer|min:1|max:5',
                'komentar' => 'required|string|max:500'
            ]);

            $review = Review::create([
                'user_id' => Auth::id(),
                'buku_id' => $validated['buku_id'],
                'rating' => $validated['rating'],
                'komentar' => $validated['komentar'],
                'tanggal_ulasan' => now()
            ]);

            if($review) {
                return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan');
            }

            return redirect()->back()->with('error', 'Gagal menambahkan ulasan')->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}