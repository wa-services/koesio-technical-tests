<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RepositorySearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'q' => 'required|string|max:256',
        ];
    }

    public function messages(): array
    {
        return [
            'q.required' => "The query parameter 'q' is missing.",
            'q.string' => "The query parameter 'q' must be a string",
            'q.max' => "The query parameter 'q' cannot be longer than 256 chars.",
        ];
    }
}
