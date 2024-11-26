<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(app()->isProduction()) return;
        Department::insert([
            [ 'name' => 'IT Support' ],
            [ 'name' => 'Manager' ],
            [ 'name' => 'Marketing' ]
        ]);
    }
}
