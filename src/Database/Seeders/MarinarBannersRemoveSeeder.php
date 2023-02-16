<?php
    namespace Marinar\Banners\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Banners\MarinarBanners;
    use Spatie\Permission\Models\Permission;

    class MarinarBannersRemoveSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_banners';
            static::$packageDir = MarinarBanners::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoRemove();

            $this->refComponents->info("Done!");
        }

        public function clearMe() {
            $this->refComponents->task("Clear DB", function() {
                Permission::whereIn('name', [
                    'banners.view',
                    'banner.create',
                    'banner.update',
                    'banner.delete',
                ])
                ->where('guard_name', 'admin')
                ->delete();
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
                return true;
            });
        }
    }
