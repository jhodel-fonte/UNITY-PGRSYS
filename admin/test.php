<?php 
session_start();
$_SESSION['s'] = 1;

foreach ($_SESSION as $data => $value) {
    echo "<b>" . $data . "</b> : ";

    if (is_array($value)) {
        echo " <br>";
        print_r($value); 
        echo "<br>";
    } else {
        echo $value . "<br>";
    }
}

echo '12';

?>