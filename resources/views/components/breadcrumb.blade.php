<div class="page_banner_container"
    @if (isset($page_banner) && $page_banner != '')
        style="background-image:url({{ $page_banner }});"
    @endif
>
    <div class="page_banner_content">
        <div class="banner_title">
            <h1>{{ $title }}</h1>
        </div>
        <div class="banner_route">
            <h6>
                <span style="cursor:pointer;" onclick="location.href='{{ route('home') }}';">{{__('page.home')}}</span>
                @if ($parent != '' && $parent_link != '')
                / 
                <span style="cursor:pointer;" onclick="location.href='{{ $parent_link }}';">{{ $parent }}</span>
                @endif
                @if ($show_title != '')
                /
                {{ $show_title }}
                @endif
            </h6>
        </div>
    </div>
    <div class="mask"></div>
</div>