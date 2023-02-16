<?php
    namespace Marinar\Banners\Database\Seeders;

    use Illuminate\Database\Seeder;
    use Marinar\Banners\MarinarBanners;

    class MarinarBannersInstallSeeder extends Seeder {

        use \Marinar\Marinar\Traits\MarinarSeedersTrait;

        public static function configure() {
            static::$packageName = 'marinar_banners';
            static::$packageDir = MarinarBanners::getPackageMainDir();
        }

        public function run() {
            if(!in_array(env('APP_ENV'), ['dev', 'local'])) return;

            $this->autoInstall();

            $this->refComponents->info("Done!");
        }

    }
