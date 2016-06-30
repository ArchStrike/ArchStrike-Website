{{ Head::setTitle('News: ' . $title) }}
<h1>{{ $title }}</h1>
<div class="date">{{ $date }}</div>
<p>{!! Parsedown::instance()->setMarkupEscaped(true)->parse($body) !!}</p>
