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



    //get info
    #get data kun available
    if(isset($_SESSION['user_login'])){
        $user_id_login = $_SESSION['user_login'];

        $get_data = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
        $get_data->bind_param("s",$user_login);
        $get_data->execute();
        $result_get = $get_data->get_result();
        if($result_get->num_rows>0){
            while($row_admin = mysqli_fetch_assoc($result_get)){
            
            }
        }

    }



      #get data kun available
    if(isset($_SESSION['admin_login'])){
        $admin_id = $_SESSION['admin_login'];

        $get_data = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
        $get_data->bind_param("s",$user_login);
        $get_data->execute();
        $result_get = $get_data->get_result();
        if($result_get->num_rows>0){
            while($row_admin = mysqli_fetch_assoc($result_get)){
            
            }
        }

    }



































?>