#!/usr/bin/env bash

[[ -d "$1" ]] || exit

git_repo="$1"
mirrorlist="$git_repo/archstrike/archstrike-mirrorlist/archstrike-mirrorlist"
script_location="$(readlink -f "$0")"
base_dir="${script_location%\/scripts\/*}"
output="$base_dir/app/GeneratedMirrorlist.php"
mirrorlist_type=official

printf '%s\n\n%s\n\n    %s\n    %s\n        %s\n' '<?php namespace App;' 'class GeneratedMirrorlist {' 'public static function getGeneratedMirrorlist()' '{' 'return [' > "$output"

while read -r; do
    if [[ "$REPLY" =~ ^Server\ =\ (https?)://([^/]*)(.*) ]]; then
        protocol="${BASH_REMATCH[1]}"
        url="${BASH_REMATCH[2]}"
        path="${BASH_REMATCH[3]}"
        unset location_code location

        while read -r; do
            [[ "$REPLY" =~ ^GeoIP\ Country\ Edition[^:]*:\ ([^,]*),\ *(.*)$ ]] && {
                location_code="${BASH_REMATCH[1]}"
                location="${BASH_REMATCH[2]}"
            }
        done < <(geoiplookup "$url")

        printf '            [ %s, %s, %s, %s, %s, %s ],\n' "'$protocol'" "'$url'" "'$path'" "'$mirrorlist_type'" "'$location_code'" "'$location'" >> "$output"
    elif [[ "$REPLY" =~ ^##\ Community\ Mirrors ]]; then
        mirrorlist_type=community
    fi
done < "$mirrorlist"

printf '        %s\n    %s\n\n%s\n' '];' '}' '}' >> "$output"

cd "$base_dir" || exit
php artisan update_mirrorlist
