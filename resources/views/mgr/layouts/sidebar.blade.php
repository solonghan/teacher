<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('mgr.home') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo_sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <h3>北教外校推薦系統</h3>
                <!-- <img src="{{ URL::asset('assets/images/logo.svg') }}" alt="" height="47"> -->
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('mgr.home') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('assets/images/logo_sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <!-- <img src="{{ URL::asset('assets/images/logo.svg') }}" alt="" height="47"> -->
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link @if ($active == 'dashboard') active @endif" href="/mgr">
                        <i class="ri-home-7-fill"></i> <span >Dashboard</span>
                    </a>
                    
                    @foreach ($nav as $nav_item) 
                    <a class="nav-link menu-link @if ($active == $nav_item['function']) active @endif"@if (count($nav_item['sub']) > 0) href="#nav_{{$nav_item['id']}}" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="nav_{{$nav_item['id']}}"@else href="{{ ($nav_item['url']!='')?route($nav_item['url']):'#' }}" @endif>
                        <i class="{{ $nav_item['icon'] }}"></i> <span >{{$nav_item['name']}}</span>
                        @if (isset($nav_item['badge']))
                            @if ($nav_item['badge'] == 'v')
                            <span class="badge badge-pill bg-success">{{ $nav_item['badge'] }}</span>
                            @elseif ($nav_item['badge'] == 'ing')
                            <span class="badge badge-pill bg-warning">{{ $nav_item['badge'] }}</span>
                            @elseif ($nav_item['badge'] != 0)
                            <span class="badge badge-pill bg-primary">{{ $nav_item['badge'] }}</span>
                            @endif
                        @endif
                    </a>
                    @if (count($nav_item['sub']) > 0)
                    <div class="collapse menu-dropdown @if ($active == $nav_item['function']) show @endif" id="nav_{{$nav_item['id']}}">
                        <ul class="nav nav-sm flex-column">
                            @foreach ($nav_item['sub'] as $sub)
                            <li class="nav-item">
                                <a class="nav-link @if ($sub_active == $sub['function']) active @endif" href="{{ ($sub['url']!='')?route($sub['url']):'#' }}">{{$sub['name']}}
                                @if (isset($sub['badge']))
                                    @if ($sub['badge'] == 'v')
                                    <span class="badge badge-pill bg-success">{{ $sub['badge'] }}</span>
                                    @elseif ($sub['badge'] == 'ing')
                                    <span class="badge badge-pill bg-warning">{{ $sub['badge'] }}</span>
                                    @elseif ($sub['badge'] != 0)
                                    <span class="badge badge-pill bg-primary">{{ $sub['badge'] }}</span>
                                    @endif
                                @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @endforeach
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
