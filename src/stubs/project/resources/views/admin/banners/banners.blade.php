@pushonceOnReady('below_js_on_ready')
<script>
    function toggleChilds($parent, visible) {
        if (visible) {
            $('tr[data-parent="' + $parent.attr('data-id') + '"]').each(function (index, el) {
                var $el = $(el);
                //toggleChilds($el, parseInt($el.attr('data-show')));
                $el.show();
            });
            return;
        }

        $('tr[data-parent="' + $parent.attr('data-id') + '"]').each(function (index, el) {
            var $el = $(el);
            //toggleChilds($el, 0);
            $el.hide();
        });
    }

    $(document).on('click', '.js_childs_toggle', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $thistr = $this.parents('tr').first();
        visible = parseInt($thistr.attr('data-show')) ? 0 : 1; //reverse;
        toggleChilds($thistr, visible);
        $thistr.attr('data-show', visible);
        $this.html(visible ?
            '<i class="fa fa-angle-down"></i>' : '<i class="fa fa-angle-up"></i>'
        );
    });
</script>
@endpushonceOnReady

<x-admin.main>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a>
            </li>
            <li class="breadcrumb-item active">@lang('admin/banners/banners.banners')</li>
        </ol>

        @can('create', App\Models\BannerPosition::class)
            <a href="{{ route("{$route_namespace}.banners.position.create") }}"
               class="btn btn-sm btn-primary h5"
               title="create">
                <i class="fa fa-plus mr-1"></i>@lang('admin/banners/banners.create_position')
            </a>
        @endcan

        @can('create', App\Models\Banner::class)
            <a href="{{ route("{$route_namespace}.banners.create") }}"
               class="btn btn-sm btn-primary h5"
               title="create">
                <i class="fa fa-plus mr-1"></i>@lang('admin/banners/banners.create')
            </a>
        @endcan

        <x-admin.box_messages />

        <div class="table-responsive rounded ">
            <table class="table table-sm">
                <thead class="thead-light">
                <tr class="">
                    <th scope="col" class="text-center">@lang('admin/banners/banners.id')</th>
                    {{-- @HOOK_AFTER_ID_TH --}}

                    <th scope="col" class="w-75">@lang('admin/banners/banners.name')</th>
                    {{-- @HOOK_AFTER_NAME_TH --}}

                    <th scope="col" class="text-center">@lang('admin/banners/banners.edit')</th>
                    {{-- @HOOK_AFTER_EDIT_TH --}}

                    <th colspan="2" scope="col" class="text-center">@lang('admin/banners/banners.move_th')</th>
                    {{-- @HOOK_AFTER_MOVE_TH --}}

                    <th scope="col" class="text-center">@lang('admin/banners/banners.remove')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bannerPositions as $bannerPosition)
                    @php
                        $bannerPositionEditUri = route("{$route_namespace}.banners.position.edit", $bannerPosition);
                    @endphp
                    <tr data-id="{{$bannerPosition->id}}" ]
                        data-show="1">
                        <td scope="row" class="text-center align-middle"><a href="{{ $bannerPositionEditUri }}"
                                                                            title="@lang('admin/banners/banners.edit')"
                            >{{ $bannerPosition->id }}</a></td>
                        {{-- @HOOK_AFTER_POSITION_ID --}}

                        {{--    NAME    --}}
                        <td class="w-75 align-middle">
                            <a href="{{ $bannerPositionEditUri }}"
                               title="@lang('admin/banners/banners.edit')"
                               class="text-dark"
                            >{{ \Illuminate\Support\Str::words($bannerPosition->system, 12,'....') }}</a>
                            @if($bannerPosition->banners->count())
                                <a href="#"
                                   class="js_childs_toggle text-dark"
                                   data-show="1"
                                   data-id="{{$bannerPosition->id}}"><i class="fa fa-angle-down"></i></a>
                            @endif
                        </td>
                        {{-- @HOOK_AFTER_POSITION_NAME --}}

                        {{--    EDIT    --}}
                        <td class="text-center">
                            <a class="btn btn-link text-success"
                               href="{{ $bannerPositionEditUri }}"
                               title="@lang('admin/banners/banners.edit')"><i class="fa fa-edit"></i></a>
                        </td>
                        {{-- @HOOK_AFTER_POSITION_EDIT--}}

                        <td colspan="2"></td>
                        {{-- @HOOK_AFTER_POSITION_MOVE --}}

                        {{--    DELETE    --}}
                        <td class="text-center">
                            @can('delete', $bannerPosition)
                                <form
                                    action="{{ route("{$route_namespace}.banners.position.destroy", $bannerPosition->id) }}"
                                    method="POST"
                                    id="deletePosition[{{$bannerPosition->id}}]">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-link text-danger"
                                            title="@lang('admin/banners/banners.remove')"
                                            onclick="if(confirm('@lang("admin/banners/banners.remove_ask")')) document.querySelector( '#deletePosition\\[{{$bannerPosition->id}}\\] ').submit() "
                                            type="button"><i class="fa fa-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>

                    {{-- BANNERS --}}
                    @foreach($bannerPosition->banners as $banner)
                        @php
                            $bannerEditUri = route("{$route_namespace}.banners.edit", $banner->id);
                            $canUpdate = $authUser->can('update', $banner);
                        @endphp
                        @if($loop->first)
                            @php $prevBanner = $banner->getPrevious(); @endphp
                        @endif
                        @if($loop->last)
                            @php $nextBanner = $banner->getNext(); @endphp
                        @endif
                        <tr data-id="{{$banner->id}}"
                            data-parent="{{$bannerPosition->id}}"
                            data-show="1">
                            <td scope="row" class="text-center align-middle"><a href="{{ $bannerEditUri }}"
                                                                                class="text-success"
                                                                                title="@lang('admin/banners/banners.edit')"                                >{{ $banner->id }}</a></td>
                            {{-- @HOOK_AFTER_ID --}}

                            {{--    NAME    --}}
                            <td class="w-75 align-middle">
                                <i class="fa fa-arrow-right text-success mr-2"></i>
                                <a href="{{ $bannerEditUri }}"
                                   title="@lang('admin/banners/banners.edit')"
                                   class="@if($banner->active) text-primary @else text-danger @endif"
                                >{{ \Illuminate\Support\Str::words($banner->aVar('name'), 12,'....') }}</a>
                            </td>
                            {{-- @HOOK_AFTER_NAME --}}

                            {{--    EDIT    --}}
                            <td class="text-center">
                                <a class="btn btn-link text-success"
                                   href="{{ $bannerEditUri }}"
                                   title="@lang('admin/banners/banners.edit')"><i class="fa fa-edit"></i></a></td>
                            {{-- @HOOK_AFTER_EDIT --}}

                            {{--    MOVE DOWN    --}}
                            <td class="text-center">
                                @if($canUpdate && (!$loop->last || $nextBanner))
                                    <a class="btn btn-link text-dark"
                                       href="{{route("{$route_namespace}.banners.move", [$banner, 'down'])}}"
                                       title="@lang('admin/banners/banners.move_down')"><i class="fa fa-arrow-down"></i></a>
                                @endif
                            </td>

                            {{--    MOVE UP   --}}
                            <td class="text-center">
                                @if($canUpdate && (!$loop->first || $prevBanner))
                                    <a class="btn btn-link text-dark"
                                       href="{{route("{$route_namespace}.banners.move", [$banner, 'up'])}}"
                                       title="@lang('admin/banners/banners.move_up')"><i class="fa fa-arrow-up"></i></a>
                                @endif
                            </td>
                            {{-- @HOOK_AFTER_MOVE--}}

                            {{--    DELETE    --}}
                            <td class="text-center">
                                @if($authUser->can('delete', $banner))
                                    <form action="{{ route("{$route_namespace}.banners.destroy", $banner->id) }}"
                                          method="POST"
                                          id="delete[{{$banner->id}}]">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-danger"
                                                title="@lang('admin/banners/banners.remove')"
                                                onclick="if(confirm('@lang("admin/banners/banners.remove_ask")')) document.querySelector( '#delete\\[{{$banner->id}}\\] ').submit() "
                                                type="button"><i class="fa fa-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{-- END BANNERS --}}
                @empty
                    <tr>
                        <td colspan="4">@lang('admin/banners/banners.no_banner_positions')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>
</x-admin.main>
