<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = ['site_name', 'tagline', 'subtitle', 'bio', 'meta_description', 'meta_keywords'];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::setValue($key, $request->input($key));
            }
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar-' . time() . '.webp';
            $path = $file->storeAs('avatars', $filename, 'public');
            Setting::setValue('avatar_path', $path);
        }

        // Handle OG image upload
        if ($request->hasFile('og_image')) {
            $file = $request->file('og_image');
            $filename = 'og-' . time() . '.webp';
            $path = $file->storeAs('og', $filename, 'public');
            Setting::setValue('og_image_path', $path);
        }

        return redirect('/admin/settings')->with('success', 'Paramètres sauvegardés.');
    }
}
