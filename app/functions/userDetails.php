<?php
require_once __DIR__ .'../../database/profile.php';

function getUserProfile($id) {
    $profile = new profileMng();
    return $profile->getProfile($id);
}


?>