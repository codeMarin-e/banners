<?php
    namespace Marinar\Banners;

    use Marinar\Banners\Database\Seeders\MarinarBannersInstallSeeder;

    
    class MarinarBanners {

        public static function getPackageMainDir() {
            return __DIR__;
        }

        public static function injects() {
            return MarinarBannersInstallSeeder::class;
        }
    }
