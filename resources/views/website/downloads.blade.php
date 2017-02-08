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
                @set('iso_name', env('ISO_NAME'))
                @set('iso_release', preg_replace('/^.*-/', '', $iso_name))
                @set('iso32', $iso_name . '-i686.iso')
                @set('iso64', $iso_name . '-x86_64.iso')
                @set('torrent_mirror', 'https://mirror.archstrike.org')
                @set('vm_mirror', 'https://uk.mirror.archstrike.org')

                <h2>Archstrike Live ISO</h2>
                <h3>Release Info</h3>
                <p>The image can be burned to a CD, mounted as an ISO file, or be directly written to a USB stick using a utility like <code>dd</code>.</p>

                <p><strong>Current Release:</strong> {{ $iso_release }}</p>
                <p><strong>ISO Size (x86_64):</strong> {{ env('ISO_64_SIZE') }}</p>
                <p><strong>ISO Size (i686):</strong> {{ env('ISO_32_SIZE') }}</p>
                <p><a href="/wiki/setup">Installation Guide</a></p>

                <ul>
                    <li>To log in as root, use <code>root</code> for the <strong>username</strong> <em>and</em> <strong>password</strong></li>
                    <li>To log in as standard user, use <code>archstrike</code> for the <strong>username</strong> <em>and</em> <strong>password</strong></li>
                </ul>

                <h3>Torrent Download (Recommended)</h3>

                <ul>
                    <li><a href="{{ $torrent_mirror }}/os/{{ $iso64 }}.torrent">x86_64</a></li>
                    <li><a href="{{ $torrent_mirror }}/os/{{ $iso32 }}.torrent">i686</a></li>
                </ul>

                <h3>Direct Download Links</h3>
                <h4>x86_64 (64 bit)</h4>
                <h5>Official Mirrors for x86_64</h5>

                <ul>
                    @foreach($official_mirrors as $mirror)
                        <li><a href="{{ $mirror['url'] }}os/{{ $iso64 }}">{{ $mirror['name'] }}</a></li>
                    @endforeach
                </ul>

                <h5>Community Mirrors for x86_64</h5>

                <ul>
                    @foreach($community_mirrors as $mirror)
                        <li><a href="{{ $mirror['url'] }}os/{{ $iso64 }}">{{ $mirror['name'] }}</a></li>
                    @endforeach
                </ul>

                <h4>i686 (32 bit)</h4>
                <h5>Official Mirrors for i686</h5>

                <ul>
                    @foreach($official_mirrors as $mirror)
                        <li><a href="{{ $mirror['url'] }}os/{{ $iso32 }}">{{ $mirror['name'] }}</a></li>
                    @endforeach
                </ul>

                <h5>Community Mirrors for i686</h5>

                <ul>
                    @foreach($community_mirrors as $mirror)
                        <li><a href="{{ $mirror['url'] }}os/{{ $iso32 }}">{{ $mirror['name'] }}</a></li>
                    @endforeach
                </ul>

                <h3>Checksums</h3>
                <h4>{{ $iso64 }}</h4>
                <p class="checksum"><strong>SHA256:</strong> <input type="text" value="{{ env('ISO_64_256') }}" readonly /></p>
                <p class="checksum"><strong>SHA512:</strong> <input type="text" value="{{ env('ISO_64_512') }}" readonly /></p>
                <h4>{{ $iso32 }}</h4>
                <p class="checksum"><strong>SHA256:</strong> <input type="text" value="{{ env('ISO_32_256') }}" readonly /></p>
                <p class="checksum"><strong>SHA512:</strong> <input type="text" value="{{ env('ISO_32_512') }}" readonly /></p>

                <h2>ArchStrike VirtualBox &amp; VMWare OVA</h2>
                <h3>VirtualBox</h3>
                <p><a href="{{ $vm_mirror }}/ArchStrike.ova">Download Link</a></p>
                <p><strong>Size:</strong> {{ env('VBOX_SIZE') }}</p>
                <p class="checksum"><strong>SHA1:</strong> <input type="text" value="{{ env('VBOX_SHA') }}" readonly /></p>
                <p class="checksum"><strong>MD5:</strong> <input type="text" value="{{ env('VBOX_MD5') }}" readonly /></p>
                <h3>VMWare</h3>
                <p><a href="{{ $vm_mirror }}/ArchStrike-vmware.ova">Download Link</a></p>
                <p><strong>Size:</strong> {{ env('VMWARE_SIZE') }}</p>
                <p class="checksum"><strong>SHA1:</strong> <input type="text" value="{{ env('VMWARE_SHA') }}" readonly /></p>
                <p class="checksum"><strong>MD5:</strong> <input type="text" value="{{ env('VMWARE_MD5') }}" readonly /></p>
                <h3>General Information</h3>
                <p>The virtual disk is 50G with 15G used.</p>
                <p>To log in as root, use <code>root</code> for the username and <code>root</code> for the password.</p>
                <p>To log in as a standard user, use <code>archstrike</code> for the username and <code>archstrike</code> for the password.</p>
            </div>
        </div>
    </div>
@endsection
