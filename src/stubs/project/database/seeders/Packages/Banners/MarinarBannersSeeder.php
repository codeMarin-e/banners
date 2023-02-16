<?php
namespace Database\Seeders\Packages\Banners;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MarinarBannersSeeder extends Seeder {

    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::upsert([
            ['guard_name' => 'admin', 'name' => 'banners.view'],
            ['guard_name' => 'admin', 'name' => 'banner.create'],
            ['guard_name' => 'admin', 'name' => 'banner.update'],
            ['guard_name' => 'admin', 'name' => 'banner.delete'],
        ], ['guard_name','name']);
    }
}
