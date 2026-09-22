<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRequest extends FormRequest
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
        return [
            //Validasi inputan name dan level pada form kelas
            'name'  => 'required|string|max:50',
            'level' => 'required|string|max:20',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'  => 'Nama kelas wajib diisi.',
            'level.required' => 'Tingkat wajib diisi.',
        ];
    }
}
