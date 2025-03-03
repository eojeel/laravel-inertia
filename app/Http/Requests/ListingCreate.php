<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListingCreate extends FormRequest
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
            'link' => 'nullable|url',
            'image' => 'nullable|image|max:3072',
        ];
    }
}
