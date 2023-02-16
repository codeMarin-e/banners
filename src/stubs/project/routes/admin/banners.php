<?php

use App\Http\Controllers\Admin\BannerPositionController;
use App\Http\Controllers\Admin\BannerController;
use App\Models\Banner;
use App\Models\BannerPosition;

Route::group([
    'middleware' => ['auth:admin', 'can:view,'.Banner::class],
    'as' => 'banners.', //naming prefix
    'prefix' => 'banners', //for routes
], function() {
    Route::group([
        'controller' => BannerPositionController::class,
        'as' => 'position.', //naming prefix
        'prefix' => 'position', //for routes
    ], function() {
        Route::post('', 'store')->name('store')->middleware('can:create,' . BannerPosition::class);
        Route::get('create', 'create')->name('create')->middleware('can:create,' . BannerPosition::class);
        Route::get('{chBannerPosition}/edit', 'edit')->name('edit');
        Route::get('{chBannerPosition}', 'edit')->name('show');
        Route::patch('{chBannerPosition}', 'update')->name('update')->middleware('can:update,chBannerPosition');
        Route::delete('{chBannerPosition}', 'destroy')->name('destroy')->middleware('can:delete,chBannerPosition');

        // @HOOK_BANNER_POSITIONS_ROUTES
    });

    Route::group([
        'controller' => BannerController::class,
    ], function() {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store')->middleware('can:create,' . Banner::class);
        Route::get('create', 'create')->name('create')->middleware('can:create,' . Banner::class);
        Route::get('{chBanner}/edit', 'edit')->name('edit');
        Route::get('{chBanner}', 'edit')->name('show');
        Route::get('{chBanner}/move/{direction}', "move")->name('move')->middleware('can:update,chBanner');
        Route::patch('{chBanner}', 'update')->name('update')->middleware('can:update,chBanner');
        Route::delete('{chBanner}', 'destroy')->name('destroy')->middleware('can:delete,chBanner');

        // @HOOK_BANNERS_ROUTES
    });

    // @HOOK_ROUTES
});
