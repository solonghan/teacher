<div class="classify_bar">
    <div class="classify_container bar_container">
        <h5>{{ __('page.classify') }}</h5>
        <ul class="mb-3">
            @foreach ($news_category as $category)
            <li class="nav-item" role="presentation">
                <button class="news_classify_link limit_one" data-tag="announcement" onclick="location.href='{{ route('news') }}?tab={{ $category['id'] }}';">{{ $category['text'] }}</button>
                <!-- <button class="news_classify_link" data-tag="announcement" onclick="location.href='{{ route('news.list').'?category='.$category['id'] }}';">{{ $category['text'] }}</button> -->
                <span>({{ $category['cnt'] }})</span>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="news_list_bar_container bar_container">
        <h5>{{ __('page.news') }}</h5>
        @foreach ($side_news as $news_item)
        <div class="news_bar_item" onclick="location.href='{{ route('news.detail', ['id'=>$news_item->id]) }}';">
            <img src="{{ env('APP_URL').Storage::url($news_item->thumb) }}" alt="{{ $news_item->title }}" style="width:75px;">
            <div class="item_content">
                <h6 class="limit_two">{{ $news_item->title }}</h6>
                <div class="item_date limit_one">{{ date('Y/m/d', strtotime($news_item->date)) }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="tag_container bar_container">
        <h5>標籤</h5>
        <div class="tag_link_container mb-3">
            <!-- <button class="tag_link news_tag_link" data-tag="1" onclick="goNewsTag('可回收')">可回收</button>
            <button class="tag_link news_tag_link" data-tag="1" onclick="goNewsTag('可回收')">無毒</button> -->
        </div>
    </div>
</div>
