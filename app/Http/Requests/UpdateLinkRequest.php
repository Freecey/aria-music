<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'platform' => 'sometimes|in:youtube,email,telegram,crypto,instagram,bandcamp',
            'label'    => 'sometimes|string|max:255',
            'url'      => 'sometimes|url',
            'icon_svg' => 'nullable|string',
            'sort'     => 'nullable|integer',
            'active'   => 'nullable|boolean',
        ];
    }
}
