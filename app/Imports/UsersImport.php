<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'email';
    }
    public function collection(Collection $rows)
    {
        foreach ($rows as $i => $row) {
            if ($i === 0) continue;
            try {
                $department = $row[6];
                $jabatan = $row[7];
                User::insert([
                    'name' => $row[0],
                    'email' => $row[1],
                    'nis' => $row[2],
                    'pin' => $row[3],
                    'password' => $row[4],
                    'role' => $row[5],
                    'department_id' => is_numeric($department) ? $department : Department::whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($department)) . '%'])->first()->id,
                    'jabatan_id' => is_numeric($jabatan) ? $jabatan : Jabatan::whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($jabatan)) . '%'])->first()->id,
                ]);
    
    

            } catch (\Exception $e) {
                info($e->getMessage());
            }
        }
    }
}
