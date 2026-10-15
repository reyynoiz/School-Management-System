<?php

namespace App\Imports;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    private function gender($value): string
    {
        return match (strtoupper(trim((string) $value))) {
            'L', 'LAKI-LAKI' => 'L',
            'P', 'PEREMPUAN' => 'P',
            default => '',
        };
    }

    public function prepareForValidation($data, $index)
    {
        $data['nis'] = trim((string) ($data['nis'] ?? ''));
        $data['jenis_kelamin'] = $this->gender($data['jenis_kelamin'] ?? '');

        return $data;
    }

    public function rules(): array
    {
        return [
            'nis'           => ['required', 'max:30', 'distinct', 'unique:tbl_students,nis'],
            'nama'          => ['required', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas'         => ['required', Rule::exists('tbl_classes', 'name')->where('archived', 0)],
            'username'      => ['nullable', 'unique:tbl_users,username'],
            'email'         => ['nullable', 'email', 'unique:tbl_users,email'],
        ];
    }

    public function customValidationAttributes()
    {
        return ['jenis_kelamin' => 'jenis kelamin'];
    }

    public function model(array $row)
    {
        $classId = SchoolClass::where('name', $row['kelas'])->value('id');

        $userId = null;
        if (! empty($row['username']) && ! empty($row['email'])) {
            $userId = User::create([
                'name'     => $row['nama'],
                'username' => $row['username'],
                'email'    => $row['email'],
                'password' => Hash::make('password123'),
                'role'     => 'student',
                'status'   => 1,
            ])->id;
        }

        return new Student([
            'user_id'  => $userId,
            'class_id' => $classId,
            'nis'      => trim((string) $row['nis']),
            'name'     => $row['nama'],
            'gender'   => $this->gender($row['jenis_kelamin']),
            'archived' => 0,
        ]);
    }
}