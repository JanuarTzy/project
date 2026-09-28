<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true agar request tidak menghasilkan response 403 Forbidden
    }

    /**
     * Aturan validasi yang diterapkan pada request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255', 'unique:categories,name'],
            // 'gt:0' (greater than 0) memastikan nilai angka harus lebih besar dari 0 (tidak boleh 0 atau minus)
            'price' => ['required', 'numeric', 'gt:0'],
        ];
    }

    /**
     * Kustomisasi pesan error (opsional).
     */
    public function messages(): array
    {
        return [
            'name.required'  => 'Nama kategori wajib diisi.',
            'name.unique'    => 'Nama kategori sudah terdaftar.',
            'price.required' => 'Harga wajib diisi.',
            'price.numeric'  => 'Harga harus berupa angka.',
            'price.gt'       => 'Harga harus lebih besar dari 0.',
        ];
    }
}