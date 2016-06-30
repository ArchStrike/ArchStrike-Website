{{ Head::setTitle('News: ' . $title) }}
<h1 class="news-title">{{ $title }}</h1>
<div class="news-date">{{ $date }}</div>
<div class="news-body">{!! Parsedown::instance()->setMarkupEscaped(true)->parse($body) !!}</div>
