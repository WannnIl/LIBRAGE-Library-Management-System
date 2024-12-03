<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory, SoftDeletes;

    // Kolom yang dapat diisi secara mass assignment
    protected $fillable = ['judul', 'penulis', 'penerbit', 'tahun_terbit', 'stok', 'kategori_id', 'image', 'deskripsi'];


    // Relasi dengan model Kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi dengan model Peminjaman
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi dengan model Reservasi
    public function reservasi(): HasMany
    {
        return $this->hasMany(Reservasi::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->review()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->review()->count();
    }
}
