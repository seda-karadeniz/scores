<?php

define('TODAY', (new DateTime('now', new DateTimeZone('Europe/Brussels')))->format('M jS, Y')); /*M=month, j= jour sans le 0 devant S= suffix exemple th ou nd Y= year 4chiffre */
define('FILE_PATH', 'matches.csv');
$matches = [];
$standings = [];
$teams = [];

function getEmptyStatsArray(){
    return [
        'games' => 0,
        'points' => 0,
        'wins' => 0,
        'losses' => 0,
        'draws' => 0,
        'GF' => 0,
        'GA' => 0,
        'GD' => 0,
    ];
}


$handle = fopen(FILE_PATH,'r'); /*file open .. r=read*/
$headers = fgetcsv($handle,1000);
while ($line = fgetcsv($handle,1000)){
    $match = array_combine($headers, $line); /*affecter ca au tableau comme push en js*/
    $matches[] = $match;
    $homeTeam = $match['home-team']; /*en gros celui qui a la clé home team */
    $awayTeam = $match['away-team'];
    /*dans le tableau des equipe sil ya deja la home team*/
    /*le if en dessus -> si la home team ne fais pas partie du tableau teams -> !in_array($homeTeam, $teams)*/

    if (!array_key_exists($homeTeam, $standings)){
        $standings[$homeTeam]= getEmptystatsArray();
    }
    if (!array_key_exists($awayTeam, $standings)){
        $standings[$awayTeam]= getEmptystatsArray();
    }
    $standings[$homeTeam]['games']++;
    $standings[$awayTeam]['games']++;
    /*incrementation dans la clé des jeu*/

    if ($match['home-team-goals'] === $match['away-team-goals']) {
        $standings[$homeTeam]['points']++;
        $standings[$awayTeam]['points']++;
        $standings[$homeTeam]['draws']++;
        $standings[$awayTeam]['draws']++;
    } elseif ($match['home-team-goals'] > $match['away-team-goals']) {
        $standings[$homeTeam]['points'] += 3;
        $standings[$homeTeam]['wins']++;
        $standings[$awayTeam]['losses']++;
    } else {
        $standings[$awayTeam]['points'] += 3;
        $standings[$awayTeam]['wins']++;
        $standings[$homeTeam]['losses']++;
    }
    $standings[$homeTeam]['GF'] += $match['home-team-goals'];
    $standings[$homeTeam]['GA'] += $match['away-team-goals'];
    $standings[$awayTeam]['GF'] += $match['away-team-goals'];
    $standings[$awayTeam]['GA'] += $match['home-team-goals'];
    $standings[$homeTeam]['GD'] = $standings[$homeTeam]['GF'] - $standings[$homeTeam]['GA'];
    $standings[$awayTeam]['GD'] = $standings[$awayTeam]['GF'] - $standings[$awayTeam]['GA'];
}

uasort($standings, function ($a, $b){
    if ($a['points'] === $b['points']){
        //si les points de la premiere equipe sont egale a la deuxieme
        return 0;
    }
    return $a['points'] > $b['points'] ? -1: 1;
});

$teams = array_keys($standings);
sort($teams);
/*toutes les valeur des equipes sont dans standings alors on met tte les clé dans teams*/


require('vue.php');
/*
define('TODAY', (new DateTime('now', new DateTimeZone('Europe/Brussels')))->format('M jS, Y'));
define('FILE_PATH', 'matches.csv');
$matches = [];
$standings = [];
$teams = [];

function getEmptyStatsArray()
{
    return [
        'games' => 0,
        'points' => 0,
        'wins' => 0,
        'losses' => 0,
        'draws' => 0,
        'GF' => 0,
        'GA' => 0,
        'GD' => 0,
    ];
}

$handle = fopen(FILE_PATH, 'r');
$headers = fgetcsv($handle, 1000);

while ($line = fgetcsv($handle, 1000)) {
    $match = array_combine($headers, $line);
    $matches[] = $match;
    $homeTeam = $match['home-team'];
    $awayTeam = $match['away-team'];
    if (!array_key_exists($homeTeam, $standings)) {
        $standings[$homeTeam] = getEmptyStatsArray();
    }
    if (!array_key_exists($awayTeam, $standings)) {
        $standings[$awayTeam] = getEmptyStatsArray();
    }
    $standings[$homeTeam]['games']++;
    $standings[$awayTeam]['games']++;

    if ($match['home-team-goals'] === $match['away-team-goals']) {
        $standings[$homeTeam]['points']++;
        $standings[$awayTeam]['points']++;
        $standings[$homeTeam]['draws']++;
        $standings[$awayTeam]['draws']++;
    } elseif ($match['home-team-goals'] > $match['away-team-goals']) {
        $standings[$homeTeam]['points'] += 3;
        $standings[$homeTeam]['wins']++;
        $standings[$awayTeam]['losses']++;
    } else {
        $standings[$awayTeam]['points'] += 3;
        $standings[$awayTeam]['wins']++;
        $standings[$homeTeam]['losses']++;
    }
    $standings[$homeTeam]['GF'] += $match['home-team-goals'];
    $standings[$homeTeam]['GA'] += $match['away-team-goals'];
    $standings[$awayTeam]['GF'] += $match['away-team-goals'];
    $standings[$awayTeam]['GA'] += $match['home-team-goals'];
    $standings[$homeTeam]['GD'] = $standings[$homeTeam]['GF'] - $standings[$homeTeam]['GA'];
    $standings[$awayTeam]['GD'] = $standings[$awayTeam]['GF'] - $standings[$awayTeam]['GA'];

}

uasort($standings, function ($a, $b) {
    if ($a['points'] === $b['points']) {
        return 0;
    }
    return $a['points'] > $b['points'] ? -1 : 1;
});

$teams = array_keys($standings);
sort($teams);

require('vue.php');*/
