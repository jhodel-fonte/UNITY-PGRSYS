<?php

// include_once __DIR__ .'/app/functions/passwordF.php';
// echo securePassword('12345678');
include_once __DIR__ .'/app/database/res_teams.php';
$teamClass = new Teams();
// $teams = $teamClass->getAllTeams();
$members = $teamClass->getAllTeamMembers();

var_dump($members);

?>