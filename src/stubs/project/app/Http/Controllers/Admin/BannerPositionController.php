<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\BannerPosition;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class BannerPositionController extends Controller {
    public function __construct() {
        if(!request()->route()) return;

        $this->banners_table = Banner::getModel()->getTable();
        $this->banner_positions_table = BannerPosition::getModel()->getTable();
        $this->routeNamespace = Str::before(request()->route()->getName(), '.banners');
        View::composer('admin/banners/*', function($view)  {
            $viewData = [
                'route_namespace' => $this->routeNamespace,
            ];
            // @HOOK_VIEW_COMPOSERS
            $view->with($viewData);
        });
        // @HOOK_CONSTRUCT
    }

    public function create() {
        $viewData = [];

        // @HOOK_CREATE

        return view('admin/banners/banner_position', $viewData);
    }

    public function edit(BannerPosition $chBannerPosition) {
        $viewData = [];
        $viewData['chBannerPosition'] = $chBannerPosition;

        // @HOOK_EDIT

        return view('admin/banners/banner_position', $viewData);
    }

    private function validateData(&$request, $chBannerPosition = null) {
        $inputs = request()->all();
        $inputs = $inputs['position']?? [];
        if(empty($inputs)) {
            throw ValidationException::withMessages([
                'no_data' => trans('admin/banners/validation.no_data'),
            ]);
        }
        $messages = Arr::dot((array)trans('admin/banners/validation.positions'));
        $rules = [ 'system' => 'required|max:255' ];

        // @HOOK_VALIDATE

        return Validator::make($inputs, $rules, $messages)->validateWithBag('position');
    }

    public function store(Request $request) {
        $validatedData = $this->validateData($request);

        // @HOOK_STORE_VALIDATE

        $chBannerPosition = BannerPosition::create( array_merge([
            'site_id' => app()->make('Site')->id,
        ], $validatedData));

        // @HOOK_STORE_END
        event( 'banner_position.submited', [$chBannerPosition, $validatedData] );

        return redirect()->route($this->routeNamespace.'.banners.position.edit', $chBannerPosition)
            ->with('message_success', trans('admin/banners/banner_position.created'));
    }

    public function update(BannerPosition $chBannerPosition, Request $request) {
        $validatedData = $this->validateData($request, $chBannerPosition);

        // @HOOK_UPDATE_VALIDATE

        $chBannerPosition->update( $validatedData );

        // @HOOK_UPDATE_END

        event( 'banner_position.submited', [$chBannerPosition, $validatedData] );
        if($request->has('action')) {
            return redirect()->route($this->routeNamespace.'.banners.index')
                ->with('message_success', trans('admin/banners/banner_position.updated'));
        }
        return back()->with('message_success', trans('admin/banners/banner_position.updated'));
    }

    public function destroy(BannerPosition $chBannerPosition) {
        // @HOOK_DESTROY

        $chBannerPosition->delete();

        // @HOOK_DESTROY_END

        return redirect()->route($this->routeNamespace.'.banners.index')
            ->with('message_danger', trans('admin/banners/banner_position.deleted'));
    }
}
