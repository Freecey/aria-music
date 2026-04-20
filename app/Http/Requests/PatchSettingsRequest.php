<?php

namespace App\Http\Requests;

use App\Services\SettingsService;
use Illuminate\Foundation\Http\FormRequest;

class PatchSettingsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'site_name'        => 'sometimes|string|max:255',
            'tagline'          => 'sometimes|string|max:500',
            'subtitle'         => 'sometimes|string|max:255',
            'bio'              => 'sometimes|string',
            'avatar_path'      => 'sometimes|string',
            'meta_description' => 'sometimes|string|max:500',
            'meta_keywords'    => 'sometimes|string|max:500',
            'og_image_path'    => 'sometimes|string',
        ];
    }
}
