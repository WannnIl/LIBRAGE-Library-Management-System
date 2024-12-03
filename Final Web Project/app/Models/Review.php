<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{

    protected $fillable = ['user_id', 'buku_id', 'rating', 'komentar', 'tanggal_ulasan'];
    protected $dates = ['tanggal_ulasan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
