<?php
use App\Http\Controllers\BannerController;
use App\Models\Banner;

Route::get('/banners/{banner}', [BannerController::class, 'get'])
    ->middleware(\App\Http\Middleware\SlugParameters::class.":".Banner::class.',banner|chBanner')
    ->name('banner.get');
