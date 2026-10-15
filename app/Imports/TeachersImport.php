<?php

namespace App\Imports;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeachersImport implements ToModel, WithHeadingRow, WithValidation
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
        $subject = trim((string) ($data['mata_pelajaran'] ?? ''));

        $data['nip'] = trim((string) ($data['nip'] ?? ''));
        $data['jenis_kelamin'] = $this->gender($data['jenis_kelamin'] ?? '');
        $data['mata_pelajaran'] = $subject === '' ? null : $subject;

        return $data;
    }

    public function rules(): array
    {
        return [
            'nip'            => ['required', 'max:30', 'unique:tbl_teachers,nip'],
            'nama'           => ['required', 'max:255'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'mata_pelajaran' => ['nullable', Rule::exists('tbl_subjects', 'name')->where('archived', 0)],
            'username'       => ['nullable', 'unique:tbl_users,username'],
            'email'          => ['nullable', 'email', 'unique:tbl_users,email'],
        ];
    }

    public function customValidationAttributes()
    {
        return [
            'jenis_kelamin'  => 'jenis kelamin',
            'mata_pelajaran' => 'mata pelajaran',
        ];
    }

    public function model(array $row)
    {
        $subjectName = trim((string) ($row['mata_pelajaran'] ?? ''));
        $subjectId = $subjectName !== '' ? Subject::where('name', $subjectName)->value('id') : null;

        $userId = null;
        if (! empty($row['username']) && ! empty($row['email'])) {
            $userId = User::create([
                'name'     => $row['nama'],
                'username' => $row['username'],
                'email'    => $row['email'],
                'password' => Hash::make('password123'),
                'role'     => 'teacher',
                'status'   => 1,
            ])->id;
        }

        return new Teacher([
            'user_id'    => $userId,
            'subject_id' => $subjectId,
            'nip'        => trim((string) $row['nip']),
            'name'       => $row['nama'],
            'gender'     => $this->gender($row['jenis_kelamin']),
            'archived'   => 0,
        ]);
    }
}