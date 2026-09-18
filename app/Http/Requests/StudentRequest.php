<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $studentId = $this->route('student');
        return [
            //
            'nis'      => 'required|string|max:30|unique:tbl_students,nis' . ($studentId ? ',' . $studentId : ''),
            'name'     => 'required|string|max:255',
            'gender'   => 'required|in:L,P',
            'class_id' => 'nullable|exists:tbl_classes,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nis.required'    => 'NIS wajib diisi.',
            'nis.unique'      => 'NIS sudah digunakan.',
            'name.required'   => 'Nama wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
        ];
    }
}
