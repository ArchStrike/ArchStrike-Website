@extends('rss')

@section('items')
    @foreach($pkgupdates as $update)
        @set('new', $update['new'] == 1 ? ' (new)' : '')

        @include('rss.item', [
            'title' => $update['pkgname'] . ' ' . $update['pkgver'] . '-' . $update['pkgrel'] . $new,
            'link' => 'https://archstrike.org/packages/' . $update['pkgname'],
            'date' => $update['date'],
            'body' => '',
            'body_markdown' => false
        ])
    @endforeach
@endsection
