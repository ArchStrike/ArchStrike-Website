@extends('main')

@section('page')

<div class="container home-container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            <div class="home-wrapper">
                <img src="/img/archstrike.svg" class="img-responsive home-logo" />

                <div class="intro">
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
        </div>
    </div>
</div>

@endsection
