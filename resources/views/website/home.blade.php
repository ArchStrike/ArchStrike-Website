@extends('main')

@section('page')

<div class="container page-container">
    <div class="row">
        <div class="page-wrapper">
            <div class="col-md-6 col-md-offset-1 info-column">
                <img src="/img/archstrike.svg" class="img-responsive home-logo" />
                <div class="info">
                    An Arch Linux repository for security professionals and enthusiasts.<br />
                    Done <a href="https://wiki.archlinux.org/index.php/Arch_Linux#Principles">the Arch Way</a> and optimized for i686, x86_64, ARMv6, and ARMv7.
                </div>

                <div class="contact">
                    <a href="https://github.com/ArchStrike/ArchStrike"><img src="/img/gh-logo.png" /></a>
                    <a href="https://twitter.com/ArchStrike"><img src="/img/tw-logo.png" /></a>
                    <a href="mailto:archstrikelinux@gmail.com"><img src="/img/em-logo.png" /></a>
                </div>

                <h3>#archstrike @ irc.freenode.net</h3>
            </div>

            <div class="col-md-4 feed-column">
                <div class="tweet-box">
                    <div class="tweet-box-heading">Twitter Feed</div>
                    @cache('twitter', 5)
                        @foreach(Twitter::getUserTimeline(['screen_name' => 'ArchStrike', 'count' => 5, 'format' => 'object']) as $tweet)
                            <div class="tweet">
                                <div><a href="https://twitter.com/ArchStrike">ArchStrike</a> <span>{{ Twitter::ago($tweet->created_at) }}</span></div>
                                {!! Twitter::linkify($tweet->text) !!}
                            </div>
                        @endforeach
                    @endcache
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
