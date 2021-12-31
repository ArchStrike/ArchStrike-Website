@extends('main')

@section('page')
    <div class="container-fluid page-container">
        <div class="row">
            <div class="col-xs-12">
                <h1>ArchStrike Downloads</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                @set('torrent_mirror', 'https://mirror.archstrike.org')
                @set('vm_mirror', 'https://uk.mirror.archstrike.org')

                @set('iso_minimal_name', env('ISO_MINIMAL_NAME'))
                @set('iso_minimal_variant', ucfirst(preg_replace([ '/^[^\-]*-/', '/-.*/' ], [ '', '' ], $iso_minimal_name)))
                @set('iso_minimal_release', preg_replace('/^.*-/', '', $iso_minimal_name))
                @set('iso_minimal32', $iso_minimal_name . '-i686.iso')
                @set('iso_minimal64', $iso_minimal_name . '.iso')

                <h2>Archstrike Live ISO</h2>
                <h3>Release Info</h3>
                <p>The image can be burned to a CD, mounted as an ISO file, or be directly written to a USB stick using a utility like <code>dd</code>.</p>

                <div class="space-above space-below">
                    <p><strong>{{ $iso_minimal_variant }} Release:</strong> {{ $iso_minimal_release }}</p>
                    <p><strong>ISO Size (x86_64):</strong> {{ env('ISO_MINIMAL_64_SIZE') }}</p>
                </div>
           

                {{--
                <h3>Torrent Download (Recommended)</h3>

                <div class="space-above">
                    <ul>
                        <li><a href="{{ $torrent_mirror }}/os/{{ $iso_minimal64 }}.torrent">{{ $iso_minimal_name }} (x86_64)</a></li>
                        <li><a href="{{ $torrent_mirror }}/os/{{ $iso_minimal32 }}.torrent">{{ $iso_minimal_name }} (i686)</a></li>
                    </ul>
                </div>

                <div class="space-above">
                    <ul>
                        <li><a href="{{ $torrent_mirror }}/os/{{ $iso64 }}.torrent">{{ $iso_name }} (x86_64)</a></li>
                        <li><a href="{{ $torrent_mirror }}/os/{{ $iso32 }}.torrent">{{ $iso_name }} (i686)</a></li>
                    </ul>
                </div>
                --}}

                <div id="downloads-form-container">
                    <h3>Direct Download</h3>

                    <form>
                        <select id="downloads-form-iso">
                            <option value="">Select an ISO</option>
                            <option value="{{ $iso_minimal64 }}">{{ $iso_minimal_name }} (64 bit)</option>
                            {{-- <option value="{{ $iso_minimal32 }}">{{ $iso_minimal_name }} (32 bit)</option> --}}
                            {{-- <option value="{{ $iso64 }}">{{ $iso_name }} (64 bit)</option> --}}
                            {{-- <option value="{{ $iso32 }}">{{ $iso_name }} (32 bit)</option> --}}
                        </select>

                        <select id="downloads-form-mirror">
                            <option value="">Select a Mirror</option>

                            @foreach($official_mirrors as $mirror)
                                <option value="{{ $mirror['url'] }}">Official - {{ $mirror['name'] }}</option>
                            @endforeach

                            @foreach($community_mirrors as $mirror)
                                <option value="{{ $mirror['url'] }}">{{ $mirror['name'] }}</option>
                            @endforeach
                        </select>

                        <input class="download-iso-submit" type="submit" value="Download ISO" />
                    </form>
                </div>

                <noscript>
                    <h3>Direct Download Links</h3>

                    <h4>{{ $iso_minimal_name }} x86_64 (64 bit)</h4>
                    <h5>Official Mirrors</h5>

                    <ul>
                        @foreach($official_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/new_iso/{{ $iso_minimal64 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h5>Community Mirrors</h5>

                    <ul>
                        @foreach($community_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/new_iso/{{ $iso_minimal64 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    {{--
                    <h4>{{ $iso_minimal_name }} i686 (32 bit)</h4>
                    <h5>Official Mirrors</h5>

                    <ul>
                        @foreach($official_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso_minimal32 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h5>Community Mirrors</h5>

                    <ul>
                        @foreach($community_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso_minimal32 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h4>{{ $iso_name }} x86_64 (64 bit)</h4>
                    <h5>Official Mirrors</h5>

                    <ul>
                        @foreach($official_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso64 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h5>Community Mirrors</h5>

                    <ul>
                        @foreach($community_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso64 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h4>{{ $iso_name }} i686 (32 bit)</h4>
                    <h5>Official Mirrors</h5>

                    <ul>
                        @foreach($official_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso32 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>

                    <h5>Community Mirrors</h5>

                    <ul>
                        @foreach($community_mirrors as $mirror)
                            <li><a href="{{ $mirror['url'] }}os/{{ $iso32 }}">{{ $mirror['name'] }}</a></li>
                        @endforeach
                    </ul>
                    --}}
                </noscript>

                <h3>Checksums</h3>

                <div class="space-above space-below">
                    <h4>{{ $iso_minimal64 }}</h4>
                    <p class="checksum"><strong>SHA256:</strong> <input class="click-select" type="text" value="{{ env('ISO_MINIMAL_64_256') }}" readonly /></p>
                    <p class="checksum"><strong>SHA512:</strong> <input class="click-select" type="text" value="{{ env('ISO_MINIMAL_64_512') }}" readonly /></p>
                </div>

                {{--
                <div class="space-below">
                    <h4>{{ $iso_minimal32 }}</h4>
                    <p class="checksum"><strong>SHA256:</strong> <input class="click-select" type="text" value="{{ env('ISO_MINIMAL_32_256') }}" readonly /></p>
                    <p class="checksum"><strong>SHA512:</strong> <input class="click-select" type="text" value="{{ env('ISO_MINIMAL_32_512') }}" readonly /></p>
                </div>

                <div class="space-below">
                    <h4>{{ $iso64 }}</h4>
                    <p class="checksum"><strong>SHA256:</strong> <input class="click-select" type="text" value="{{ env('ISO_64_256') }}" readonly /></p>
                    <p class="checksum"><strong>SHA512:</strong> <input class="click-select" type="text" value="{{ env('ISO_64_512') }}" readonly /></p>
                </div>

                <div class="space-below">
                    <h4>{{ $iso32 }}</h4>
                    <p class="checksum"><strong>SHA256:</strong> <input class="click-select" type="text" value="{{ env('ISO_32_256') }}" readonly /></p>
                    <p class="checksum"><strong>SHA512:</strong> <input class="click-select" type="text" value="{{ env('ISO_32_512') }}" readonly /></p>
                </div>
                --}}

                {{--
                <h2>ArchStrike VirtualBox &amp; VMWare OVA</h2>
                <h3>VirtualBox</h3>
                <p><a href="{{ $vm_mirror }}/ArchStrike.ova">Download Link</a></p>
                <p><strong>Size:</strong> {{ env('VBOX_SIZE') }}</p>
                <p class="checksum"><strong>SHA1:</strong> <input class="click-select" type="text" value="{{ env('VBOX_SHA') }}" readonly /></p>
                <p class="checksum"><strong>MD5:</strong> <input class="click-select" type="text" value="{{ env('VBOX_MD5') }}" readonly /></p>
                <h3>VMWare</h3>
                <p><a href="{{ $vm_mirror }}/ArchStrike-vmware.ova">Download Link</a></p>
                <p><strong>Size:</strong> {{ env('VMWARE_SIZE') }}</p>
                <p class="checksum"><strong>SHA1:</strong> <input class="click-select" type="text" value="{{ env('VMWARE_SHA') }}" readonly /></p>
                <p class="checksum"><strong>MD5:</strong> <input class="click-select" type="text" value="{{ env('VMWARE_MD5') }}" readonly /></p>
                <h3>General Information</h3>
                <p>The virtual disk is 50G with 15G used.</p>
                <p>To log in as root, use <code>root</code> for the username and <code>root</code> for the password.</p>
                <p>To log in as a standard user, use <code>archstrike</code> for the username and <code>archstrike</code> for the password.</p>
                --}}
            </div>
        </div>
    </div>
@endsection
