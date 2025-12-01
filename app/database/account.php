<?php
//user account class

require_once __DIR__ ."../../database/Db.php";
require_once __DIR__ ."../../utils/addAllUtil.php";

class UserAcc {
    private $conn;

    function __construct(){
        $dbObj = new Database();
        $this->conn = $dbObj->getConn();
    }
    //get Functions
    function getAccById($id) {
        try {
            $query = $this->conn->prepare("SELECT * FROM account WHERE account.accId = ?");
            $id = sanitizeInput($id);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return $result;
            } else {
                throw new Exception("No Account results found for ID: $id");
            }

        } catch (Exception $r) {
            $response = ['success' => false, 'message' => $r->getMessage()];
            containlog('Error', $r->getMessage(), __DIR__, 'database.log');
            return $response;
        }
    }

function isUserMobileRegisterd($number) {
    $number = sanitizeInput($number);

    try {
        if (strlen($number) !== 11) {
            throw new Exception('Mobile number must be 11 Digit!');
        }

        $query = $this->conn->prepare("SELECT `mobileNum` FROM `account` WHERE `mobileNum` = ?");
        $query->execute([$number]);
        
        $result = $query->fetch();

        return $result !== false;

    } catch (PDOException $e) {
        containlog("INFO", "Database Error in isUserMobileRegisterd:" .$e->getMessage(), null, 'database.log');
        return false; 
    }
}

    function isUsernameRegistered($uname) {
        $uname = sanitizeInput($uname);
        $existing = $this->getAccByUsername($uname);
        // var_dump($existing);

        if ($existing['success'] == false) {
            return false;
        }

        return true;
    }

    function getAccByUsername($userN) {
        try {
            $query = $this->conn->prepare("SELECT * FROM `account` WHERE `username` = ? LIMIT 1");
            $id = sanitizeInput($userN);
            $query->execute([$id]);
            $result = $query->fetch();

            if ($result) {
                return ['success' => true, 'data' => $result];
            } else {
                throw new Exception("No Account results found for ID: $id");
            }

        } catch (Exception $r) {
            containlog('Error', $r->getMessage(), __DIR__, 'database.log');
            $response = ['success' => false, 'message' => $r->getMessage()];
            return $response;
        }
    }

    ///////////////////////////////////////////////////////

    //create account 
    function addAccount($username, $hashedPass, $mNumber, $pgCode, $email, $role, $status) {
        
        try {
            $num = sanitizeInput($mNumber);

            if ($this->isUsernameRegistered($username)) {
                throw new Exception('Already Have Username');
            }

            $defaultRole = (isset($role)) ? $role : 4;
            $defaultStatus = (isset($status)) ? $status : 4;

            $reg = $this->conn->prepare("INSERT INTO `account`(`username`, `saltedPass`, `mobileNum`, `roleId`, `statusId`, `pgCode`, `email`) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$reg->execute([$username, $hashedPass, $num, $defaultRole, $defaultStatus, $pgCode, $email])){
                throw new Exception("Failed to execute query");
            };

            $newId = $this->conn->lastInsertId();

            return ['success' => true, 'accid' => $newId];

        } catch (Exception $errs) {
            containlog('Info', $errs->getMessage(), __DIR__, 'registerActivity.log');
            return ['success' => false];
        }
    }

    //update account
    function update($id, $key, $value){

        $keyList = getTableRows($this->conn, 'account');
        $id = sanitizeInput($id);
        $value = sanitizeInput($value);

        try {
            if (!in_array($key, $keyList, true)) {
                throw new Exception("Exception : Fail to Update Data! Unauthorize Account Table Row Key : " .$key);
            }

            $update = $this->conn->prepare("UPDATE account SET `" . $key . "` = ? WHERE accId = ?");
            
            if (!$update->execute([$value, $id])){
                throw new Exception("Failed to execute query");
            };
            // echo "Updated : " .$key ." = " .$value;
            return $update->rowCount();//returms if has effect on database rows

        } catch (Exception $p) {
            error_log(date('[Y-m-d H:i:s] ') . $p->getMessage() . PHP_EOL, 3, __DIR__ . '../../../log/account.log');
            exit();
        }
    }

    //just removing user from database
    function remove($id, $isValid) {

    }

}

?>