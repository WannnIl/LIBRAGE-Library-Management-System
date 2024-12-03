<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('kategoris')->insert([
            [
                'nama' => 'Fantasy',
                'deskripsi' => 'Buku yang berhubungan dengan dunia fantasi dan imajinasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Adventure',
                'deskripsi' => 'Buku yang berisi cerita petualangan atau perjalanan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fiction',
                'deskripsi' => 'Buku cerita fiksi yang berasal dari imajinasi penulis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dystopia',
                'deskripsi' => 'Buku yang menggambarkan dunia atau masyarakat distopia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Classic',
                'deskripsi' => 'Buku klasik yang dianggap sebagai karya besar literatur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Historical',
                'deskripsi' => 'Buku yang mengambil latar belakang sejarah nyata.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Romance',
                'deskripsi' => 'Buku yang berfokus pada cerita cinta dan hubungan romantis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Science Fiction',
                'deskripsi' => 'Buku yang menggabungkan sains dan imajinasi dalam dunia fiksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Drama',
                'deskripsi' => 'Buku yang berfokus pada konflik dan emosi manusia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Biography',
                'deskripsi' => 'Buku yang menceritakan kisah hidup seseorang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
