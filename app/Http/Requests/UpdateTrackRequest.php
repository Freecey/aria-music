<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrackRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'     => 'sometimes|string|max:255',
            'album_id'  => 'sometimes|exists:albums,id',
            'platform'  => 'sometimes|in:youtube,soundcloud,bandcamp,spotify',
            'media_url' => 'nullable|url',
            'duration'  => 'nullable|string',
            'sort'      => 'nullable|integer',
            'active'    => 'nullable|boolean',
        ];
    }
}
