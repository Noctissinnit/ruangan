<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->isProduction()) return;
        Jabatan::insert([
            [ 'name' => 'Programmer' ],
            [ 'name' => 'Dosen' ],
            [ 'name' => 'Karyawan' ],
            [ 'name' => 'Security' ],
            [ 'name' => 'Manager' ],
        ]);
    }
}
