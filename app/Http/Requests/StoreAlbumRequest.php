<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'year'        => 'required|integer|min:2000|max:2100',
            'platform'    => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url'   => 'nullable|url',
            'description' => 'nullable|string',
            'cover'       => 'nullable|image|max:4096',
            'sort'        => 'nullable|integer',
            'active'      => 'nullable|boolean',
        ];
    }
}
