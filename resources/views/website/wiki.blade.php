@extends('main')

@section('page')

<div class="container page-container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            <h1>ArchStrike Wiki</h1>
            <div class="breadcrumb">
                @if($page == 'index')
                    home
                @else
                    <a href="/wiki">home</a> &gt; {{ $page }}
                @endif
            </div>
        </div>
    </div>
    <div class="row wiki-row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            @include("markdown.wiki.$page")
        </div>
    </div>
</div>

@endsection
