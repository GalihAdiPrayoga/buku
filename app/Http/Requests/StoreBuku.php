<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuku extends FormRequest
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
            'nama'=> 'required|string|unique:bukus|max:255',
            'penerbit_id'=> 'required|exists:penerbits,id',
            'stok'=> 'required|integer|min:1',
            'pengarang'=> 'required|string|max:25', 
            'kategori_id' => 'required|exists:kategoris,id',   
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ];
    }
}
