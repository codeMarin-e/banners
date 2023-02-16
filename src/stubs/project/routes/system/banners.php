<?php
use App\Http\Controllers\BannerController;
use App\Models\Banner;

Route::get('/banners/{chBanner}', [BannerController::class, 'get'])
    ->name('banner.get');
