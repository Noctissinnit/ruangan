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
                'name' => 'Alvin Dimas',
                'nis' => '111111',
                'email' => 'alvin.dimas.praditya@gmail.com',
                'password' => 'password',
                'role' => 'admin',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '111111',
            ],
            [
                'name' => 'Anjing Sedboi',
                'nis' => '999999',
                'email' => 'anjingsedboi@gmail.com',
                'password' => 'password',
                'role' => 'user',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '111111',
            ],
            [
                'name' => 'Noctis Yoru',
                'nis' => '987654',
                'email' => 'ncts.yoru@gmail.com',
                'password' => 'password',
                'role' => 'admin',
                'department_id' => 1,
                'jabatan_id' => 1,
                'pin' => '111111',
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
