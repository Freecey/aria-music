<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'body'         => 'sometimes|string',
            'visible'      => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
