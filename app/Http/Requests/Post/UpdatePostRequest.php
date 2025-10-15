<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');
        return auth()->check() && auth()->id() === $post->user_id;
    }

    /**
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'string|max:5000',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'body.max' => 'Post content must not exceed 5000 characters.',
            'is_active.boolean' => 'Post status must be true or false.',
        ];
    }
}
