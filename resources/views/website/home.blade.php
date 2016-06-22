@extends('main')

@section('page')
    <div class="container page-container">
        <div class="page-wrapper">
            <div class="intro-column">
                <img src="/img/archstrike.svg" class="img-responsive home-logo" />

                <div class="intro">
                    <p class="intro-description">
                        An <a href="https://www.archlinux.org" target="_blank" rel="noopener noreferrer">Arch Linux</a> repository for security professionals and enthusiasts done the <a href="https://wiki.archlinux.org/index.php/Arch_Linux#Principles" target="_blank" rel="noopener noreferrer">Arch Way</a><br class="no-mobile" />
                        and optimized for i686, x86_64, ARMv6, and ARMv7
                    </p>

                    <p class="intro-information">We follow the Arch Linux standards closely in order to keep our packages clean, proper and easy to maintain. Our team works hard to maintain the repository and give the best ArchStrike experience. If you find any issues, please don't hesitate to contact us via GitHub, IRC, Twitter or email. Any feedback is appreciated.</p>
                </div>
            </div>
        </div>

        <div class="feed-column">
            <form id="package-search" class="package-search">
                <div class="package-search-wrapper">
                    <div>Package Search:</div><input placeholder="" />
                </div>
            </form>

            <div class="tweet-box">
                <div class="tweet-box-heading"><a href="https://twitter.com/ArchStrike" target="_blank" rel="noopener noreferrer">Twitter Feed</a></div>

                @cache('twitter', 5)
                    @foreach(Twitter::getUserTimeline([ 'screen_name' => 'ArchStrike', 'count' => 5, 'format' => 'object' ]) as $tweet)
                        <div class="tweet">
                            <div><a href="{!! Twitter::linkTweet($tweet) !!}" target="_blank" rel="noopener noreferrer">ArchStrike</a> <span>{{ Twitter::ago($tweet->created_at) }}</span></div>
                            {!! Twitter::linkify($tweet->text) !!}
                        </div>
                    @endforeach
                @endcache
            </div>

            <div class="contact">
                <a href="https://github.com/ArchStrike" title="ArchStrike Github" target="_blank" rel="noopener noreferrer"><img src="/img/gh-logo.png" /></a>
                <a href="https://twitter.com/ArchStrike" title="ArchStrike Twitter" target="_blank" rel="noopener noreferrer"><img src="/img/tw-logo.png" /></a>
                <a href="mailto:team@archstrike.org" title="ArchStrike Email"><img src="/img/em-logo.png" /></a>
            </div>

            <h3>#archstrike @ irc.freenode.net</h3>
        </div>
    </div>
@endsection
