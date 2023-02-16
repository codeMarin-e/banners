<?php

use App\Models\Banner;
use App\Models\BannerPosition;
use App\Policies\BannerPolicy;
use App\Policies\BannerPositionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

Route::model('chBanner', Banner::class);
Route::model('chBannerPosition', BannerPosition::class);
Gate::policy(Banner::class, BannerPolicy::class);
Gate::policy(BannerPosition::class, BannerPositionPolicy::class);

