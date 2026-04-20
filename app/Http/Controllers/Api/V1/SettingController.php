<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $keys = [
            'site_name', 'tagline', 'subtitle', 'bio',
            'avatar_path', 'meta_description', 'meta_keywords', 'og_image_path'
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::getValue($key);
        }

        return response()->json(['data' => $settings]);
    }

    public function patchBio(Request $request): JsonResponse
    {
        $data = $request->validate([
            'value' => 'required|string'
        ]);

        Setting::setValue('bio', $data['value']);

        return response()->json([
            'data' => ['bio' => Setting::getValue('bio')]
        ]);
    }

    public function patchSettings(Request $request): JsonResponse
    {
        $allowedKeys = [
            'site_name', 'tagline', 'subtitle', 'bio',
            'avatar_path', 'meta_description', 'meta_keywords', 'og_image_path'
        ];

        $data = $request->validate([
            'site_name' => 'sometimes|string|max:255',
            'tagline' => 'sometimes|string|max:500',
            'subtitle' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string',
            'avatar_path' => 'sometimes|string',
            'meta_description' => 'sometimes|string|max:500',
            'meta_keywords' => 'sometimes|string|max:500',
            'og_image_path' => 'sometimes|string',
        ]);

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        $settings = [];
        foreach ($allowedKeys as $key) {
            $settings[$key] = Setting::getValue($key);
        }

        return response()->json(['data' => $settings]);
    }
}
