<?php
    return [
        'no_data' => 'There is no data',

        'banners' => \Illuminate\Support\Arr::undot([
            'banner_position_id.required' => 'There is no such `banner position`',
            'banner_position_id.not_found' => 'There is no such `banner position`',
            'add.name.required' => '`Name` is required',
            'add.name.maz' => '`Name` is too large',
            'pictures.required' => '`Picture` is required',
            'pictures.*.mime' => '`Picture` should be an image',
        ]),

        'position' => \Illuminate\Support\Arr::undot([
            'system.require' => '`System Name` is required',
            'system.max' => '`System Name` is too large',
        ]),

        // @HOOK_LANG
    ];
