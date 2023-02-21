<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Models\BannerPosition;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;


class BannerController extends Controller {
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

    public function index() {
        $viewData = [];
        $viewData['bannerPositions'] = BannerPosition::where("{$this->banner_positions_table}.site_id", app()->make('Site')->id)
            ->with('banners')
            ->orderBy("{$this->banner_positions_table}.id", 'ASC');

        // @HOOK_INDEX

        $viewData['bannerPositions'] = $viewData['bannerPositions']->get();

        return view('admin/banners/banners', $viewData);
    }

    public function create() {
        $viewData = [];
        $viewData['bannerPositions'] = BannerPosition::where("{$this->banner_positions_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->banner_positions_table}.id", 'ASC');

        // @HOOK_CREATE

        $viewData['bannerPositions'] = $viewData['bannerPositions']->get();

        return view('admin/banners/banner', $viewData);
    }

    public function edit(Banner $chBanner) {
        $viewData = [];
        $viewData['chBanner'] = $chBanner;
        $viewData['bannerPositions'] = BannerPosition::where("{$this->banner_positions_table}.site_id", app()->make('Site')->id)
            ->orderBy("{$this->banner_positions_table}.id", 'ASC');

        // @HOOK_EDIT

        $viewData['bannerPositions'] = $viewData['bannerPositions']->get();
        return view('admin/banners/banner', $viewData);
    }

    public function store(BannerRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_STORE_VALIDATE

        $chBanner = Banner::create( array_merge([
            'site_id' => app()->make('Site')->id,
        ], $validatedData));

        // @HOOK_STORE_INSTANCE

        $chBanner->setAVars($validatedData['add']);
        $chBanner->reAttachAndOrder( $validatedData['pictures'] ?? [], 'pictures' );
        $chBanner->setUri($validatedData['uri']['slug'], $validatedData['uri']['type'], $validatedData['uri']);

        // @HOOK_STORE_END
        event( 'banner.submited', [$chBanner, $validatedData] );

        return redirect()->route($this->routeNamespace.'.banners.edit', $chBanner)
            ->with('message_success', trans('admin/banners/banner.created'));
    }

    public function update(Banner $chBanner, BannerRequest $request) {
        $validatedData = $request->validated();

        // @HOOK_UPDATE_VALIDATE

        $chBanner->update( $validatedData );
        $chBanner->setAVars($validatedData['add']);
        $chBanner->reAttachAndOrder( $validatedData['pictures'] ?? [], 'pictures' );
        $chBanner->setUri($validatedData['uri']['slug'], $validatedData['uri']['type'], $validatedData['uri']);

        // @HOOK_UPDATE_END

        event( 'banner.submited', [$chBanner, $validatedData] );
        if($request->has('action')) {
            return redirect()->route($this->routeNamespace.'.banners.index')
                ->with('message_success', trans('admin/banners/banner.updated'));
        }
        return back()->with('message_success', trans('admin/banners/banner.updated'));
    }

    public function move(Banner $chBanner, $direction) {
        // @HOOK_MOVE

        $chBanner->orderMove($direction);

        // @HOOK_MOVE_END

        return back();
    }

    public function destroy(Banner $chBanner) {
        // @HOOK_DESTROY

        $chBanner->delete();

        // @HOOK_DESTROY_END

        return redirect()->route($this->routeNamespace.'.banners.index')
            ->with('message_danger', trans('admin/banners/banner.deleted'));
    }
}
