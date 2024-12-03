<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bukus')->insert([
            [
                'judul' => 'Harry Potter and the Sorcerer\'s Stone',
                'penulis' => 'J.K. Rowling',
                'penerbit' => 'Scholastic Corporation',
                'tahun_terbit' => 1997,
                'stok' => 100,
                'kategori_id' => 1, // Fantasy
                'deskripsi' => 'Harry Potter and the Sorcerer\'s Stone adalah buku pertama dalam seri legendaris karya J.K. Rowling yang memperkenalkan pembaca pada dunia sihir yang penuh keajaiban, petualangan, dan persahabatan.',
                'image' => 'images/books/HarryPotterADSS.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'The Hobbit',
                'penulis' => 'J.R.R. Tolkien',
                'penerbit' => 'Houghton Mifflin',
                'tahun_terbit' => 1937,
                'stok' => 80,
                'kategori_id' => 2, // Adventure
                'deskripsi' => 'The Hobbit adalah petualangan seorang hobbit bernama Bilbo Baggins, yang bergabung dengan sekelompok kurcaci untuk merebut kembali Kerajaan Erebor dari naga Smaug.',
                'image' => 'images/books/TheHobbit.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'To Kill a Mockingbird',
                'penulis' => 'Harper Lee',
                'penerbit' => 'J.B. Lippincott & Co.',
                'tahun_terbit' => 1960,
                'stok' => 50,
                'kategori_id' => 3, // Fiction
                'deskripsi' => 'To Kill a Mockingbird mengeksplorasi ketidakadilan rasial dan moralitas melalui mata seorang anak bernama Scout Finch di kota kecil Alabama.',
                'image' => 'images/books/TKAM.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => '1984',
                'penulis' => 'George Orwell',
                'penerbit' => 'Secker & Warburg',
                'tahun_terbit' => 1949,
                'stok' => 60,
                'kategori_id' => 4, // Dystopia
                'deskripsi' => '1984 menggambarkan dunia totaliter penuh pengawasan dan manipulasi, di mana Winston Smith mulai mempertanyakan rezim yang menindas kebebasan individu.',
                'image' => 'images/books/1984.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'The Catcher in the Rye',
                'penulis' => 'J.D. Salinger',
                'penerbit' => 'Little, Brown and Company',
                'tahun_terbit' => 1951,
                'stok' => 45,
                'kategori_id' => 5, // Classic
                'deskripsi' => 'The Catcher in the Rye menceritakan perjalanan emosional seorang remaja bernama Holden Caulfield dalam pencarian identitas dan makna hidup.',
                'image' => 'images/books/TheCatherintheRye.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Moby-Dick',
                'penulis' => 'Herman Melville',
                'penerbit' => 'Harper & Brothers',
                'tahun_terbit' => 1851,
                'stok' => 25,
                'kategori_id' => 6, // Historical
                'deskripsi' => 'Moby-Dick adalah kisah epik tentang Kapten Ahab yang terobsesi menangkap paus putih bernama Moby Dick, yang melambangkan perjuangan manusia melawan alam.',
                'image' => 'images/books/MobyDick.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Pride and Prejudice',
                'penulis' => 'Jane Austen',
                'penerbit' => 'T. Egerton',
                'tahun_terbit' => 1813,
                'stok' => 70,
                'kategori_id' => 7, // Romance
                'deskripsi' => 'Pride and Prejudice adalah kisah cinta antara Elizabeth Bennet dan Mr. Darcy yang mengeksplorasi dinamika sosial dan kesalahpahaman.',
                'image' => 'images/books/PandP.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'War and Peace',
                'penulis' => 'Leo Tolstoy',
                'penerbit' => 'The Russian Messenger',
                'tahun_terbit' => 1869,
                'stok' => 40,
                'kategori_id' => 8, // Science Fiction
                'deskripsi' => 'War and Peace adalah epik yang mengisahkan kehidupan keluarga bangsawan Rusia selama invasi Napoleon, dengan tema cinta, perang, dan pencarian makna hidup.',
                'image' => 'images/books/WandP.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'The Great Gatsby',
                'penulis' => 'F. Scott Fitzgerald',
                'penerbit' => 'Charles Scribner\'s Sons',
                'tahun_terbit' => 1925,
                'stok' => 55,
                'kategori_id' => 9, // Drama
                'deskripsi' => 'The Great Gatsby adalah kisah tentang ambisi, cinta, dan mimpi Amerika yang pudar di era Jazz Age, melalui mata Jay Gatsby.',
                'image' => 'images/books/TheGreatGatsby.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Jane Eyre',
                'penulis' => 'Charlotte Brontë',
                'penerbit' => 'Smith, Elder & Co.',
                'tahun_terbit' => 1847,
                'stok' => 30,
                'kategori_id' => 10, // Biography
                'deskripsi' => 'Jane Eyre adalah kisah seorang wanita muda yang mandiri, yang melalui perjuangan hidup menemukan cinta dan tempatnya di dunia.',
                'image' => 'images/books/JaneEyre.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
