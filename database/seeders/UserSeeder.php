<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (app()->isProduction()) {
            $this->seedAdmin();
        } else {
            $this->seedDummyUsers();
        }
    }

    public function seedAdmin()
    {
        User::insert([
            'name' => 'Admin Booking Web',
            'nis' => '999999',
            'email' => 'bookingweb@gmail.com',
            'password' => '@!bookingweb123',
            'role' => 'admin',
            'department_id' => 1,
            'jabatan_id' => 1,
            'pin' => '123123',
        ]);
    }

    public function seedDummyUsers()
    {
        User::insert([
            [
                'name' => 'It Atmi Corp',
                'nis' => '11111',
                'email' => 'itatmicorp@gmail.com',
                'password' => 'password',
                'role' => 'admin',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '202501',
            ],
            [
                'name' => 'Wahyu Punta R',
                'nis' => '2100',
                'email' => 'punta.rajamanggala@atmi.ac.id',
                'password' => '582009',
                'role' => 'admin',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '682009',
            ],
            [
                'name' => 'Bimo Satriaji',
                'nis' => '123456',
                'email' => 'bimosatriaji6@gmail.com',
                'password' => 'password',
                'role' => 'manager',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '111111',
            ]
        ]);
    }
}
