<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingCreate extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required',
            'tags' => 'nullable|string|regex:/^[a-zA-Z0-9,]+$/',
            'email' => 'nullable|email',
            'link' => 'nullable|url',
            'image' => 'nullable|file|max:3072|mimes:jpg,jpeg,png',
        ];
    }
}
