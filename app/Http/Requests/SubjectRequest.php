<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
        $subjectId = $this->route('subject');

        return [
            //Validasi inputan code dan name pada form mata pelajaran
            'code' => 'required|string|max:20|unique:tbl_subjects,code' . ($subjectId ? ',' . $subjectId : ''),
            'name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique'   => 'Kode sudah digunakan.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ];
    }
}
