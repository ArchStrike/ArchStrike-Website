<div class="news-item">
    <div class="news-heading">
        <h2 class="news-title"><a href="/news/{{ $news_item }}">{{ $title }}</a></h2>
        <div class="news-date">{{ $date }}</div>
    </div>

    <div class="news-body">
        {!! Parsedown::instance()->setMarkupEscaped(true)->parse($body) !!}
    </div>
</div>
