@foreach($pkgupdates as $update)
    @if($loop->index1 <= 6)
        <div class="sidebar-box-item">
            <a href="/packages/{{ $update['pkgname'] }}">{{ $update['pkgname'] }}</a>
            {{ $update['pkgver'] }}-{{ $update['pkgrel'] }}
            {!! $update['new'] == 1 ? '<span class="new">(new)</span>' : '' !!}
            {{-- <span class="date">{{ $update['date'] }}</span> --}}
        </div>
    @else
        @break
    @endif
@endforeach
