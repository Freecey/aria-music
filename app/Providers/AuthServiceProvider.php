<?php

namespace App\Providers;

use App\Models\Album;
use App\Models\Link;
use App\Models\Setting;
use App\Models\Track;
use App\Models\Update;
use App\Policies\AlbumPolicy;
use App\Policies\LinkPolicy;
use App\Policies\SettingPolicy;
use App\Policies\TrackPolicy;
use App\Policies\UpdatePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Album::class => AlbumPolicy::class,
        Track::class => TrackPolicy::class,
        Link::class => LinkPolicy::class,
        Update::class => UpdatePolicy::class,
        Setting::class => SettingPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
