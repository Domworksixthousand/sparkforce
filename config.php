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
    $timetoday_24_hourformat = date("H:i:s");



    //get info
    #get data kun available
if(isset($_SESSION['user_login'])){
    $user_id_login = $_SESSION['user_login'];

    $get_data = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
    $get_data->bind_param("s", $user_id_login); 
    $get_data->execute();
    $result_get = $get_data->get_result();


    if($result_get->num_rows > 0){
        $row_admin = $result_get->fetch_assoc();

        $lastnameko = $row_admin['lastname'] ?? '';
        $firstnameko = htmlspecialchars($row_admin['firstname'] ?? '');
        $suffixko = htmlspecialchars($row_admin['suffix'] ?? '');
        $middlenameko = htmlspecialchars($row_admin['middlename'] ?? '');
        $fullnameko = $lastnameko . ' ' . $firstnameko . ' ' . $middlenameko . ' ' . $suffixko;
        
        $emailko = htmlspecialchars($row_admin['email'] ?? '');
        $contact_numberko = htmlspecialchars($row_admin['contact_number'] ?? '');
        $provinceko = htmlspecialchars($row_admin['province'] ?? '');
        $municipalityko = htmlspecialchars($row_admin['municipality'] ?? '');
        $barangayko = htmlspecialchars($row_admin['barangay'] ?? '');
        $zipcodeko = htmlspecialchars($row_admin['zipcode'] ?? '');
        $addressko = $barangayko . ',' . $municipalityko . ',' . $provinceko . ',' . $zipcodeko;
        $user_type = $row_admin['user_type'];
        $id_typeko = htmlspecialchars($row_admin['id_type'] ?? '');
        $id_numberko = htmlspecialchars($row_admin['id_number'] ?? '');
        $id_photoko = htmlspecialchars($row_admin['id_photo'] ?? '');
        $occupationko = htmlspecialchars($row_admin['occupation'] ?? '');
        
        $profileko = $row_admin['profile'] ?? '';

    
        $final_profileko = empty($profileko) 
            ? '../assets/images/logo-icon.png' 
            : "../assets/uploads/$profileko";
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