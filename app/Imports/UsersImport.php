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
                $department = empty($row[6]) ? null : (is_numeric($row[6]) ? $row[6] : Department::whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($row[6])) . '%'])->first()->id);
                $jabatan = is_numeric($row[7]) ? $row[7] : Jabatan::whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($row[7])) . '%'])->first()->id;
                User::insert([
                    'name' => $row[0],
                    'email' => $row[1],
                    'nis' => $row[2],
                    'pin' => $row[3],
                    'password' => $row[4],
                    'role' => $row[5],
                    'department_id' => $department,
                    'jabatan_id' => $jabatan,
                ]);
            } catch (\Exception $e) {
                info($e->getMessage());
            }
        }
    }
}
