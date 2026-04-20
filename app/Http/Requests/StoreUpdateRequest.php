<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'body'         => 'required|string',
            'visible'      => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
