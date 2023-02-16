<?php
	return [
		'install' => [
            'php artisan db:seed --class="\Marinar\Banners\Database\Seeders\MarinarBannersInstallSeeder"',
		],
		'remove' => [
            'php artisan db:seed --class="\Marinar\Banners\Database\Seeders\MarinarBannersRemoveSeeder"',
        ]
	];
