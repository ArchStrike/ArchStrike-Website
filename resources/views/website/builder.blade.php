@extends('main')

@section('page')
    <div id="build-logs">
        <h1>ArchStrike Builds</h1>
        <p>Click on the build status to see the log, or view the complete list of logs <a class="logs" href="http://archstrike.org:81/in-log">here</a></p>
        <table>
            <thead>
                <tr>
                    <th>Package Name</th>
                    <th>Status ARMv6</th>
                    <th>Status ARMv7</th>
                    <th>Status i686</th>
                    <th>Status x86_64</th>
                </tr>
            </thead>
            <tbody>
                @cache('buildlogs', 5)
                    @foreach(DB::table('abs')->select('id', 'package', 'pkgver', 'pkgrel')->orderBy('package', 'asc')->get() as $package)
                        <tr>
                            <td><span>{{ $package->package }} <div>{{ $package->pkgver }}-{{ $package->pkgrel }}</div></span></td>

                            @foreach(['armv6', 'armv7', 'i686', 'x86_64'] as $arch)
                                @foreach(DB::table($arch)->select('done', 'fail', 'log')->where('id', $package->id)->get() as $status)
                                    @if($status->fail == 1)
                                        @set('status_val','Fail')
                                    @elseif($status->done == 1)
                                        @set('status_val','Done')
                                    @else
                                        @set('status_val','Incomplete')
                                    @endif

                                    <td class="{{ strtolower($status_val) }}">
                                        @if(!is_null($status->log))
                                            <a href="http://archstrike.org:81/in-log/{{ preg_replace('/\.gz$/', '', $status->log) }}" target="_blank">
                                        @else
                                            <span>
                                        @endif

                                        {{-- set the status to failed, done or incomplete --}}
                                        {{ $status_val }}

                                        {{-- close the link to the log file if it exists --}}
                                        @if(!is_null($status->log))
                                            <div>{{ preg_replace([ '/-[^-]*\.log\.html\.gz/', '/' . $package->package . '-/' ], [ '', '' ], $status->log) }}</div></a>
                                        @else
                                            </span>
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
