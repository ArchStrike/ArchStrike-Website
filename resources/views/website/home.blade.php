@extends('main')

@section('page')
    <div class="container page-container">
        <div class="page-wrapper">
            <div class="info-column">
                <div class="info">
                    <div class="info-heading-wrapper">
                        <div class="info-logo"><img src="/img/archstrike.svg" class="img-responsive" /></div>
                        <div class="info-heading">An <a href="https://www.archlinux.org" target="_blank" rel="noopener noreferrer">Arch Linux</a> repository for security professionals and enthusiasts</div>
                    </div>

                    <div class="info-body">
                        <p>Done the <a href="https://wiki.archlinux.org/index.php/Arch_Linux#Principles" target="_blank" rel="noopener noreferrer">Arch Way</a> and optimized for i686, x86_64, ARMv6, and ARMv7</p>
                        <p>We follow the Arch Linux standards closely in order to keep our packages clean, proper and easy to maintain. Our team works hard to maintain the repository and give the best ArchStrike experience. If you find any issues, please don't hesitate to contact us via GitHub, IRC, Twitter or email. Any feedback is appreciated.</p>
                        <p class="social-links">@include('elements.social')</p>
                    </div>
                </div>

                <div class="news">
                    <div class="news-logo">Latest News</div>

                    <div class="news-item">
                        <div class="news-heading">
                            <div class="news-title">This is a sample news heading describing a story</div>
                            <div class="news-date">2016-06-26</div>
                        </div>

                        <div class="news-body">
                            This is a news post
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <form id="package-search" class="package-search">
                    <div class="package-search-wrapper">
                        <div>Package Search:</div><input placeholder="" />
                    </div>
                </form>

                <div class="sidebar-box">
                    <div class="sidebar-box-heading"><a href="https://twitter.com/ArchStrike" target="_blank" rel="noopener noreferrer">Twitter Feed</a></div>

                    @cache('twitter', 4)
                        @foreach(Twitter::getUserTimeline([ 'screen_name' => 'ArchStrike', 'count' => 5, 'format' => 'object' ]) as $tweet)
                            <div class="tweet">
                                <div><a href="{!! Twitter::linkTweet($tweet) !!}" target="_blank" rel="noopener noreferrer">ArchStrike</a> <span>{{ Twitter::ago($tweet->created_at) }}</span></div>
                                {!! Twitter::linkify($tweet->text) !!}
                            </div>
                        @endforeach
                    @endcache
                </div>

                <div class="sidebar-box sponsors">
                    <div class="sidebar-box-heading">Our Sponsors</div>
                    <div class="item"><a href="https://www.linode.com/?utm_source=referral&utm_medium=website&utm_content=Archstrike&utm_campaign=sponsorship" target="_blank" rel="noopener noreferrer"><img src="/img/sponsors/linode.svg" class="img-responsive" /></a></div>
                </div>
            </div>
        </div>
    </div>
@endsection
