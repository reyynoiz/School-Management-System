<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $teacherId = $this->route('teacher');

        return [
            //
            'nip'        => 'required|string|max:30|unique:tbl_teachers,nip' . ($teacherId ? ',' . $teacherId : ''),
            'name'       => 'required|string|max:255',
            'gender'     => 'required|in:L,P',
            'subject_id' => 'nullable|exists:tbl_subjects,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nip.required'    => 'NIP wajib diisi.',
            'nip.unique'      => 'NIP sudah digunakan.',
            'name.required'   => 'Nama wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
        ];
    }
}
