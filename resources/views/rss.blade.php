<?xml version="1.0" encoding="UTF-8"?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><![CDATA[{{ $title }}]]></title>
        <description>{{ $description }}</description>
        <link>{{ URL::to('/') }}</link>
        <atom:link href="{{ URL::to('/') . $feed_url }}" rel="self" type="application/rss+xml" />

        <image>
            <title><![CDATA[{{ $title }}]]></title>
            <url>{{ URL::to('/') }}/img/archstrike-small.png</url>
            <link>{{ URL::to('/') }}</link>
        </image>

        @yield('items')
    </channel>
</rss>
