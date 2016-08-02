@extends('rss')

@section('items')
    @foreach($pkgupdates as $update)
        @if(App\Abs::exists($update['pkgname']))
            @set('info', $update['info'] == 1 ? ' (new)' : '')

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
