<?php

namespace App\Http\Requests\Comment;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
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
        $rules = [
            'body' => 'required|string|max:5000',
            'commentable_id' => 'sometimes|required|integer',
            'commentable_type' => 'sometimes|required|in:post,comment',
        ];

        if ($this->has('commentable_type') && $this->has('commentable_id')) {
            $type = $this->input('commentable_type');

            if ($type === 'post') {
                $rules['commentable_id'] .= '|exists:posts,id';
            } elseif ($type === 'comment') {
                $rules['commentable_id'] .= '|exists:comments,id';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Comment content is required.',
            'body.max' => 'Comment content must not exceed 5000 characters.',
            'commentable_id.required' => 'Commentable ID is required.',
            'commentable_id.integer' => 'Commentable ID must be an integer.',
            'commentable_id.exists' => 'The specified record does not exist.',
            'commentable_type.required' => 'Commentable type is required.',
            'commentable_type.in' => 'Invalid commentable type. Must be post or comment.',
        ];
    }
}
