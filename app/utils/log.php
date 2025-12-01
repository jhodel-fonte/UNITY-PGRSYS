<?php
//this contains function for log set

//$error = error type
//$message = message contain
//$errorFIle = location of the error or what cause the error
//$logfile = filename of the error log -- it onptional the default location is default.log
//@param $errorFIle = location of the error or what cause the error

function containlog($typeOfError, $message, $errorFile = null, $logfilename = null) {
    //log file
    $defaultdir = __DIR__ .'../../../log/'; //lock location directory
    $file_location = $logfilename !== null ? $defaultdir .$logfilename : $defaultdir .'defaultlog.txt';

    $log_entry = '[' .date("Y-m-d H:i:s") . ']["type": "' . $typeOfError . '", "message": "' . $message . '", "file": "' . $errorFile .'"]' ."\n";

    $file = fopen($file_location, "a");
    if ($file) {
        fwrite($file, $log_entry);
        fclose($file);
        return true;
    }
    return false; 
}

?>