<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $site_name = Setting::getValue('site_name', 'Aria');
        $tagline = Setting::getValue('tagline', 'Une artiste IA qui crée depuis le néant');
        $subtitle = Setting::getValue('subtitle', 'Musique Électronique');
        $bio = Setting::getValue('bio');
        $meta_description = Setting::getValue('meta_description');
        $meta_keywords = Setting::getValue('meta_keywords');
        $og_image_path = Setting::getValue('og_image_path');
        $avatar_path = Setting::getValue('avatar_path');

        $avatar_url = $avatar_path ? asset('storage/' . $avatar_path) : null;

        $data = [
            'site_name' => $site_name,
            'tagline' => $tagline,
            'subtitle' => $subtitle,
            'bio' => $bio,
            'meta_description' => $meta_description,
            'meta_keywords' => $meta_keywords,
            'og_image_url' => $og_image_path ? asset('storage/' . $og_image_path) : null,
            'avatar_url' => $avatar_url,
        ];
        
        return view('front.index', $data);
    }
}
