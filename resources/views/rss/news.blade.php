@extends('rss')

@section('items')
    @foreach($news_items as $news_item)
        @include('news.' . $news_item, [
            'include' => 'rss.item',
            'link' => URL::to('/') . '/news/' . $news_item,
            'body_markdown' => true
        ])
    @endforeach
@endsection
