<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/res_teams.php';
require_once __DIR__ . '/../src/utillities/log.php';

try {
    $teamClass = new Teams();
    $teams = $teamClass->getAllTeams();

    $members = $teamClass->getAllTeamMembers();
    
    if (isset($teams['success']) && $teams['success'] == false ) {
        throw new Exception('Failed to fetch teams');
    }

    if (isset($members['success']) && $members['success'] == false ) {
        throw new Exception('Failed to fetch team members');
    }

    // Group members by team_id
    $membersByTeam = [];
    if (is_array($members)) {
        foreach ($members as $member) {
            $teamId = $member['team_id'] ?? null;
            if (!$teamId) {
                continue;
            }
            if (!isset($membersByTeam[$teamId])) {
                $membersByTeam[$teamId] = [];
            }
            $membersByTeam[$teamId][] = $member;
        }
    }

    // Merge members into each team
    foreach ($teams as &$team) {
        $teamId = $team['team_id'] ?? $team['id'] ?? null;
        $team['members'] = $teamId && isset($membersByTeam[$teamId]) ? $membersByTeam[$teamId] : [];
    }
    unset($team);

    $response = [
        'success' => true,
        'data' => $teams
    ];
    
} catch (Exception $e) {
    containlog('Error', $e->getMessage(), __DIR__, 'reportData.log');
        $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

ob_clean();
echo json_encode($response, JSON_PRETTY_PRINT);