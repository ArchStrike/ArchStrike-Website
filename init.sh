#!/usr/bin/env bash

# dependencies
deps=('bower' 'composer' 'gulp' 'npm' 'php')

# Colour scheme
[[ -t 1 ]] && {
    c_d=$'\e[1;30m' # DARK GREY
    c_w=$'\e[1;37m' # WHITE
    c_b=$'\e[1;34m' # BLUE
    c_g=$'\e[1;32m' # GREEN
    c_m=$'\e[1;35m' # MAGENTA
    c_r=$'\e[1;31m' # RED
    c_t=$'\e[1;36m' # TEAL
    c_y=$'\e[1;33m' # YELLOW
    c_c=$'\e[0m'    # CLEAR
}

# Display a formatted message
function msg {
    printf '%s %s\n' "$c_b==>" "$c_w$1$c_c"
}

function error {
    printf '%s\n' "${c_r}ERROR${c_w}: $1$c_c" >&2
    exit 1
}

# Check for missing dependencies
declare -a missing_deps=()
for dep in "${deps[@]}"; do
    type -P "$dep" >/dev/null \
        || missing_deps=( ${missing_deps[@]} "$dep" )
done
[[ -n "${missing_deps[*]}" ]] && {
    error "${c_w}missing dependencies ($(
        for (( x=0; x < ${#missing_deps[@]}; x++ )); do
            printf '%s' "$c_m${missing_deps[$x]}$c_c"
            (( (( x + 1 )) < ${#missing_deps[@]} )) && printf '%s' ', '
        done
    )$c_w)"
}

# Exit with an error on ctrl-c
trap 'error "script killed"' SIGINT SIGQUIT

[[ ! -f .env ]] && {
    msg "Copying ${c_y}.env.example$c_w to ${c_y}.env$c_w with a randomly generated ${c_g}APP_KEY"
    sed 's|^APP_KEY=.*|APP_KEY='"$(</dev/urandom tr -dc A-Za-z0-9 | head -c"${1:-32}")"'|' .env.example > .env
    exit
}

msg "Running: ${c_m}composer installl --no-dev"
composer install --no-interaction --no-dev || error "${c_m}composer install --no-interaction --no-dev$c_w exited with an error status"

msg "Running: ${c_m}php artisan migrate"
php artisan migrate || error "${c_m}php artisan migrate$c_w exited with an error status"

msg "Running: ${c_m}npm install"
npm install || error "${c_m}npm install$c_w exited with an error status"

msg "Running: ${c_m}bower install"
bower install || error "${c_m}bower install$c_w exited with an error status"

msg "Running: ${c_m}gulp --production"
gulp --production || error "${c_m}gulp --production$c_w exited with an error status"

