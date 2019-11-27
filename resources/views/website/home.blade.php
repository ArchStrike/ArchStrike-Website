@extends('main')

@section('page')
    <div class="container page-container">
        <div class="page-wrapper">
            <div class="info-column">
                <div class="info">
                    <div class="info-heading-wrapper">
                        <h1 class="info-logo"><img src="/img/archstrike.svg" alt="ArchStrike" class="img-responsive" /></h1>
                        <div class="info-container info-heading">An <a href="https://www.archlinux.org" target="_blank" rel="noopener noreferrer">Arch Linux</a> repository for security professionals and enthusiasts</div>
                    </div>

                    <div class="info-container info-body">
                        <p>Done the <a href="https://wiki.archlinux.org/index.php/Arch_Linux#Principles" target="_blank" rel="noopener noreferrer">Arch Way</a> and optimized for i686, x86_64, ARMv6, ARMv7, and ARMv8</p>
                        <p>We follow the Arch Linux standards closely in order to keep our packages clean, proper and easy to maintain. Our team works hard to maintain the repository and give the best ArchStrike experience. If you find any issues, please don't hesitate to contact us via GitHub, IRC, Twitter or email. Any feedback is appreciated.</p>
                        <p class="social-links">@include('elements.social')</p>
                    </div>
                </div>

                <div class="news">
                    <div class="news-logo"><span>Latest News</span></div>
                    <a class="rss-link" href="/rss/news" title="Archstrike News RSS Feed"><img src="/img/rss.png" alt="RSS" /></a>

                    @foreach($news_items as $news_item)
                        @include('news.' . $news_item, [ 'include' => 'elements.news-item' ])
                    @endforeach

                    <div class="complete-news-link"><a href="/news">Complete News History</a></div>
                </div>
            </div>

            <div class="sidebar">
                <div class="sidebar-box">
                    <div class="sidebar-box-heading">Tools</div>

                    <div class="sidebar-box-item">
                        <form id="package-search" class="package-search">
                            <input id="search-string" type="text" placeholder="Package Search" />
                            <input id="search-type" type="hidden" value="name-description" />
                        </form>
                    </div>

                    <a class="sidebar-box-link" href="/mirrorlist">ArchStrike Mirrorlist Generator</a>
                </div>

                @if(env('PKGUPDATES_ENABLED'))
                    <div class="sidebar-box pkgupdates">
                        <div class="sidebar-box-heading">
                            Latest Package Updates
                            <a class="rss-link" href="/rss/latest-updates" title="RSS Feed of the latest Package Updates"><img src="/img/rss.png" alt="RSS" /></a>
                        </div>

                        @include('generated.pkgupdates', [ 'blade' => 'elements.pkgupdates' ])
                    </div>
                @endif

                <div class="sidebar-box">
                    <div class="sidebar-box-heading"><a href="https://twitter.com/ArchStrike" target="_blank" rel="noopener noreferrer">Twitter Feed</a></div>

                    @cache('twitter', 5)
                        @foreach(Twitter::getUserTimeline([ 'screen_name' => 'ArchStrike', 'count' => 4, 'format' => 'object' ]) as $tweet)
                            <div class="sidebar-box-item">
                                <div><a href="{!! Twitter::linkTweet($tweet) !!}" target="_blank" rel="noopener noreferrer">ArchStrike</a> <span>{{ Twitter::ago($tweet->created_at) }}</span></div>
                                <a href="https://twitter.com/ArchStrike/status/{{ $tweet->id_str }}" target="_blank">{{ $tweet->text }}</a>
                            </div>
                        @endforeach
                    @endcache
                </div>

                {{--
                <div class="sidebar-box sponsors">
                    <div class="sidebar-box-heading">Sponsors</div>
                </div>
                --}}
            </div>
        </div>
    </div>
@endsection
