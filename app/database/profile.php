<?php
//for modify sa data then pag kuha na din, bale madalas gamitin ito for profile page
require_once __DIR__ ."/Db.php";
include_once __DIR__ .'../../utils/log.php';

class profileMng {  //Profile functions for user
    private $conn;

    function __construct() {//use user object or i think better the Id, hmm lets seee
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }
    
    function getConn() {
        return $this->conn;
    }

    function getAllImage(){
        try {
            $query = $this->conn->prepare("SELECT * FROM `images`");
            
            if (!$query->execute()) {
                throw new Exception("An Error Occured!");
            }
            $result = $query->fetchAll();
            
            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found");
            }

        } catch (Exception $r) {
            return ['success' => false,'message' => $r->getMessage()];
        }
    }

    function getAllProfileData(){
        try {
            $query = $this->conn->prepare("SELECT profile.*, acc.mobileNum, acc.email, acc.username, st.Name as status, rl.name as role FROM `profile` 
                                            Left JOIN account as acc on userId = acc.pgCode
                                            Left JOIN status as st on st.statusId = acc.statusId
                                            LEFT JOIN roles as rl on rl.roleId = acc.roleId");
            
            if (!$query->execute()) {
                throw new Exception("An Error Occured!");
            }
            $result = $query->fetchAll();
            
            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found");
            }

        } catch (Exception $r) {
            return ['success' => false,'message' => $r->getMessage()];
        }
    }

    function getUserByRole($role) {
        try {
            $role = sanitizeInput($role);
            $query = $this->conn->prepare("SELECT mem.team_id, p.* FROM members_team AS mem JOIN profile AS p ON mem.member_id = p.userId WHERE p.role_id = ?;");
            $query->execute([$role]);
            $result = $query->fetchAll();
            // var_dump($result);

            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found");
            }

        } catch (Exception $r) {
            return ['success' => false,'message' => $r->getMessage()];
        }
    }

    function getProfile($id) {
        try {
            $query = $this->conn->prepare("SELECT * FROM `profile` WHERE `userId` = ? LIMIT 1");
            $id = sanitizeInput($id);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return $result;
            } else {
                throw new Exception("No Profile results found for ID: $id");
            }

        } catch (Exception $r) {
            echo "Message: " .$r->getMessage();
            return 0;
        }
    }

        function getProfileDetailsByID($id) {
        try {
            $query = $this->conn->prepare("SELECT a.accId, p.userId as pgCode, a.username, a.email, a.mobileNum, rl.name as role, st.Name as status , p.firstName, p.lastName, p.gender, p.dateOfBirth
                                            FROM account AS a 
                                            INNER JOIN profile AS p ON a.pgCode = p.userId
                                            LEFT JOIN roles as rl on rl.roleId = a.roleId
                                            LEFT JOIN status as st on st.statusId = a.statusId
                                            WHERE a.accId = ?
                                            LIMIT 1");
            $id = sanitizeInput($id);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return $result;
            } else {
                throw new Exception("No Results found for ID: $id");
            }

        } catch (Exception $r) {
            echo "Message: " .$r->getMessage();
            return 0;
        }
    }

    function addProfile($fName, $lName, $Gnder, $DOB, $role, $address) {
        try {
            $fName = sanitizeInput($fName);
            $lName = sanitizeInput($lName);
            $Gnder = sanitizeInput($Gnder);
            $DOB = sanitizeInput($DOB);
            $role = sanitizeInput($role);
            $address = sanitizeInput($address);

            $sql = "INSERT INTO `profile`(`firstName`, `lastName`, `gender`, `dateOfBirth`, `address`) 
                    VALUES (?, ?, ?, ?, ?);";
            $reg = $this->conn->prepare($sql);

            if ($reg === false) {
                throw new Exception("SQL Prepare failed: " . implode(" ", $this->conn->errorInfo()));
            }
            
            $success = $reg->execute([$fName, $lName, $Gnder, $DOB, $address]);

            if (!$success) {
                $errorInfo = $reg->errorInfo();
                throw new Exception("Failed to execute profile insertion: " . $errorInfo[2]); 
            }

            $newId = $this->conn->lastInsertId();

            if (!is_numeric($newId) || $newId <= 0) {
                throw new Exception('No valid new ID was generated (ID: ' . $newId . '), insertion likely failed silently.');
            }

            return [
                "success" => true,
                "pgID" => (int)$newId
            ];

        } catch (\Exception $errs) {
            containlog('PROFILE_ERROR', $errs->getMessage(), __DIR__, 'database.log');
            
            return [
                "success" => false, 
                "error" => $errs->getMessage()
            ];
        }
    }

    function deleteUser($id) {
        $id = sanitizeInput($id);
    
        try {
            $stmt_profile = $this->conn->prepare("DELETE FROM profile WHERE `userId` = ?");

            if (!$stmt_profile->execute([$id])) {
                throw new Exception("Error deleting profile");
            }
            return true;

        } catch (Exception $e) {
            throw new Exception("Failed to delete user with ID $id: " . $e->getMessage());
        }
    }

    

    function updateUser(){//still not sure how to update by certain object

    }



}
// $userProfile = new profileMng();
// $userProfile->addProfile('mm', 'sr', 'male', '06/10/2025');
?>