<?php

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;

class BannerPolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(User $user) {
        // @HOOK_POLICY_VIEW
        return $user->hasPermissionTo('banners.view', request()->whereIam());
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('banner.create', request()->whereIam());
    }

    public function update(User $user, Banner $chBanner) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('banner.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, Banner $chBanner) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('banner.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END
}
