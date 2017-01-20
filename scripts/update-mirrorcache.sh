#!/usr/bin/env bash

[[ -d "$1" ]] || exit

git_repo="$1"
mirrorlist="$git_repo/archstrike/archstrike-mirrorlist/archstrike-mirrorlist"
script_location="$(readlink -f "$0")"
output="${script_location%\/scripts\/*}/storage/generated/mirrorlist.php"
mirrorlist_type=official

printf '%s\n\n%s\n' '<?php' '$mirrorlist = [' > "$output"

while read -r; do
    if [[ "$REPLY" =~ ^Server\ =\ (https?)://([^/]*) ]]; then
        protocol="${BASH_REMATCH[1]}"
        url="${BASH_REMATCH[2]}"
        unset location

        while read -r; do
            if [[ "$REPLY" =~ ^GeoIP\ Country\ Edition[^:]*:\ (.*)$ ]]; then
                location="${BASH_REMATCH[1]}"
            elif [[ ! "$REPLY" = *'N/A'* && "$REPLY" =~ ^GeoIP\ City\ Edition[^:]*:\ ([^0-9]*),\ [0-9].*$ ]]; then
                location="${BASH_REMATCH[1]}"
                break
            fi
        done < <(geoiplookup "$url")

        printf '    [ %s, %s, %s ],\n' "'$protocol://$url'" "'$mirrorlist_type'" "'$location'" >> "$output"
    elif [[ "$REPLY" =~ ^##\ Community\ Mirrors ]]; then
        mirrorlist_type=community
    fi
done < "$mirrorlist"

printf '%s\n' '];' >> "$output"
