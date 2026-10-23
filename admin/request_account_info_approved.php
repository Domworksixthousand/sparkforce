<?php
    include '../config.php';
    require_once __DIR__ . '/../vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    // Pwede mo nang gamitin ang PHPMailer dito:
    $mail = new PHPMailer(true);

    $id = $_GET['id'];
    $user_type = $_GET['user_type'];
    $status = "Approved";


    $get_info = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
    $get_info->bind_param("s",$id);
    $get_info->execute();
    $result_info = $get_info->get_result();
    if($result_info->num_rows>0){
        while($row = mysqli_fetch_assoc($result_info)){
            $email = $row['email'];
            $firstname = $row['firstname'];
        }
    }

       //message na email
   $subject = "RENTSPACE: Account Approved!";
   if($user_type === "2"){
     $message = "
        <p>Dear <strong>$firstname</strong>,</p>
        <p>Congratulations! We are excited to inform you that your <strong>Rentspace</strong> account has been successfully approved.</p>
        <div style='background-color: #f8f9fa; border-left: 4px solid #2c7be5; padding: 15px; margin: 20px 0;'>
            <p style='margin: 0;'><strong>What's next?</strong></p>
            <p style='margin: 5px 0 0 0;'>You can now log in to your account, list your space, or start exploring available rentals.</p>
        </div>
        <p>If you have any questions or need assistance getting started, feel free to contact our support team.</p>
        <p>Welcome to the community!</p>
        <br>
        <p>Best regards,</p>
        <p><strong>The Rentspace Team</strong></p>
    ";
   }else{
      $message = "
        <p>Dear <strong>$firstname</strong>,</p>
        <p>Congratulations! We are excited to inform you that your <strong>Rentspace</strong> account has been successfully approved.</p>
        <div style='background-color: #f8f9fa; border-left: 4px solid #2c7be5; padding: 15px; margin: 20px 0;'>
            <p style='margin: 0;'><strong>What's next?</strong></p>
            <p style='margin: 5px 0 0 0;'>You can now log in to your account, start exploring available rentals.</p>
        </div>
        <p>If you have any questions or need assistance getting started, feel free to contact our support team.</p>
        <p>Welcome to the community!</p>
        <br>
        <p>Best regards,</p>
        <p><strong>The Rentspace Team</strong></p>
    ";
   }
   
    $mail = new PHPMailer(true);

    try {
      
    
        include '../vendor/connect_mail.php';
        $mail->send();

            
        $update = $conn->prepare("UPDATE accounts SET status = ? WHERE `user_id` = ?");
        $update->bind_param("ss", $status, $id);
        $update->execute();


        if($user_type === "2"){
            $update_landlord = $conn->prepare("UPDATE  landlord SET status = ? WHERE `user_id` = ?");
            $update_landlord->bind_param("ss", $status, $id);
            $update_landlord->execute();
        }

        $text_noti = "Welcome to RENTSPACE! Let's find your next home away from home. Start by completing your profile so landlords can get to know you better!";
        $status = "unseen";
        $sender = "RENTSPACE TEAM";
        $link = "my_account.php";
        $notifications = $conn->prepare("INSERT INTO `notifications` (`text_noti`,`status`,`date_sent`,`time_sent`,`sender`,`receiver`,`link`) VALUES
        (?,?,?,?,?,?,?)");
        $notifications->bind_param("sssssss", $text_noti, $status, $datetoday, $timetoday_24_hourformat, $sender,$id,$link);
        $notifications->execute();

       

        $_SESSION['success'] = "Successfully Approved";
        header("location:admin/request_accounts.php");
        exit;

       
      
    } catch (Exception $e) {
        //check kun my internet
        $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
        header("Location: request_account_info.php?id=$id&location_back=request_accounts.php");
        exit;
    }



