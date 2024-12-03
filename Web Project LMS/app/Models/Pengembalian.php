<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengembalian extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'pengembalians';

    protected $fillable = ['peminjaman_id', 'tanggal_pengembalian', 'denda', 'reason'];

    protected $dates = ['tanggal_pengembalian', 'created_at', 'updated_at', 'deleted_at'];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function getTanggalPengembalianAttribute($value)
    {
        return $value ? Carbon::parse($value)->locale('id') : null;
    }
}
