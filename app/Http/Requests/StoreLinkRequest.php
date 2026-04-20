<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'platform' => 'required|in:youtube,email,telegram,crypto,instagram,bandcamp',
            'label'    => 'required|string|max:255',
            'url'      => 'required|url',
            'icon_svg' => 'nullable|string',
            'sort'     => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ];
    }
}
