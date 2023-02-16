@if($authUser->can('view', \App\Models\Banner::class))
    {{--   Banners --}}
    <li class="nav-item @if(request()->route()->named("{$whereIam}.banners.*")) active @endif">
        <a class="nav-link " href="{{route("{$whereIam}.banners.index")}}">
            <i class="fa fa-fw fa-images mr-1"></i>
            <span>@lang("admin/banners/banners.sidebar")</span>
        </a>
    </li>
@endif
