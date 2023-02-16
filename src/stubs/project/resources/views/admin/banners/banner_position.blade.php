@pushonce('below_templates')
@if(isset($chBannerPosition) && $authUser->can('delete', $chBannerPosition))
    <form action="{{ route("{$route_namespace}.banners.position.destroy", $chBannerPosition) }}"
          method="POST"
          id="delete[{{$chBannerPosition->id}}]">
        @csrf
        @method('DELETE')
    </form>
@endif
@endpushonce

<x-admin.main>
@php $inputBag = 'position'; @endphp
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route("{$route_namespace}.home")}}"><i class="fa fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route("{$route_namespace}.banners.index") }}">@lang('admin/banners/banners.banners')</a></li>
            <li class="breadcrumb-item active">@isset($chBannerPosition){{ $chBannerPosition->id }}@else @lang('admin/banners/banner_position.create') @endisset</li>
        </ol>

        <div class="card">
            <div class="card-body">
                <form action="@isset($chBannerPosition){{ route("{$route_namespace}.banners.position.update", $chBannerPosition->id) }}@else{{ route("{$route_namespace}.banners.position.store") }}@endisset"
                      method="POST"
                      autocomplete="off"
                      enctype="multipart/form-data">
                    @csrf
                    @isset($chBannerPosition)@method('PATCH')@endisset

                    <x-admin.box_messages />

                    <x-admin.box_errors :inputBag="$inputBag" />

                    <div class="form-group row">
                        <label for="{{$inputBag}}[system]"
                               class="col-lg-1 col-form-label"
                        >@lang('admin/banners/banner_position.system')</label>
                        <div class="col-lg-11">
                            <input type="text"
                                   name="{{$inputBag}}[system]"
                                   id="{{$inputBag}}[system]"
                                   value="{{ old("{$inputBag}.system", (isset($chBannerPosition)? $chBannerPosition->system : '')) }}"
                                   class="form-control @if($errors->$inputBag->has('system')) is-invalid @endif"
                            />
                        </div>

                    </div>

                    {{-- @HOOK_AFTER_SYSTEM--}}

                    <div class="form-group row">
                        @isset($chBannerPosition)
                            @can('update', $chBannerPosition)
                                <button class='btn btn-success mr-2'
                                        type='submit'
                                        name='action'>@lang('admin/banners/banner_position.save')
                                </button>

                                <button class='btn btn-primary mr-2'
                                    type='submit'
                                    name='update'>@lang('admin/banners/banner_position.update')</button>
                            @endcan

                            @can('delete', $chBannerPosition)
                                <button class='btn btn-danger mr-2'
                                        type='button'
                                        onclick="if(confirm('@lang("admin/banners/banner_position.delete_ask")')) document.querySelector( '#delete\\[{{$chBannerPosition->id}}\\] ').submit() "
                                        name='delete'>@lang('admin/banners/banner_position.delete')</button>
                            @endcan
                        @else
                            @can('create', \App\Models\BannerPosition::class)
                            <button class='btn btn-success mr-2'
                                    type='submit'
                                    name='create'>@lang('admin/banners/banner_position.create')</button>
                            @endcan
                        @endisset
                        <a class='btn btn-warning'
                           href="{{ route("{$route_namespace}.banners.index") }}"
                        >@lang('admin/banners/banner_position.cancel')</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-admin.main>
