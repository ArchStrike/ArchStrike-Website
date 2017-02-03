@extends('main')

@section('page')
    <div class="container-fluid page-container">
        <div class="row">
            <div class="col-xs-12">
                <h1>ArchStrike Wiki</h1>

                <div class="breadcrumb">
                    @if($path == 'index')
                        Home
                    @elseif(preg_match('/\//', $path))
                        <a href="/wiki">Home</a> <span>&rarr;</span>
                        @set('curr_path', '/wiki')

                        @foreach(explode('/', preg_replace('/\/index$/', '', $path)) as $level)
                            @if($loop->last)
                                {{ ucfirst($level) }}
                            @else
                                @set('curr_path', $curr_path . '/' . $level)
                                <a href="{{ $curr_path }}">{{ ucfirst($level) }}</a> <span>&rarr;</span>
                            @endif
                        @endforeach
                    @else
                        <a href="/wiki">Home</a> <span>&rarr;</span> {{ ucfirst(preg_replace('/\/index$/', '', $path)) }}
                    @endif
                </div>
            </div>
        </div>

        <div class="row wiki-row">
            <div class="col-xs-12">
                @include('wiki.' . preg_replace('/\//', '.', $path))
            </div>
        </div>
    </div>
@endsection
