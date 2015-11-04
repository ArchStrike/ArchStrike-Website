@extends('main')

@section('page')
    <div id="build-logs">
        <h1>ArchStrike Builds</h1>
        <p>Click on the build status to see the log, or view the complete list of logs <a class="logs" href="http://archstrike.org:81/in-log">here</a></p>
        <input id="package-filter" class="search" placeholder="Filter Packages" />
        <table>
            <thead>
                <tr>
                    <th>Package Name</th>
                    <th>Repository</th>
                    <th>Status ARMv6</th>
                    <th>Status ARMv7</th>
                    <th>Status i686</th>
                    <th>Status x86_64</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach($buildlist as $build)
                    <tr>
                        <td class="package"><a href="/packages/{{ $build['package'] }}">{{ $build['package'] }}</a> <span class="version">{{ $build['pkgver'] }}-{{ $build['pkgrel'] }}</span></td>
                        <td class="repo package-status"><span class="label">Repository: </span><span class="repo-name">{{ $build['repo'] }}</span></td>

                        @foreach(['armv6', 'armv7', 'i686', 'x86_64'] as $index => $arch)
                            <td class="build-status package-status {{ strtolower($build[$arch]) }}">
                                @if(!is_null($build[$arch . '_log']))
                                    <a href="http://archstrike.org:81/in-log/{{ preg_replace('/\.gz$/', '', $build[$arch . '_log']) }}" target="_blank">
                                        <span class="label">{{ $arch }}: </span>
                                        <span class="status">{{ $build[$arch] }}</span>
                                        <span class="version">{{ preg_replace([ '/-[^-]*\.log\.html\.gz/', '/' . $build['package'] . '-/' ], [ '', '' ], $build[$arch . '_log']) }}</span>
                                    </a>
                                @else
                                    <div>
                                        <span class="label">{{ $arch }}: </span>
                                        <span class="status">{{ $build['i686'] }}</span>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
