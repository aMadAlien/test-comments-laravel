<?php

namespace App\Http\Requests;

use App\Rules\CommentValidation;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'text' => 'required|string',
            'userId' => 'required|string|exists:users,id',
            'homePage' => 'string|nullable',
            'replyTo' => 'int|exists:comments,id',
            'file' => [new CommentValidation],
        ];
    }
}
