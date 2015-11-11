@extends('main')

@section('page')

<div class="container page-container">
    <div class="page-wrapper">
        <div class="intro-column">
            <img src="/img/archstrike.svg" class="img-responsive home-logo" />
            <div class="intro">
                An&nbsp;<a href="https://www.archlinux.org" target="_blank">Arch&nbsp;Linux</a>&nbsp;repository&nbsp;for security&nbsp;professionals&nbsp;and&nbsp;enthusiasts.<br />
                Done&nbsp;<a href="https://wiki.archlinux.org/index.php/Arch_Linux#Principles">the&nbsp;Arch&nbsp;Way</a>&nbsp;and&nbsp;optimized&nbsp;for i686,&nbsp;x86_64,&nbsp;ARMv6,&nbsp;and&nbsp;ARMv7.
            </div>
        </div>
    </div>

    <div class="feed-column">
        <div class="tweet-box">
            <div class="tweet-box-heading"><a href="https://twitter.com/ArchStrike" target="_blank">Twitter Feed</a></div>
            @cache('twitter', 5)
                @foreach(Twitter::getUserTimeline(['screen_name' => 'ArchStrike', 'count' => 5, 'format' => 'object']) as $tweet)
                    <div class="tweet">
                        <div><a href="{!! Twitter::linkTweet($tweet) !!}" target="_blank">ArchStrike</a> <span>{{ Twitter::ago($tweet->created_at) }}</span></div>
                        {!! Twitter::linkify($tweet->text) !!}
                    </div>
                @endforeach
            @endcache
        </div>

        <div class="contact">
            <a href="https://github.com/ArchStrike" title="ArchStrike Github" target="_blank"><img src="/img/gh-logo.png" /></a>
            <a href="https://twitter.com/ArchStrike" title="ArchStrike Twitter" target="_blank"><img src="/img/tw-logo.png" /></a>
            <a href="mailto:team@archstrike.org" title="ArchStrike Email"><img src="/img/em-logo.png" /></a>
        </div>

        <h3>#archstrike @ irc.freenode.net</h3>
    </div>
</div>

@endsection
