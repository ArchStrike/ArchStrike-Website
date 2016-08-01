#!/usr/bin/env bash

[[ -d "$1" ]] || exit

git_repo="$1"
script_location="$(readlink -f "$0")"
output="${script_location%\/scripts\/*}"/resources/views/generated/pkgupdates.blade.php
pkg_total=20

function gen_updates {
    count=0

    pushd "$git_repo" >/dev/null
    printf '%s\n' "@set('pkgupdates', ["

    while read -r commit; do
        pkgbuild_list="$(git diff-tree --no-commit-id --name-only -r "$commit" | grep PKGBUILD)"

        while read -r pkg; do
            diff="$(git show "$commit" --date=iso -- "$pkg")"

            [[ -f "$pkg" ]] && egrep -q '^\+pkg(ver|rel)' <<< "$diff" && {
                unset pkgname pkgver pkgrel date info
                eval "$(egrep '^\s*(_pkgname|pkgname|pkgver|pkgrel)=' "$pkg")"
                date="$(egrep '^Date' <<< "$diff" | sed 's|^Date:\s*||;s|\s.*||')"
                package_count="$(egrep -c "^[^/]*/$pkgname/PKGBUILD$" <<< "$pkgbuild_list")"

                if (( package_count > 1 )); then
                    info=2
                elif egrep -q '^-pkg(ver|rel)' <<< "$diff"; then
                    info=0
                else
                    info=1
                fi

                printf "    [ 'pkgname' => '%s', 'pkgver' => '%s', 'pkgrel' => '%s', 'date' => '%s', 'info' => '%s' ]" \
                    "$pkgname" "$pkgver" "$pkgrel" "$date" "$info"

                if (( count++ == pkg_total )); then
                    printf '\n%s\n' '])'
                    printf '\n%s\n' '@include($blade, [ '"'pkgupdates'"' => $pkgupdates ])'
                    return
                else
                    printf '%s\n' ','
                fi
            }
        done <<< "$pkgbuild_list"
    done < <(git log | egrep '^commit ' | sed 's|^commit ||')

    popd >/dev/null
}

pkgupdates="$(gen_updates)"
printf '%s\n' "$pkgupdates" > "$output"
