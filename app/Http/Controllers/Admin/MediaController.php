<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $files = collect(Storage::disk('public')->files('covers'))
            ->merge(Storage::disk('public')->files('avatars'))
            ->merge(Storage::disk('public')->files('og'))
            ->sortByDesc(fn($f) => Storage::disk('public')->lastModified($f));

        $files = $files->map(fn($f) => [
            'path' => $f,
            'url' => Storage::disk('public')->url($f),
            'name' => basename($f),
            'size' => Storage::disk('public')->size($f),
            'modified' => Storage::disk('public')->lastModified($f),
            'type' => explode('/', Storage::disk('public')->mimeType($f))[0],
        ]);

        return view('admin.media.index', compact('files'));
    }

    public function destroy(string $filename)
    {
        Storage::disk('public')->delete($filename);
        return redirect('/admin/media')->with('success', 'Fichier supprimé.');
    }
}
