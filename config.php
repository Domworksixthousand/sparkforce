<?php
    #error handler
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    function customErrorHandler($errno, $errstr, $errfile, $errline) {
        $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
        

        error_log($errorMessage . "\n", 3, "error_log.txt");


        echo "<div style='color: red; font-weight: bold;'>Something went wrong! Please try again later.</div>";

        return true;
    }

    #connection sa mysql
    session_start();
    $conn = mysqli_connect("localhost","root","","sparkforce_db");
    if(!$conn){
        echo "not connected";
    }
    

    //time zone
    date_default_timezone_set("asia/manila");
    #get date today
    $datetoday = date("Y-m-d");
    #get year today
    $year = date("Y");
    #get time today
    $timetoday = date("h:i:s a");

































?>