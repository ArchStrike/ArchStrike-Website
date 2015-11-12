@extends('main')

@section('page')
    <div id="build-logs">
        <h1>ArchStrike Build Issues</h1>
        <p>Click on the build status to see the log, or view the complete list of logs <a class="logs" href="http://archstrike.org:81/in-log">here</a></p>
        <input id="package-filter" class="search" placeholder="Filter Packages" />
        <table>
            <thead>
                <tr>
                    <th class="sort" data-sort="package-sort">Package Name</th>
                    <th class="sort" data-sort="repo-sort">Repository</th>
                    <th class="sort" data-sort="armv6-sort">Status ARMv6h</th>
                    <th class="sort" data-sort="armv7-sort">Status ARMv7h</th>
                    <th class="sort" data-sort="i686-sort">Status i686</th>
                    <th class="sort" data-sort="x86_64-sort">Status x86_64</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach($buildlist as $build)
                    <tr>
                        <td class="package"><a href="/packages/{{ $build['package'] }}" class="package-sort">{{ $build['package'] }}</a> <span class="version">{{ $build['pkgver'] }}-{{ $build['pkgrel'] }}</span></td>
                        <td class="repo package-status"><span class="label">Repository: </span><span class="repo-name repo-sort">{{ $build['repo'] }}</span></td>

                        @foreach(['armv6', 'armv7', 'i686', 'x86_64'] as $index => $arch)
                            <td class="build-status package-status {{ strtolower($build[$arch]) }}">
                                @if($build[$arch] != 'Skip' && !is_null($build[$arch . '_log']))
                                    <a href="http://archstrike.org:81/in-log/{{ preg_replace('/\.gz$/', '', $build[$arch . '_log']) }}" target="_blank">
                                        <span class="label">{{ $arch }}: </span>
                                        <span class="status {{ $arch }}-sort">{{ $build[$arch] }}</span>
                                        <span class="version">{{ preg_replace([ '/-[^-]*\.log\.html\.gz/', '/' . $build['package'] . '-/' ], [ '', '' ], $build[$arch . '_log']) }}</span>
                                    </a>
                                @else
                                    <div>
                                        <span class="label">{{ $arch }}: </span>
                                        <span class="status {{ $arch }}-sort">{{ $build[$arch] }}</span>
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
