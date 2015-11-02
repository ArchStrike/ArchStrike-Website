@extends('main')

@section('page')

<div class="container page-container">
    @if($package === false)
        <div class="row error-row">
            <div class="col-xs-12 col-md-10 error-column">
                <div class="page-wrapper">
                    <a href="/" title="Home"><img src="/img/archstrike.svg" class="img-responsive small-logo" /></a>
                    <div class="error">Package Does Not Exist</div>
                </div>
            </div>
        </div>
    @elseif($package === true)
        @if($packages !== false)
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1 column">
                    <h1>Packages</h1>

                    <table class="packages-table">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td><a href="/packages/{{ $package['pkgname'] }}">{{ $package['pkgname'] }}</a></td>
                                    <td>{{ $package['pkgdesc'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <p class="page-list">
                        Page:
                        @for($x = 0; $x <= $pages; $x++)
                            @if($x == $page)
                                {{ $x }}
                            @else
                                <a href="/packages/page/{{ $x }}">{{ $x }}</a>
                            @endif
                        @endfor
                    </p>
                </div>
            </div>
        @else
            <div class="row error-row">
                <div class="col-xs-12 col-md-10 error-column">
                    <div class="page-wrapper">
                        <a href="/" title="Home"><img src="/img/archstrike.svg" class="img-responsive small-logo" /></a>
                        <div class="error">Page Does Not Exist</div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1 column">
                <h1>{{ $package->pkgname }}</h1>
                <table class="package-table">
                    <tbody>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $pkgdesc }}</td>
                        </tr>
                        <tr>
                            <th>Repository:</th>
                            <td>{{ $package->repo }}</td>
                        </tr>
                        <tr>
                            <th>Version:</th>
                            <td>{{ $package->pkgver }}-{{ $package->pkgrel }}</td>
                        </tr>
                        @if(!empty($package->provides))
                            <tr>
                                <th>Provides:</th>
                                <td>{{ $package->provides }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th>Dependencies:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($package->depends))
                            @foreach(explode(' ', $package->depends) as $dep)
                                <tr>
                                    <td>{{ $dep }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @if(!empty($package->makedepends))
                            @foreach(explode(' ', $package->makedepends) as $dep)
                                <tr>
                                    <td>{{ $dep }} (build)</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@endsection
