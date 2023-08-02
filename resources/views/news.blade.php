@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="news_page_container">
        <div class="news_classify">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($news_category as $category)
                <li class="nav-item" role="presentation">
                    <button 
                        type="button"
                        class="nav-link @if($tab == $category['id']) active @endif" 
                        id="btn_{{ $category['id'] }}" 
                        data-bs-toggle="pill" 
                        data-bs-target="#tab_{{ $category['id'] }}"  
                        role="tab" 
                        aria-controls="{{ $category['id'] }}" 
                        aria-selected="@if ($tab == $category['id']) true @else false @endif"
                    >{{ $category['text'] }}</button>
                </li>
                @endforeach
            </ul>
        </div>
        
        <div class="tab-content" id="myTabContent">
            @foreach ($news_category as $index => $category)
            <div class="tab-pane fade @if($tab == $category['id']) show active @endif" id="tab_{{ $category['id'] }}" role="tabpanel" aria-labelledby="tab_{{ $category['id'] }}" tabindex="{{ $index }}">
                <div class="news_list">
                    <div class="row">
                        @foreach ($data as $item)
                        @if($category['id'] != 'all' && $category['id'] != $item->category) @continue @endif
                        <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="news_item">
                                <img class="mb-2" src="{{ env('APP_URL').Storage::url($item->cover) }}" alt="{{ $item->title }}" onclick="location.href='{{ route('news.detail', ['id'=>$item->id]) }}';">
                                <div class="new_time mb-3">
                                    <span>{{ date('Y/m/d', strtotime($item->date)) }}</span> | By {{ $item->member->username }} | <span style="color:#979797;" onclick="location.href='{{ route('news') }}?tab={{ $news_category[$item->category]['id'] }}';">{{ $news_category[$item->category]['text'] }}</span>
                                </div>
                                <h2 style="font-size:1.25rem;" class="new_title mb-3" onclick="location.href='{{ route('news.detail', ['id'=>$item->id]) }}';">
                                    {{ $item->title }}
                                </h2>
                                <div class="new_content limit_three mb-2">
                                    {{ $item->summary }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- <div class="pagination_container">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link"><span><i class="fa-solid fa-angle-left"></i></span></a>
                            </li>

                            <li class="page-item" ><a class="page-link" href="#"><span>1</span></a></li>
                            <li class="page-item" aria-current="page"><a class="page-link active" href="#"><span>2</span></a></li>
                            <li class="page-item"><a class="page-link" href="#"><span>3</span></a></li>

                            <li class="page-item">
                                <a class="page-link" href="#"><span><i class="fa-solid fa-angle-right"></i></span></a>
                            </li>
                        </ul>
                    </nav>
                </div> -->
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
@section('script')

@endsection