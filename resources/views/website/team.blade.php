@extends('main')

@section('page')
    <div class="container page-container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Team</h1>
            </div>
        </div>

        <div class="row team-list">
            @foreach(App\Team::getCurrentTeamMembers() as $member)
                <div class="col-xs-12 col-sm-6 col-md-4 column">
                    <div class="member-row">
                        <div class="profile-picture" style="background-image: url(/img/team/{{ $member['nick'] }}.jpg)"></div>

                        <div class="profile-info">
                            <h2>{{ $member['name'] }}</h2>
                            <p><strong>email</strong>: <a href="mailto:{{ $member['email'] }}">{{ $member['email'] }}</a></p>
                            <p><strong>github</strong>: <a href="{{ $member['github'] }}" target="_blank">{{ $member['github'] }}</a></p>
                            <p><strong>pgp</strong>: <input class="click-select" type="text" value="{{ $member['pgp'] }}" readonly /></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Former Team Members</h2>
            </div>
        </div>

        <div class="row team-list">
            @foreach(App\Team::getFormerTeamMembers() as $member)
                <div class="col-xs-12 col-sm-6 col-md-4 column">
                    <div class="member-row">
                        <div class="profile-picture" style="background-image: url(/img/team/{{ $member['nick'] }}.jpg)"></div>

                        <div class="profile-info">
                            <h2>{{ $member['name'] }}</h2>
                            <p><strong>github</strong>: <a href="{{ $member['github'] }}" target="_blank">{{ $member['github'] }}</a></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
