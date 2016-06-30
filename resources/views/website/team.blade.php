@extends('main')

@section('page')
    <div class="container page-container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-12 col-md-offset-0">
                <h1>Team</h1>
            </div>
        </div>

        <div class="row">
            <div class="team-list">
                @foreach([ 'arch3y', 'd1rt', 'prurigro', 'xorond' ] as $member)
                    <div class="col-xs-12 col-md-6 column">
                        <div class="member-row">
                            <div class="profile-picture" style="background-image: url(/img/team/{{ $member }}.jpg)"></div>
                            <div class="profile-info">@include("markdown.team.$member")</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
