<?php

    namespace App\Models;

    use App\Models\BannerPosition;
    use App\Traits\Activiable;
    use App\Traits\AddVariable;
    use App\Traits\Attachable;
    use App\Traits\MacroableModel;
    use App\Traits\Orderable;
    use App\Traits\Uriable;
    use Illuminate\Database\Eloquent\Model;

    class Banner extends Model {

        protected $fillable = ['site_id', 'banner_position_id', 'period_type', 'period_from', 'period_to', 'clicks', 'ord'];

        protected $casts = [
            'period_from' => 'datetime',
            'period_to' => 'datetime',
        ];

        use MacroableModel;
        use AddVariable;
        use Activiable;

        // @HOOK_TRAITS

        //ORDERABLE
        public function orderableQryBld($qryBld = null) {
            $qryBld = $qryBld ? $qryBld : $this;
            return $qryBld->where([
                ['banner_position_id', $this->banner_position_id],
                ['site_id', $this->site_id],
            ]);
        }
        use Orderable;
        //END ORDERABLE

        //URIABLE
        public function defaultUri($language = null, $site_id = null, $prepareLevel = null, $additionals = []) { //just for default
            return 'banners/'.$this->id;
        }
        public function prepareSlug($slug, $prepareLevel = null, $additionals = []) {
            return $slug;
        }
        use Uriable;
        //END URIABLE

        //ATTACHABLE
        public static $attach_folder = 'banners';

        use Attachable;
        //END ATTACHABLE

        protected static function boot() {
            parent::boot();
            static::updating(static::class.'@onUpdating_bannerPosition');
            static::updated(static::class.'@onUpdated_bannerPosition');

            // @HOOK_BOOT
        }

        public function bannerPosition() {
            return $this->belongsTo( BannerPosition::class, 'banner_position_id');
        }

        public function onUpdating_bannerPosition($model) {
            if (!$model->isDirty('banner_position_id'))
                return;
            $model->ord = static::freeOrd($model->orderableQryBld());

        }

        public function onUpdated_bannerPosition($model) {
            if (!$model->isDirty('banner_position_id'))
                return;
            $model->banner_position_id = $model->getOriginal('banner_position_id');
            $model->ord = $model->getOriginal('ord');
            $model->onDeleted_orderable($model);
        }
    }
