@extends('main')

@section('page')
    <div class="container-fluid page-container">
        <div class="row">
            <div class="col-xs-12 column">
                <h1>ArchStrike Downloads</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 column">
                @include("markdown.downloads.index")
            </div>
        </div>
    </div>
@endsection
