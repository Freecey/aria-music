<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'     => 'required|string|max:255',
            'album_id'  => 'required|exists:albums,id',
            'platform'  => 'required|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration'  => 'nullable|string',
            'sort'      => 'nullable|integer',
            'active'    => 'nullable|boolean',
        ];
    }
}
