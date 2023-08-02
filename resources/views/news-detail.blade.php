@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="without_page_banner">
    <div class="container">
        <div class="news_detail_container">
            @include('components.news_filter')
            <div class="main_container">
                <div class="banner_route">
                    <h6><a style="color:#686868;" href="{{ route('home') }}">首頁</a> / <a style="color:#686868;" href="{{ route('news') }}?tab={{ $news_category[$data->category]['id'] }}">{{ $news_category[$data->category]['text'] }}</a> / {{ $data->title }}</h6>
                </div>
                <div class="news_detail_carousel my-3">
                    <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($pics as $index => $pic)
                            <div class="carousel-item @if($index == 0) active @endif">
                                <img src="{{ env('APP_URL').Storage::url($pic->path) }}" class="d-block w-100" alt="">
                            </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="new_time mb-2">
                    <span class="blue_time">{{ date('Y/m/d', strtotime($data->date)) }}</span> | By {{ $data->member->username }} | {{ $news_category[$data->category]['text'] }}
                </div>
                <h4 class="new_title my-3">
                    {{ $data->title }}
                </h4>
                <div class="new_content mb-5">
                    {!! $data->content !!}
                </div>
                <div class="tag_and_share_container">
                    <div class="tag_title">標籤 :
                        @foreach ($data->tags as $index => $tag)
                        @if ($index > 0) , @endif
                        <span><a href="javascript:;" onclick="goNewsTag('{{ $tag->title }}')">{{ $tag->title }}</a></span>
                        @endforeach
                    </div>
                    <div class="share_to">
                        <div class="share_title">分享至 :</div>
                        <div class="share_facebook share_btn" onclick="share('facebook')"><i class="fa-brands fa-facebook"></i>Facebook</div>
                        <div class="share_line share_btn" onclick="share('line')"><i class="fa-brands fa-line"></i>Line</div>
                        <div class="share_twitter share_btn" onclick="share('twitter')"><i class="fa-brands fa-twitter"></i>Twitter</div>
                        <div class="share_wechat share_btn" onclick="share('wechat')"><i class="fa-brands fa-weixin"></i>WeChat</div>
                        <div class="share_whatsapp share_btn" onclick="share('whatsapp')"><i class="fa-brands fa-whatsapp"></i>WhatsApp</div>
                    </div>
                </div>
                <div class="mb-2 mt-2 d-flex justify-content-between">
                    <div class="">
                        @if ($prev != null)
                        <a style="color:#1173BA;" href="{{ route('news.detail', ['id'=>$prev->id]) }}">
                            <i class="fa-solid fa-chevron-left"></i> {{ $prev->title }}
                        </a>
                        @endif
                    </div>
                    <div class="">
                        @if ($next != null)
                        <a style="color:#1173BA;" href="{{ route('news.detail', ['id'=>$next->id]) }}">
                        {{ $next->title }} <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="news_list">
                    <div class="row">
                        @foreach ($related as $item)
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="news_item" style="padding: 0;" onclick="location.href='{{ route('news.detail', ['id'=>$item->id]) }}';">
                                <img class="mb-2" src="{{ env('APP_URL').Storage::url($item->cover) }}" alt="{{ $item->title }}">
                                <div class="new_time mb-2">
                                    <span>{{ date('Y/m/d', strtotime($item->date)) }}</span> | By {{ $item->member->username }} | <a style="color:#979797;" href="{{ route('news') }}?tab={{ $news_category[$item->category]['id'] }}">{{ $news_category[$item->category]['text'] }}</a>
                                </div>
                                <h5 class="new_title mb-2">
                                    {{ $item->title }}
                                </h5>
                                <div class="new_content limit_three mb-2">
                                    {{ $item->summary }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
    
@endsection
@section('script')

<script>
    function share(position) {
        if (position === 'facebook') {
            console.log(location.href);
            window.open(`https://www.facebook.com/sharer.php?u=${location.href}`)
        } else if (position === 'line'){
            window.open(`https://social-plugins.line.me/lineit/share?url=${location.href}`)
        } else if (position === 'twitter') {
            window.open(`https://twitter.com/intent/tweet?text=${location.href}`)
        } else if (position === 'whatsapp') {
            const whatappTitle = $('.new_title').text()
            window.open(`https://api.whatsapp.com/send?&text=${whatappTitle}&url=${location.href}`)
        }
    }
    let tags = geturlQuery('tag');
    
    if (tags) {
        const news_link = $('.tag_link_container').children();
        Object.entries(news_link).forEach(function ([i, item]) {
            // $(item).addClass('active')
            const item_tag = $(item).text()
            if (item_tag === tags) {
                $(item).addClass('active')
            }
        })
    }
</script>
@endsection