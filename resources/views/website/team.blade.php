@extends('main')

@section('page')

<div class="container page-container">
    <div class="row">
        <div class="col-xs-12 col-md-10 col-md-offset-1 column">
            <h1>Team</h1>
        </div>
    </div>
    <div class="team-list">
        @foreach(['arch3y', 'cthulu201', 'd1rt', 'prurigro'] as $member)
            <div class="member-row row">
                <div class="col-xs-12 col-md-10 col-md-offset-1 column">
                    <div class="profile-picture" style="background-image: url('/img/team/{{ $member }}.jpg')"></div>
                    <div class="profile-info">@include("markdown.team.$member")</div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
