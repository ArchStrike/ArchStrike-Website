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
                {{-- DISABLE CACHE FOR DEBUGGING --}}
                <?php //Flatten::flushSection('buildlogs') ?>

                @cache('buildlogs', 5)
                    @foreach(Cache::remember('packages', 5, function() { return DB::table('abs')->select('id', 'package', 'repo', 'pkgver', 'pkgrel')->where('del', 0)->orderBy('package', 'asc')->get(); }) as $package)
                        <tr>
                            <td class="package">{{ $package->package }} <span class="version">{{ $package->pkgver }}-{{ $package->pkgrel }}</span></td>
                            <td class="repo package-status"><span class="label">Repository: </span><span class="repo-name">{{ $package->repo }}</span></td>

                            @foreach(['armv6', 'armv7', 'i686', 'x86_64'] as $index => $arch)
                                @foreach(DB::table($arch)->select('done', 'fail', 'log')->where('id', $package->id)->get() as $status)
                                    @if($status->fail == 1)
                                        @set('status_val','Fail')
                                    @elseif($status->done == 1)
                                        @set('status_val','Done')
                                    @else
                                        @set('status_val','Incomplete')
                                    @endif

                                    <td class="build-status package-status {{ strtolower($status_val) }}">
                                        @if(!is_null($status->log))
                                            <a href="http://archstrike.org:81/in-log/{{ preg_replace('/\.gz$/', '', $status->log) }}" target="_blank">
                                                <span class="label">{{ $arch }}: </span>
                                                <span class="status">{{ $status_val }}</span>
                                                <span class="version">{{ preg_replace([ '/-[^-]*\.log\.html\.gz/', '/' . $package->package . '-/' ], [ '', '' ], $status->log) }}</span>
                                            </a>
                                        @else
                                        <div>
                                            <span class="label">{{ $arch }}: </span>
                                            <span class="status">{{ $status_val }}</span>
                                        </div>
                                        @endif
                                    </td>
                                @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                @endcache
            </tbody>
        </table>
    </div>
@endsection
