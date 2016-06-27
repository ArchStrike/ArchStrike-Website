<div class="news-item">
    <div class="news-heading">
        <h2 class="news-title">{{ $title }}</h2>
        <div class="news-date">{{ $date }}</div>
    </div>

    <div class="news-body">
        {!! Parsedown::instance()->setMarkupEscaped(true)->parse($body) !!}
    </div>
</div>
