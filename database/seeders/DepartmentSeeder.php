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
        
            Department::insert([
                [ 'name' => 'Finance' ],
                [ 'name' => 'GA HK' ],
                [ 'name' => 'HRD' ],
                [ 'name' => 'IT' ],
                [ 'name' => 'SUP MN' ],
                [ 'name' => 'YKBS' ],
            ]);
        
        
    }

}
