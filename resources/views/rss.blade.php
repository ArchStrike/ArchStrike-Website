<?xml version="1.0" encoding="UTF-8"?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><![CDATA[{{ $title }}]]></title>
        <description>{{ $description }}</description>
        <link>https://archstrike.org</link>
        <atom:link href="{{ $feed_url }}" rel="self" type="application/rss+xml" />

        <image>
            <title><![CDATA[{{ $title }}]]></title>
            <url>https://archstrike.org/img/archstrike-small.png</url>
            <link>https://archstrike.org</link>
        </image>

        @yield('items')
    </channel>
</rss>
