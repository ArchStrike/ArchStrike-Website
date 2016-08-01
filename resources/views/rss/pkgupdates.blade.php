@extends('rss')

@section('items')
    @foreach($pkgupdates as $update)
        @if(App\Abs::exists($update['pkgname']))
            @if($update['info'] == 1)
                @set('info', ' (new)')
            @elseif($update['info'] == 2)
                @set('info', ' (moved)')
            @endif

            @include('rss.item', [
                'title' => $update['pkgname'] . ' ' . $update['pkgver'] . '-' . $update['pkgrel'] . $info,
                'link' => URL::to('/') . '/packages/' . $update['pkgname'],
                'date' => $update['date'],
                'body' => '',
                'body_markdown' => false
            ])
        @endif
    @endforeach
@endsection
