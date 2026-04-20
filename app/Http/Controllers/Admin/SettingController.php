<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\MediaService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'        => 'nullable|string|max:255',
            'tagline'          => 'nullable|string|max:255',
            'subtitle'         => 'nullable|string|max:255',
            'bio'              => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords'    => 'nullable|string|max:255',
            'avatar'           => 'nullable|image|max:4096',
            'avatar2'          => 'nullable|image|max:4096',
            'og_image'         => 'nullable|image|max:4096',
        ]);

        $keys = ['site_name', 'tagline', 'subtitle', 'bio', 'meta_description', 'meta_keywords'];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key));
            }
        }

        // Avatar → WebP conversion
        if ($request->hasFile('avatar')) {
            $avatarPath = $this->media->processAndStoreImage($request, 'avatar', 'avatars', 'avatar');
            if ($avatarPath) {
                Setting::setValue('avatar_path', $avatarPath);
            }
        }

        // Avatar 2 → WebP conversion
        if ($request->hasFile('avatar2')) {
            $avatar2Path = $this->media->processAndStoreImage($request, 'avatar2', 'avatars', 'avatar2');
            if ($avatar2Path) {
                Setting::setValue('avatar2_path', $avatar2Path);
            }
        }

        // OG image → WebP conversion
        if ($request->hasFile('og_image')) {
            $ogPath = $this->media->processAndStoreImage($request, 'og_image', 'og', 'og');
            if ($ogPath) {
                Setting::setValue('og_image_path', $ogPath);
            }
        }

        return redirect('/admin/settings')->with('success', 'Paramètres sauvegardés.');
    }
}
