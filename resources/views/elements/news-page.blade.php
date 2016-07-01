@set('body', Parsedown::instance()->setMarkupEscaped(true)->parse($body))
{{ Head::setTitle('News: ' . $title) }}
{{ Head::setDescription(preg_replace('/\s+/', ' ', strip_tags($body))) }}
<h1 class="news-title">{{ $title }}</h1>
<div class="news-date">{{ $date }}</div>
<div class="news-body">{!! $body !!}</div>
