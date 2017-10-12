<?php namespace App;

class Team {

    public static $team = [
        [
            'nick'   => 'BartoCH',
            'name'   => 'Vincent Loup',
            'email'  => 'BartoCH@archstrike.org',
            'github' => 'https://github.com/vloup',
            'pgp'    => 'C177 DF0E 1E7B DC3F 7C82 153A E9E0 21DE FFEC FFE9',
            'status' => true
        ],

        [
            'nick'   => 'xorond',
            'name'   => 'Oğuz Bektaş',
            'email'  => 'xorond@archstrike.org',
            'github' => 'https://github.com/xorond',
            'pgp'    => 'F298 2C7A 1270 D374 73EF 0444 CA43 8D35 D819 47C0',
            'status' => true
        ],

        [
            'nick'   => 'cthulu201',
            'name'   => 'Michael Henze',
            'email'  => 'cthulu201@archstrike.org',
            'github' => 'https://github.com/Cthulu201',
            'pgp'    => '75D2 2FC9 E91D 0FA7 6D60 0701 698D B1CE 0D47 E88B',
            'status' => true
        ],

        [
            'nick'   => 'prurigro',
            'name'   => 'Kevin MacMartin',
            'email'  => 'prurigro@archstrike.org',
            'github' => 'https://github.com/prurigro',
            'pgp'    => '9D5F 1C05 1D14 6843 CDA4 858B DE64 825E 7CBC 0D51',
            'status' => true
        ],

        [
            'nick'   => 'comrumino',
            'name'   => 'James Stronz',
            'email'  => 'comrumino@archstrike.org',
            'github' => 'https://github.com/c',
            'pgp'    => '9DAC 07EC 8F2C 4B67 489F CEC1 A712 FE63 286C A73E',
            'status' => true
        ],

        [
            'nick'   => 'd1rt',
            'name'   => 'Chad Seaman',
            'email'  => 'd1rt@archstrike.org',
            'github' => 'https://github.com/chadillac',
            'pgp'    => '1EE9 6459 403B 60D1 E7E7 B7FD 7B4A BD99 A40B 98BA',
            'status' => true
        ],

        [
            'nick'   => 'wh1t3fox',
            'name'   => 'Craig West',
            'email'  => 'wh1t3fox@archstrike.org',
            'github' => 'https://github.com/wh1t3fox',
            'pgp'    => '2684 FFAF ACA8 A554 9C54 DA90 7DDB 5DE1 2A3F 5213',
            'status' => false
        ],

        [
            'nick'   => 'arch3y',
            'name'   => 'Tyler Bennett',
            'github' => 'https://github.com/archey',
            'status' => false
        ]
    ];

    public static function getCurrentTeamMembers()
    {
        $current_team = [];

        foreach (self::$team as $member) {
            if ($member['status']) {
                array_push($current_team, $member);
            }
        }

        return $current_team;
    }

    public static function getFormerTeamMembers()
    {
        $former_team = [];

        foreach (self::$team as $member) {
            if (!$member['status']) {
                array_push($former_team, $member);
            }
        }

        return $former_team;
    }

}
