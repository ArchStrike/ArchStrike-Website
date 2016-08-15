@set('pkg_count', 1)

@foreach($pkgupdates as $update)
    @if($pkg_count <= 6)
        @if(App\Abs::exists($update['pkgname']))
            @set('pkg_count', $pkg_count + 1)

            <div class="sidebar-box-item">
                <a href="/packages/{{ $update['pkgname'] }}">{{ $update['pkgname'] }}</a>
                {{ $update['pkgver'] }}-{{ $update['pkgrel'] }}

                @if($update['info'] == 1)
                    <span class="info">(new)</span>
                @endif

                {{-- <span class="date">{{ $update['date'] }}</span> --}}
            </div>
        @endif
    @else
        @break
    @endif
@endforeach
