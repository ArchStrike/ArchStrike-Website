@extends('main')

@section('page')

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            <h1>Team</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            @include('markdown.team.arch3y')
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            @include('markdown.team.cthulu201')
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            @include('markdown.team.d1rt')
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            @include('markdown.team.prurigro')
        </div>
    </div>
</div>

@endsection
