<?php
use App\Http\Controllers\BannerController;
use App\Http\Controllers\Banner;

Route::get('/banners/{banner}', [BannerController::class, 'get'])
    ->middleware(\App\Http\Middleware\SlugParameters::class.":".Banner::class.',banner|chBanner')
    ->name('banner.get');
