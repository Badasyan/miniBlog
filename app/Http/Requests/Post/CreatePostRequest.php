<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Post content is required.',
            'body.max' => 'Post content must not exceed 5000 characters.',
        ];
    }
}
