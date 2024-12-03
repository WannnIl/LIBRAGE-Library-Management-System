<?php

use App\Models\Buku;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

Route::get('/', [BookController::class, 'index'])->name('welcome');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute yang memerlukan otentikasi
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');

    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');

    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');

    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/admin/books', [AdminController::class, 'listBooks'])->name('admin.books');
    Route::get('/admin/books/create', [AdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/admin/books', [AdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/admin/books/{book}/edit', [AdminController::class, 'editBook'])->name('admin.books.edit');
    Route::put('/admin/books/{book}', [AdminController::class, 'updateBook'])->name('admin.books.update');
    Route::delete('/admin/books/{book}', [AdminController::class, 'destroyBook'])->name('admin.books.destroy');

    Route::post('/admin/pengembalian/confirm/{loan}', [PengembalianController::class, 'confirmReturn'])
        ->name('admin.confirm.return');
    
    Route::get('/admin/active-borrows', [AdminController::class, 'getActiveBorrows'])->name('admin.active-borrows');

    Route::get('/admin/books/filter', [BookController::class, 'filter'])->name('admin.books.filter');
});


Route::middleware(['auth', 'pegawaiMiddleware'])->group(function () {
    Route::get('pegawai/dashboard', [PegawaiController::class, 'dashboard'])->name('pegawai.dashboard');
    Route::post('/confirm-return/{loan}', [PengembalianController::class, 'confirmReturn'])->name('confirm.return');

    Route::get('/pegawai/books', [PegawaiController::class, 'listBooks'])->name('pegawai.books');
    Route::get('/pegawai/books/create', [PegawaiController::class, 'createBook'])->name('pegawai.books.create');
    Route::post('/pegawai/books', [PegawaiController::class, 'storeBook'])->name('pegawai.books.store');
    Route::get('/pegawai/books/{book}/edit', [PegawaiController::class, 'editBook'])->name('pegawai.books.edit');
    Route::put('/pegawai/books/{book}', [PegawaiController::class, 'updateBook'])->name('pegawai.books.update');
    Route::delete('/pegawai/books/{book}', [PegawaiController::class, 'destroyBook'])->name('pegawai.books.destroy');

    Route::get('/pegawai/active-borrows', [PegawaiController::class, 'getActiveBorrows'])->name('pegawai.active-borrows');

    Route::get('/pegawai/books/filter', [BookController::class, 'filter'])->name('pegawai.books.filter');
});


Route::middleware(['auth', 'userMiddleware'])->group(function () {
    Route::get('dashboard/mahasiswa', [DashboardController::class, 'mahasiswa'])->name('mahasiswa.dashboard');
});



Route::middleware('auth')->group(function () {
    Route::get('/loan', [PeminjamanController::class, 'loan'])->name('book.loan');
    Route::post('/loan', [PeminjamanController::class, 'loanBook'])->name('book.loanBook');

    Route::get('/loan/history', [PeminjamanController::class, 'loanHistory'])->name('book.loanHistory');

    
    Route::delete('/loan-history/destroy-all', [PeminjamanController::class, 'destroyAllHistory'])
        ->name('book.destroyAllHistory');

    Route::delete('/loan-history/{peminjaman}', [PeminjamanController::class, 'destroyHistory'])
        ->name('book.destroyHistory');

        
    Route::get('/return', [PengembalianController::class, 'return'])->name('book.return');
    Route::post('/return', [PengembalianController::class, 'returnBook'])->name('book.returnBook');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/catalog', [BookController::class, 'index'])->name('catalog');

Route::get('/book/{id}', [BookController::class, 'show'])->name('book.show');

Route::get('/search', [BookController::class, 'search'])->name('books.search');

require __DIR__.'/auth.php';