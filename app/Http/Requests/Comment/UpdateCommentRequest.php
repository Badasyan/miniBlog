<?php

namespace App\Http\Requests\Comment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $comment = $this->route('comment');
        return auth()->check() && auth()->id() === $comment->user_id;
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
            'body.required' => 'Comment content is required.',
            'body.max' => 'Comment content must not exceed 5000 characters.',
        ];
    }
}
