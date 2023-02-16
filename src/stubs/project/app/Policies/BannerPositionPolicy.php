<?php

namespace App\Policies;

use App\Models\BannerPosition;
use App\Models\User;

class BannerPositionPolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('banner.create', request()->whereIam());
    }

    public function update(User $user, BannerPosition $chBannerPosition) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('banner.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, BannerPosition $bannerPosition) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('banner.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END
}
