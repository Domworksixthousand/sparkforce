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
    $status = "Disapproved";


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
   $subject = "RENTSPACE: Account Update Required";
   $reason = "The submitted information or property documents could not be verified by our compliance team. Please provide secondary proof.";
    $message = "
        <p>Dear <strong>$firstname</strong>,</p>
        <p>Thank you for your interest in joining <strong>Rentspace</strong>. After carefully reviewing your account application, we regret to inform you that we are unable to approve your request at this time.</p>
        
        <div style='background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; color: #856404;'>
            <p style='margin: 0;'><strong>Reason for Disapproval:</strong></p>
            <p style='margin: 5px 0 0 0;'>$reason</p>
        </div>
        
        <p><strong>What can you do?</strong><br>
        Please log back into your dashboard to update your profile or re-upload the correct and clear verification requirements.</p>
        
        <p>If you believe this was a mistake or you need help complying with our guidelines, please don't hesitate to reach out to our support team.</p>
        <p>Thank you for your understanding.</p>
        <br>
        <p>Best regards,</p>
        <p><strong>The Rentspace Verification Team</strong></p>
    ";
    $mail = new PHPMailer(true);

    try {
        include '../vendor/connect_mail.php';
        $mail->send();

        if($user_type === "2"){
            $update_landlord = $conn->prepare("UPDATE  landlord SET status = ? WHERE `user_id` = ?");
            $update_landlord->bind_param("ss", $status, $id);
            $update_landlord->execute();
        }

            
        $update = $conn->prepare("UPDATE accounts SET status = ? WHERE `user_id` = ?");
        $update->bind_param("ss", $status, $id);
        $update->execute();

        $_SESSION['success'] = "Successfully Disapproved";
        header("location:admin/request_accounts.php");
        exit;
       
      
    } catch (Exception $e) {
        //check kun my internet
        $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
        header("Location: admin/request_account_info.php?id=$id&location_back=request_accounts.php");
        exit;
    }
