@pushonce('below_templates')
@if(isset($chBanner) && $authUser->can('delete', $chBanner))
    <form action="{{ route("{$route_namespace}.banners.destroy", $chBanner) }}"
          method="POST"
          id="delete[{{$chBanner->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

<x-admin.main>
    @php $inputBag = 'banner'; @endphp
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route("{$route_namespace}.banners.index") }}">@lang('admin/banners/banners.banners')</a></li>
            <li class="breadcrumb-item active">@isset($chBanner){{ $chBanner->id }}@else @lang('admin/banners/banner.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="@isset($chBanner){{ route("{$route_namespace}.banners.update", $chBanner) }}@else{{ route("{$route_namespace}.banners.store") }}@endisset"
                      method="POST"
                      autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($chBanner)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />

                    @php
                        $sParentId = old("{$inputBag}.banner_position_id", (isset($chBanner)? $chBanner->banner_position_id : 0));
                    @endphp
                    <div class="form-group row">
                        <label for="{{$inputBag}}[banner_position_id]"
                               class="col-lg-1 col-form-label">@lang('admin/banners/banner.positions')</label>
                        <div class="col-lg-4">
                            <select class="form-control @if($errors->$inputBag->has('banner_position_id')) is-invalid @endif"
                                    id="{{$inputBag}}[banner_position_id]"
                                    name="{{$inputBag}}[banner_position_id]">
                                @foreach($bannerPositions as $bannerPosition)
                                    <option value="{{$bannerPosition->id}}"
                                            @if($sParentId == $bannerPosition->id)selected='selected'@endif
                                    >{{$bannerPosition->system}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    {{-- @HOOK_AFTER_POSITION --}}

                    <div class="form-group row">
                        <label for="{{$inputBag}}[name]"
                               class="col-lg-1 col-form-label"
                        >@lang('admin/banners/banner.name')</label>
                        <div class="col-lg-11">
                            <input type="text"
                                   name="{{$inputBag}}[add][name]"
                                   id="{{$inputBag}}[add][name]"
                                   value="{{ old("{$inputBag}.add.name", (isset($chBanner)? $chBanner->aVar('name') : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('add.name')) is-invalid @endif"
                            />
                        </div>
                    </div>

                    {{-- @HOOK_AFTER_NAME --}}
                    <x-admin.filepond
                        translations="admin/banners/banner.pictures"
                        :routeNamespace="$route_namespace"
                        type="pictures"
                        :inputBag="$inputBag"
                        :accept="'[\'image/*\']'"
                        maxFileSize="1MB"
                        :multiple="false"
                        :attachable="$chBanner?? null"
                    />
                    {{-- @HOOK_AFTER_PICTURES--}}

                    <x-admin.uriable
                        :inputBag="$inputBag"
                        :uriable="$chBanner?? null"
                        :defaultUri="config('app.url')"
                        :excludeTypes="['default']"
                    />
                    {{-- @HOOK_AFTER_URIABLE --}}

                    <x-admin.activiable
                        :inputBag="$inputBag"
                        :activiable="$chBanner?? null"
                        translations="admin/banners/banner.activiable"
                    />
                    {{-- @HOOK_AFTER_ACTIVIABLE --}}

                    <div class="form-group row">
                        @isset($chBanner)
                            @can('update', $chBanner)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/banners/banner.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                        type='submit'
                                        name='update'>@lang('admin/banners/banner.update')</button>
                            @endcan

                            @can('delete', $chBanner)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/banners/banner.delete_ask")')) document.querySelector( '#delete\\[{{$chBanner->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/banners/banner.delete')</button>
                            @endcan
                        @else
                            @can('create', \App\Models\Banner::class)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='create'>@lang('admin/banners/banner.create')</button>
                            @endcan
                        @endisset

                        {{-- @HOOK_AFTER_BUTTONS --}}

                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.banners.index") }}"
                        >@lang('admin/banners/banner.cancel')</a>
                    </div>

                    {{-- @HOOK_ADDON_BUTTONS --}}
                </form>
            </div>
        </div>
    </div>
</x-admin.main>
