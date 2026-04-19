<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SiteController extends Controller
{
    public function index(): JsonResponse
    {
        $keys = [
            'site_name', 'tagline', 'subtitle', 'bio',
            'avatar_path', 'meta_description', 'meta_keywords', 'og_image_path'
        ];

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = Setting::getValue($key);
        }

        // Add avatar/og full URLs
        if ($data['avatar_path']) {
            $data['avatar_url'] = asset('storage/' . $data['avatar_path']);
        }
        if ($data['og_image_path']) {
            $data['og_image_url'] = asset('storage/' . $data['og_image_path']);
        }

        return response()->json(['data' => $data]);
    }
}
