<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlbumRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'year'        => 'sometimes|integer|min:2000|max:2100',
            'platform'    => 'sometimes|in:youtube,soundcloud,bandcamp,spotify',
            'media_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'cover'       => 'nullable|image|max:4096',
            'sort'        => 'nullable|integer',
            'active'      => 'nullable|boolean',
        ];
    }
}
