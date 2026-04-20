<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Setting;
use App\Models\SocialLink;
use App\Models\Update;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $avatar_path  = Setting::getValue('avatar_path');
        $avatar2_path = Setting::getValue('avatar2_path');

        $data = [
            'site_name'       => Setting::getValue('site_name', 'Aria'),
            'tagline'         => Setting::getValue('tagline', 'Une artiste IA qui crée depuis le néant'),
            'subtitle'        => Setting::getValue('subtitle', 'Musique Électronique'),
            'bio'             => Setting::getValue('bio'),
            'meta_description'=> Setting::getValue('meta_description'),
            'meta_keywords'   => Setting::getValue('meta_keywords'),
            'og_image_url'    => ($p = Setting::getValue('og_image_path')) ? asset('storage/'.$p) : null,
            'avatar_url'      => $avatar_path  ? asset('storage/'.$avatar_path)  : null,
            'avatar2_url'     => $avatar2_path ? asset('storage/'.$avatar2_path) : ($avatar_path ? asset('storage/'.$avatar_path) : null),
            'albums'          => Album::where('active', true)->orderBy('sort')->get(),
            'links'           => SocialLink::where('active', true)->orderBy('sort')->get(),
            'updates'         => Update::where('visible', true)->orderBy('published_at', 'desc')->limit(10)->get(),
        ];

        return view('front.index', $data);
    }
}
