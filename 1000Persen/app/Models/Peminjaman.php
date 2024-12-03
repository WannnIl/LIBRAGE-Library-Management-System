<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'peminjamans'; 

    protected $fillable = ['user_id', 'buku_id', 'tanggal_peminjaman', 'status_peminjaman'];

    protected $dates = ['tanggal_peminjaman', 'created_at', 'updated_at', 'deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function getTanggalPeminjamanAttribute($value)
    {
        return $value ? Carbon::parse($value)->locale('id') : null;
    }
}