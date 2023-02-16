<?php
namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Routing\Controller;

class BannerController extends Controller
{
    public function get(Banner $chBanner) {
        $chBanner->increment('clicks',1);
        return redirect( $chBanner->getUri() );
    }
}
