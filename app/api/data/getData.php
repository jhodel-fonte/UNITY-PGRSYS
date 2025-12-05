<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../../database/databaseFunctions.php';

require_once __DIR__ . '../../../database/reports.php';
require_once __DIR__ . '../../../database/accounting.php';
require_once __DIR__ . '../../../database/profile.php';
require_once __DIR__ . '../../../database/res_teams.php';
require_once __DIR__ . '../../../utils/addAllUtil.php';

$response = [
    'success' => false,
    'message' => 'Access Denied!'
];

try {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (isset($_GET['data']) && $_GET['data'] == 'report') {
            
            $reports = getAllReports();
            $images = getAllReportImages();
            
            if ($reports === false) {
                throw new Exception('Failed to fetch reports');
            }

            if ($images === false) {
                $images = []; // Set to empty array if failed instead of throwing error
            }

            // Group images by report_id
            $imagesByReportId = [];
            foreach ($images as $image) {
                $reportId = $image['report_id'] ?? null;
                if ($reportId) {
                    if (!isset($imagesByReportId[$reportId])) {
                        $imagesByReportId[$reportId] = [];
                    }
                    $imagesByReportId[$reportId][] = $image;
                }
            }
            
            // Merge images into each report
            foreach ($reports as &$report) {
                $reportId = $report['id'] ?? null;
                $report['images'] = $imagesByReportId[$reportId] ?? [];
            }
            unset($report); // Unset reference to avoid issues

            $response = [
                'success' => true,
                'data' => $reports
            ];
                
        }

        //Get Response Team List
        if (isset($_GET['data']) && $_GET['data'] == 'teams') {

            $teamClass = new Teams();
            $teams = $teamClass->getAllTeams();

            if (isset($teams['success']) && $teams['success'] == false ) {

                $response = [
                    'success' => false,
                    'message' => 'Failed to fetch teams: ' . ($teams['message'] ?? 'Unknown error.')
                ];

                $teams = [];
            }

            $members = $teamClass->getAllTeamMembers();
            
            $memberData = [];

            if (is_array($members) && !(isset($members['success']) && $members['success'] === false)) {
                $memberData = $members;
            }

            $membersByTeam = [];
            if (is_array($memberData)) {
                foreach ($memberData as $member) {
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

            foreach ($teams as &$team) {
                $teamId = $team['team_id'] ?? $team['id'] ?? null;
                $team['members'] = $teamId && isset($membersByTeam[$teamId]) ? $membersByTeam[$teamId] : [];
            }
            unset($team);

            $response = [
                'success' => true,
                'data' => $teams
            ];

        }
        
        //get users
        if (isset($_GET['data']) && $_GET['data'] == 'members') {
            $memberClass = new profileMng();
            $members = $memberClass->getAllProfileData();
            $images = $memberClass->getAllImage();

            if (isset($members['success']) && $members['success'] == false) {
                throw new Exception('Failed to fetch members');
            }

            if (isset($images['success']) && $images['success'] == false) {
                $images = []; // Set to empty array if failed instead of throwing error
            }

            // Group images by user_id
            $imagesByUserId = [];
            if (is_array($images)) {
                foreach ($images as $image) {
                    $userId = $image['user_id'] ?? null;
                    if ($userId) {
                        if (!isset($imagesByUserId[$userId])) {
                            $imagesByUserId[$userId] = [];
                        }
                        $imagesByUserId[$userId][] = $image;
                    }
                }
            }
            
            // Merge images into each member/user
            if (is_array($members)) {
                foreach ($members as &$member) {
                    $userId = $member['userId'] ?? null;
                    $member['images'] = $userId && isset($imagesByUserId[$userId]) ? $imagesByUserId[$userId] : [];
                }
                unset($member); 
            }

            $response = [
                'success' => true,
                'data' => $members
            ];

        }

        //get response tea members
        if (isset($_GET['data']) && $_GET['data'] == 'ResponseTeamUsers') {
            $users = getReposnseUsers();
            
            $response = [
                'success' => true,
                'data' => $users
            ];
        }

        //get response tea members
        if (isset($_GET['data']) && $_GET['data'] == 'NoTeamUser') {
            include_once __DIR__ .'/../../database/team_table.php';
            $users = getNotAssignedResponseUser();
            
            $response = [
                'success' => true,
                'data' => $users
            ];
        }

    }

} catch (Exception $e) {
    $response = [
    'success' => false,
    'message' => $e->getMessage()];
    containlog('Error', $e->getMessage(), __DIR__, 'reportData.log');
}

ob_clean();
echo json_encode($response, JSON_PRETTY_PRINT);