<?php

namespace App\Models;

use App\Models\Banner;
use App\Traits\MacroableModel;
use Illuminate\Database\Eloquent\Model;

class BannerPosition extends Model
{
    protected $fillable = ['site_id', 'system'];

    use MacroableModel;

    // @HOOK_TRAITS

    protected static function boot() {
        parent::boot();
        static::deleting(static::class.'@onDeleting_banners');

        // @HOOK_BOOT
    }

    public function banners() {
        return $this->hasMany( Banner::class, 'banner_position_id', 'id')->orderBy('ord');
    }

    public function onDeleting_banners($model) {
        $model->loadMissing('banners');
        foreach($model->banners as $banner) {
            $banner->delete();
        }
    }
}
