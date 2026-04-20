<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Track;
use App\Models\Update;
use App\Models\ApiLog;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'albums' => Album::count(),
            'tracks' => Track::count(),
            'updates' => Update::where('visible', true)->count(),
            'links' => SocialLink::where('active', true)->count(),
        ];

        $recentLogs = ApiLog::with('user')->latest()->limit(10)->get();
        $recentAlbums = Album::with('tracks')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentLogs', 'recentAlbums'));
    }
}
