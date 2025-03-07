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
        Room::create(['name' => 'Ruang Meeting Yayasan', 'description' => 'Ruang Meeting Yayasan Bagian Luar']);
        Room::create(['name' => 'Ruang Meeting Yayasan', 'description' => 'Ruang Meeting Yayasan Bagian Dalam', 'type' => 'alternate']);
    }
}
