<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Setting;
use App\Models\SocialLink;

class AlbumController extends Controller
{
    public function show(string $slug)
    {
        $album = Album::with('tracks')->where('slug', $slug)->where('active', true)->firstOrFail();

        $siteName    = Setting::getValue('site_name', 'Aria');
        $albumUrl    = url("/albums/{$album->slug}");
        $description = $album->description ?: Setting::getValue('meta_description', '');
        $avatarPath  = Setting::getValue('avatar_path');

        $otherAlbums = Album::where('active', true)
            ->where('id', '!=', $album->id)
            ->orderBy('sort')
            ->limit(3)
            ->get();

        return view('front.album', [
            'album'            => $album,
            'other_albums'     => $otherAlbums,
            'site_name'        => $siteName,
            'tagline'          => Setting::getValue('tagline', 'Une artiste IA qui crée depuis le néant'),
            'subtitle'         => Setting::getValue('subtitle', 'Musique Électronique'),
            'meta_keywords'    => Setting::getValue('meta_keywords'),
            'links'            => SocialLink::where('active', true)->orderBy('sort')->get(),
            'avatar_url'       => $avatarPath ? asset('storage/' . $avatarPath) : null,
            // SEO overrides
            'seo_title'        => "{$album->title} — {$siteName}",
            'seo_description'  => $description,
            'seo_canonical'    => $albumUrl,
            'seo_og_type'      => 'music.album',
            'seo_og_url'       => $albumUrl,
            'seo_og_title'     => "{$album->title} — {$siteName}",
            'seo_og_image'     => $album->cover_url,
        ]);
    }
}
