@extends('main')

@section('page')
    <div class="container-fluid page-container">
        <div class="row">
            <div class="col-xs-12">
                <h1>ArchStrike Mirrorlist</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="info">
                    <div class="info-container">
                        <p>This page generates custom mirrorlists using the set of mirrors in the latest archstrike-mirrorlist package.</p>
                        <p>You can either choose to download the complete set of mirrors, or create a customized list using your own filters. Selecting nothing in a given category will generate a mirrorlist with all of the options from that category.</p>
                        <p>Once you've generated your mirrorlist, save it to <code>/etc/pacman.d/archstrike-mirrorlist</code> and ensure your ArchStrike repositories in <code>/etc/pacman.conf</code> are configured with <code>Include = /etc/pacman.d/archstrike-mirrorlist</code>.</p>
                        <p><strong>NOTE:</strong> Some combinations will generate mirrorlists with no mirrors.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Download mirrorlist with all available mirrors</h2>

                <ul>
                    <li><a href="/mirrorlist/generate" target="_blank">All mirrors</a></li>

                    @if(sizeof($protocols) >= 2)
                        @foreach($protocols as $protocol)
                            <li><a href="/mirrorlist/generate?p={{ $protocol }}" target="_blank">All mirrors, {{ strtoupper($protocol) }} only</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Generate custom mirrolist</h2>

                <form id="custom-mirrorlist">
                    <fieldset>
                        <legend>Protocols:</legend>

                        @foreach($protocols as $protocol)
                            <div class="field">
                                <input class="protocol-checkbox" type="checkbox" value="{{ $protocol }}" name="protocol-{{ $protocol }}">
                                <label for="protocol-{{ $protocol }}">{{ strtoupper($protocol) }}</label>
                            </div>
                        @endforeach
                    </fieldset>

                    <fieldset>
                        <legend>Types:</legend>

                        @foreach($types as $type)
                            <div class="field">
                                <input class="type-checkbox" type="checkbox" value="{{ $type }}" name="type-{{ $type }}">
                                <label for="type-{{ $type }}">{{ ucfirst($type) }}</label>
                            </div>
                        @endforeach
                    </fieldset>

                    <fieldset>
                        <legend>Countries:</legend>

                        @foreach($countries as $country)
                            <div class="field">
                                <input class="country-checkbox" type="checkbox" value="{{ $country[0] }}" name="country-{{ $country[0] }}">
                                <label for="country-{{ $country[0] }}">{{ $country[1] }}</label>
                            </div>
                        @endforeach
                    </fieldset>

                    <input class="generate-mirrorlist" type="submit" value="Generate Mirrolist" />
                </form>
            </div>
        </div>
    </div>
@endsection
