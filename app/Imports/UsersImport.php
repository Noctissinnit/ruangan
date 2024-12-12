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
                User::insert([
                    'name' => $row[0],
                    'email' => $row[1],
                    'nis' => $row[2],
                    'password' => $row[3],
                    'pin' => $row[3],
                    'role' => $row[4],
                    'department_id' => is_numeric($row[5]) ? $row[5] : Department::where('name', $row[5])->first()->id,
                    'jabatan_id' => is_numeric($row[6]) ? $row[5] : Jabatan::where('name', $row[5])->first()->id,
                ]);
            } catch (\Exception $e) {
                info($e->getMessage());
            }
        }
    }
}
