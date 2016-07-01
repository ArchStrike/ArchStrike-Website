<item>
    <title>{{ $title }}</title>
    <link>{{ $link }}</link>
    <pubDate>{{ date('r', strtotime($date)) }}</pubDate>

    <description>
        @if($body_markdown)
            {{ Parsedown::instance()->setMarkupEscaped(true)->parse($body) }}
        @else
            {{ $body }}
        @endif
    </description>
</item>
