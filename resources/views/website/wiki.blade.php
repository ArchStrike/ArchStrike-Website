@extends('main')

@section('page')
    <div class="container-fluid page-container">
        <div class="row">
            <div class="col-xs-12 column">
                <h1>ArchStrike Wiki</h1>

                <div class="breadcrumb">
                    @if($page == 'index')
                        Home
                    @else
                        <a href="/wiki">Home</a> <span>&rarr;</span> {{ ucfirst($page) }}
                    @endif
                </div>
            </div>
        </div>

        <div class="row wiki-row">
            <div class="col-xs-12 column">
                @include("markdown.wiki.$page")
            </div>
        </div>
    </div>
@endsection
