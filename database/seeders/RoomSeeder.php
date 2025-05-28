<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    if (app()->isProduction()) return;

    // Tipe: home
    Room::create([
        'name' => 'Ruang Meeting Yayasan Luar',
        'description' => 'Ruang Meeting Yayasan Bagian Luar',
        'type' => 'home',
    ]);

    // Tipe: alternate
    Room::create([
        'name' => 'Ruang Meeting Mikael Dalam',
        'description' => 'Ruang Meeting Yayasan Bagian Dalam',
        'type' => 'alternate',
    ]);

    // Tipe: faber
    Room::create([
        'name' => 'Ruang Rapat Faber Atas',
        'description' => 'Ruang Rapat Lantai Atas Gedung Faber',
        'type' => 'faber',
    ]);

    Room::create([
        'name' => 'Ruang Rapat Faber Bawah',
        'description' => 'Ruang Rapat Lantai Bawah Gedung Faber',
        'type' => 'faber',
    ]);
}

}
