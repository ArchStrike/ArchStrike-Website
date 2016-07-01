@extends('rss')

@section('items')
    @foreach($news_items as $news_item)
        @include('news.' . $news_item, [
            'include' => 'rss.item',
            'link' => 'https://archstrike.org/news/' . $news_item,
            'body_markdown' => true
        ])
    @endforeach
@endsection
