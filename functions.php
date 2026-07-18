<?php

include 'config.php';

//connection sa email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if(isset($_GET['noti_id'])){
    $noti_id = $_GET['noti_id'];
    $link = $_GET['link'];
    $status = "seen";
    $update = $conn->prepare("UPDATE notifications SET `status` = ? WHERE `noti_id` = ?");
    $update->bind_param("ss", $status, $noti_id);
    $update->execute();

    header("location:users/$link");
    exit;
}


if (isset($_POST['register'])) {
    
    // kuwaon an input san user
    $lastname       = mysqli_real_escape_string($conn, $_POST['lastname'] ?? '');
    $firstname      = mysqli_real_escape_string($conn, $_POST['firstname'] ?? '');
    $middlename     = mysqli_real_escape_string($conn, $_POST['middlename'] ?? '');
    $suffix         = mysqli_real_escape_string($conn, $_POST['suffix'] ?? '');
    $email          = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number'] ?? '');
    $province       = mysqli_real_escape_string($conn, $_POST['province'] ?? '');
    $municipality   = mysqli_real_escape_string($conn, $_POST['municipality'] ?? '');
    $barangay       = mysqli_real_escape_string($conn, $_POST['barangay'] ?? '');
    $zipcode        = mysqli_real_escape_string($conn, $_POST['zipcode'] ?? '');
    $username       = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $id_type        = mysqli_real_escape_string($conn, $_POST['id_type'] ?? '');
    $id_number      = mysqli_real_escape_string($conn, $_POST['id_number'] ?? '');
    $occupation     = mysqli_real_escape_string($conn, $_POST['occupation'] ?? '');
    $password        = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    

    
    // old image
    $old_id_photo   = mysqli_real_escape_string($conn, $_POST['old_id_photo'] ?? '');
    $old_selfie_photo = mysqli_real_escape_string($conn, $_POST['old_selfie_photo'] ?? '');

    // kapag not empty ma create siya session
    if (!empty($old_id_photo)) {
        $_SESSION['id_photo_name'] = $old_id_photo;
    }

 
    // dafault values
    $usertype  = "4";
    $status    = "Pending";
    $datetoday = date("Y-m-d"); 
    $user_id   = $id_number . rand();
    $user_type_acceptable = "3";

    // create sessions
    $fields = [
        'lastname', 'firstname', 'middlename', 'suffix', 'email', 'contact_number', 
        'province', 'municipality', 'barangay', 'zipcode', 'username', 'id_type', 
        'id_number', 'occupation','password','repeat_password'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION[$field] = $_POST[$field]; 
        }
    }

    if (isset($_POST['terms_agree'])) {
        $_SESSION['terms_agree'] = 1;
    }

    if (!empty($old_selfie_photo)) {
        $_SESSION['selfie_photo_name'] = $old_selfie_photo;
    }

    $selfie_img_name = $_FILES['selfie_photo']['name'] ?? '';
    $selfie_tmp_name = $_FILES['selfie_photo']['tmp_name'] ?? '';
    $selfie_error    = $_FILES['selfie_photo']['error'] ?? UPLOAD_ERR_NO_FILE;
    $final_selfie_name = "";

    //kuwaon ang image files
    $img_name = $_FILES['id_photo']['name'] ?? '';
    $tmp_name = $_FILES['id_photo']['tmp_name'] ?? ''; 
    $error    = $_FILES['id_photo']['error'] ?? UPLOAD_ERR_NO_FILE;

    $final_photo_name = ""; 
    $tomorrow         = date("Y-m-d", strtotime("+1 day"));

   // kung ang exists ang file sa folder para iwas duplicate
    if (!file_exists('assets/trash_uploads')) mkdir('assets/trash_uploads', 0777, true);
    if (!file_exists('assets/uploads')) mkdir('assets/uploads', 0777, true);

   // check kung nag upload image
    if ($error === UPLOAD_ERR_NO_FILE && empty($old_id_photo)) {
        $_SESSION['error'] = "Please upload ID photo!";
        header("location:signup.php");
        exit;
    }

    //kung error ang pag ka upload
    if ($error !== UPLOAD_ERR_OK && $error !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['error'] = "An error occurred during file upload. Error Code: " . $error;
        header("location:signup.php");
        exit;
    }


    if ($selfie_error === UPLOAD_ERR_NO_FILE && empty($old_selfie_photo)) {
    $_SESSION['error'] = "Please take a selfie!";
    header("location:signup.php");
    exit;
}

if ($selfie_error !== UPLOAD_ERR_OK && $selfie_error !== UPLOAD_ERR_NO_FILE) {
    $_SESSION['error'] = "An error occurred during selfie upload. Error Code: " . $selfie_error;
    header("location:signup.php");
    exit;
}

if ($selfie_error === UPLOAD_ERR_OK && !empty($selfie_img_name)) {

    $selfie_trash_name = $tomorrow . "_" . $selfie_img_name;
    $selfie_trash_path = 'assets/trash_uploads/' . $selfie_trash_name;
    $selfie_location   = 'assets/uploads/' . $selfie_img_name;

    if (move_uploaded_file($selfie_tmp_name, $selfie_trash_path)) {

        if (rename($selfie_trash_path, $selfie_location)) {
            $final_selfie_name = $selfie_img_name;
            $_SESSION['selfie_photo_name'] = $selfie_img_name;
        } else {
            $_SESSION['error'] = "Failed moving selfie to permanent directory.";
            header("location:signup.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Error uploading selfie to temporary folder.";
        header("location:signup.php");
        exit;
    }

}else if (!empty($old_selfie_photo)) {

    $selfie_source_trash = 'assets/trash_uploads/' . $tomorrow . "_" . $old_selfie_photo;
    $selfie_destination  = 'assets/uploads/' . $old_selfie_photo;

    if (file_exists($selfie_source_trash)) {
        if (rename($selfie_source_trash, $selfie_destination)) {
            $final_selfie_name = $old_selfie_photo;
        } else {
            $_SESSION['error'] = "Failed to transfer the cached selfie to permanent directory.";
            header("location:signup.php");
            exit;
        }
    } else if (file_exists($selfie_destination)) {
        $final_selfie_name = $old_selfie_photo;
    } else {
        $_SESSION['error'] = "Your previously captured selfie session has expired. Please retake your selfie.";
        unset($_SESSION['selfie_photo_name']);
        header("location:signup.php");
        exit;
    }
}


   //kung empty ang image an old photo makato sa upload
    if ($error === UPLOAD_ERR_OK && !empty($img_name)) {
        
        $image_trash   = $tomorrow . "_" . $img_name;
        $trash_folder  = 'assets/trash_uploads/' . $image_trash; 
        $location      = 'assets/uploads/' . $img_name;

        if (move_uploaded_file($tmp_name, $trash_folder)) {
            
         
            if (rename($trash_folder, $location)) {
                $final_photo_name = $img_name;          
                $_SESSION['id_photo_name'] = $img_name; 
            } else {
                $_SESSION['error'] = "Failed moving file to permanent directory.";
                header("location:signup.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Error uploading to temporary folder.";
            header("location:signup.php");
            exit;
        }
    } 
    // kung empty an old photo naman ma upload an main image
    else if (!empty($old_id_photo)) {
        
        $source_trash        = 'assets/trash_uploads/' . $tomorrow . "_" . $old_id_photo;
        $destination_uploads = 'assets/uploads/' . $old_id_photo;

        if (file_exists($source_trash)) {
            if (rename($source_trash, $destination_uploads)) {
                $final_photo_name = $old_id_photo; 
            } else {
                $_SESSION['error'] = "Failed to transfer the cached photo to permanent directory.";
                header("location:signup.php");
                exit;
            }
        } else if (file_exists($destination_uploads)) {
            $final_photo_name = $old_id_photo;
        } else {
            $_SESSION['error'] = "Your previously uploaded photo session has expired. Please re-upload your ID.";
            unset($_SESSION['id_photo_name']); 
            header("location:signup.php");
            exit;
        }
    }

    //check kung same an repeat password and password
    if ($repeat_password !== $password) {
        $_SESSION['error'] = "Password and Repeat Password do not Match!";
        header("location:signup.php");
        exit;
    }

    //check kun diri tugma sa fromat an number
    if (strlen($contact_number) !== 11 || !str_starts_with($contact_number, '09')) {
        $_SESSION['error'] = "Contact Number Must start in 09 and 11 digits";
        header("location:signup.php");
        exit;
    }

    // kun diri match ang passsword sa format
    $hasNumber = preg_match('/[0-9]/', $password);
    $hasSymbol = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
    if (strlen($password) < 7 || !$hasNumber || !$hasSymbol) {

        $_SESSION['error'] = "Password must be at least 7 characters long, contain 1 number, and 1 symbol!";
        header("location:signup.php");
        exit;
    }

    //dapat 7 pataas an username
    if (strlen($username) < 7) {
        $_SESSION['error'] = "Username must be at least 7 characters long";
        header("location:signup.php");
        exit;
    }

    //check kun same an username and password
    if($username === $password){
        $_SESSION['error'] = "Username and password cannot be the same!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Email
    $get_email = $conn->prepare("SELECT email,user_type FROM accounts WHERE `email` = ? AND `user_type` <= ? ");
    $get_email->bind_param("ss", $email,$user_type_acceptable);
    $get_email->execute();
    if ($get_email->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email already Taken!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Contact Number
    $get_contact = $conn->prepare("SELECT contact_number,user_type FROM accounts WHERE `contact_number` = ? AND `user_type` <= ? ");
    $get_contact->bind_param("ss", $contact_number,$user_type_acceptable);
    $get_contact->execute();
    if ($get_contact->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Contact Number already Taken!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Username
    $get_user = $conn->prepare("SELECT username,user_type FROM accounts WHERE `username` = ?  AND `user_type` <= ? ");
    $get_user->bind_param("ss", $username,$user_type_acceptable); 
    $get_user->execute();
    if ($get_user->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Username already Taken!";
        header("location:signup.php");
        exit;
    }


    
    // Generate 6 digit random number
    $verification_code = rand(100000, 999999);
    //my expire siya 5 min
    $expiry_time       = time() + (5 * 60); 

    $_SESSION['email_verification'] = [
        'code'       => $verification_code,
        'email'      => $email, 
        'expires_at' => $expiry_time
    ];

    //message na email
    $subject = "RENTSPACE VERIFICATION";
    $message = "
        <p>Dear <strong>$firstname</strong>,</p>
        <p>Thank you for using <strong>Rentspace</strong>.<br>  
        To complete your verification, please use the code below:</p>
        <h2 style='color:#2c7be5; letter-spacing:3px; text-align:center;'>$verification_code</h2>
        <p>This code will expire in <strong>5 minutes</strong>.<br>  
        If you did not request this verification, please ignore this email.</p>
        <p>Best regards,</p>
        <p><strong>RENTSPACE</strong></p>
    ";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rentspace4707@gmail.com';
        $mail->Password   = 'hmmv thkm hoqs gzhi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        //  HASH THE PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // save data
      $insert = $conn->prepare("INSERT INTO `accounts` (`user_id`,`middlename`,`lastname`,`firstname`,`suffix`,`email`,`contact_number`,`province`,`municipality`,`barangay`,`zipcode`,`username`,`password`,`id_type`,`id_number`,`id_photo`,`selfie_photo`,`occupation`,`status`,`user_type`,`date_request`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $insert->bind_param(
            "sssssssssssssssssssss",
            $user_id, $middlename, $lastname, $firstname, $suffix,
            $email, $contact_number, $province, $municipality, $barangay,
            $zipcode, $username, $hashed_password, $id_type, $id_number,
            $final_photo_name, $final_selfie_name, $occupation, $status, $usertype, $datetoday
        );
                
        if ($insert->execute()) {
           //clear session
            foreach ($fields as $field) {
                unset($_SESSION[$field]);
            }

            unset($_SESSION['id_photo_name']);
            unset($_SESSION['selfie_photo_name']);  
            unset($_SESSION['terms_agree']);
            unset($_SESSION['error']);

            //create session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['success'] = "Verification Code Successfully Sent to your Email, Please Check your Email";
            
            header("location:signup_confirmation.php");
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Database error occurred.";
            header("location:signup.php");
            exit;
        }
      
    } catch (Exception $e) {
        //check kun my internet
        $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
        header("Location: signup.php");
        exit;
    }
}


if(isset($_POST['confirm_code'])){
    $code =  mysqli_real_escape_string($conn, $_POST['code'] ?? '');
    $user_type = "3";

    if($code == $_SESSION['verification_code']){
        $update = $conn->prepare("UPDATE accounts SET `user_type` = ? WHERE `user_id` = ?");
        $update->bind_param("ss",$user_type,$_SESSION['user_id']);
        $update->execute();

        $_SESSION['success'] = "Your details have been sent to the administrator for approval. We will email you as soon as your account is finalized.";
        unset($_SESSION['user_id']);
        unset($_SESSION['verification_code']);
        header("location:index.php");
        exit;
    }else{
        $_SESSION['error'] = "Entered Code Doesn't Match!";
        header("location:signup_confirmation.php");
        exit;
    }
}

if (isset($_POST['signin'])) {
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $user_type_limit = 3; 
    $status = 'Approved';


    $get_user = $conn->prepare("SELECT `user_id`, `username`, `password`, `user_type` FROM `accounts` WHERE `username` = ? AND `user_type` <= ? AND `status` = ?");
    $get_user->bind_param("sss", $username, $user_type_limit,$status);
    $get_user->execute();
    $result_user = $get_user->get_result();

    if ($result_user->num_rows > 0) {
        while ($row_get = $result_user->fetch_assoc()) {
            $hashed_password = $row_get['password']; 

            if (password_verify($password, $hashed_password)) {
                // login success
                $user_id = $row_get['user_id'];
                $user_type = $row_get['user_type'];

                // Generate secure token
                $token = bin2hex(random_bytes(32)); 

                // Save token in DB
                $update_token = $conn->prepare("UPDATE accounts SET remember_token = ? WHERE user_id = ?");
                $update_token->bind_param("ss", $token, $user_id);
                $update_token->execute();

                // Set Cookie
                setcookie("remember_token", $token, time() + (7 * 24 * 60 * 60), "/", "", false, true); 

                // Redirect logic
                if ($user_type == "1") {
                    $_SESSION['admin_login'] = $user_id;
                    header('Location: admin');
                } elseif ($user_type == "2" || $user_type == "3") {
                    $_SESSION['user_login'] = $user_id;
                    header('Location: users');
                } else {
                    header('Location: signin.php');
                }
                exit;
            } else {
             
                $_SESSION['error'] = "Invalid Username or Password";
                header('Location: signin.php'); 
                exit;
            }
        }
    } else {
      
        $_SESSION['error'] = "Username or Password Invalid";
        header("Location: signin.php");
        exit;
    }
}



if(isset($_POST['sigout_admin'])){
    
    // Invalidate token in DB if session is active
    if (isset($_SESSION['admin_login'])) {
        $stmt = $conn->prepare("UPDATE accounts SET remember_token = NULL WHERE user_id = ?");
        $stmt->bind_param("s", $admin_id);
        $stmt->execute();
    }

    // Clear session
    $_SESSION = array();

    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Clear remember_token cookie
    setcookie("remember_token", "", time() - 3600, "/", "", false, true); // HttpOnly


    unset($_SESSION['admin_login']);

    // Destroy session
    session_destroy();


    // Redirect
    header("location:index.php");
    exit();
}




if(isset($_POST['approved_request_account'])){
    $id = $_POST['id'];
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
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rentspace4707@gmail.com';
        $mail->Password   = 'hmmv thkm hoqs gzhi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

            
        $update = $conn->prepare("UPDATE accounts SET status = ? WHERE `user_id` = ?");
        $update->bind_param("ss", $status, $id);
        $update->execute();

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
        header("Location: admin/request_account_info.php?id=$id&location_back=request_accounts.php");
        exit;
    }


}


if(isset($_POST['disapproved_request_account'])){
    $id = $_POST['id'];
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
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rentspace4707@gmail.com';
        $mail->Password   = 'hmmv thkm hoqs gzhi'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

            
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

  
}



if(isset($_POST['forgot_password'])){
    $email = $_POST['email'];
    $user_type = "3";
    $status = 'Approved';
    
    $check_email = $conn->prepare("SELECT * FROM `accounts` WHERE `email` = ? AND `user_type` <= ? AND `status` = ?");
    $check_email->bind_param("sss", $email, $user_type, $status);
    $check_email->execute();
    $result_email = $check_email->get_result();
    if($result_email->num_rows>0){
        while($row = mysqli_fetch_assoc($result_email)){
            $user_id = $row['user_id'];
            $firstname = $row['firstname'];


            
        // Generate 6 digit random number
        $verification_code = rand(100000, 999999);
        //my expire siya 5 min
        $expiry_time       = time() + (5 * 60); 

        $_SESSION['email_verification'] = [
            'code'       => $verification_code,
            'email'      => $email, 
            'expires_at' => $expiry_time
        ];


            
            //message na email
        $subject = "RENTSPACE: Reset Your Password";
        $message = "
            <p>Dear <strong>$firstname</strong>,</p>
            <p>We received a request to reset the password associated with your <strong>Rentspace</strong> account.</p>
            <p>To proceed with your password reset, please use the verification code below:</p>
            
            <h2 style='color:#2c7be5; letter-spacing:4px; text-align:center; background-color:#f8f9fa; padding:15px; border-radius:5px; margin:20px 0;'>$verification_code</h2>
            
            <p>This code will expire in <strong>5 minutes</strong> for security purposes.<br>
            Please do not share this code with anyone.</p>
            
            <hr style='border:0; border-top:1px solid #eef2f6; margin:20px 0;'>
            <p style='color:#7c8ba1; font-size:13px;'>If you did not request a password reset, you can safely ignore this email. Your account remains secure.</p>
            
            <p>Best regards,</p>
            <p><strong>The Rentspace Security Team</strong></p>
        ";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rentspace4707@gmail.com';
            $mail->Password   = 'hmmv thkm hoqs gzhi'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
            $mail->addAddress($email, $firstname);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();


            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['user_id'] = $user_id;
            header("location:forgot_password_confirm.php");
            exit;
        
        
        } catch (Exception $e) {
            //check kun my internet
            $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
            header("Location: forgot_password.php");
            exit;
        }


        }
    }else{
        $_SESSION['error'] = "User Doesn't Exist";
        header("location:forgot_password.php");
        exit;
    }
}


if(isset($_POST['confirm_forgot_pass'])){
    $code = $_POST['code'];
    $repeat_password = $_POST['repeat_password'];
    $password = $_POST['password']; 

     //  HASH THE PASSWORD
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

       //check kung same an repeat password and password
    if ($repeat_password !== $password) {
        $_SESSION['error'] = "Password and Repeat Password do not Match!";
        header("location:forgot_password_confirm.php");
        exit;
    }


       // kun diri match ang passsword sa format
    $hasNumber = preg_match('/[0-9]/', $password);
    $hasSymbol = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
    if (strlen($password) < 7 || !$hasNumber || !$hasSymbol) {

        $_SESSION['error'] = "Password must be at least 7 characters long, contain 1 number, and 1 symbol!";
        header("location:forgot_password_confirm.php");
        exit;
    }


    if($code == $_SESSION['verification_code']){
        $update = $conn->prepare("UPDATE accounts SET `password` = ? WHERE `user_id` = ?");
        $update->bind_param("ss",$hashed_password,$_SESSION['user_id']);
        $update->execute();

        unset($_SESSION['verification_code']);
        unset($_SESSION['user_id']);
        
        $_SESSION['success'] = "Successfully Changed Password";
        header("location:signin.php");
        exit;
    }else{
          $_SESSION['error'] = "Code Doesn't Match!";
        header("location:forgot_password_confirm.php");
        exit;
    }

}



if(isset($_POST['sigout_user'])){
 // Invalidate token in DB if session is active
    if (isset($_SESSION['user_login'])) {
        $stmt = $conn->prepare("UPDATE accounts SET remember_token = NULL WHERE user_id = ?");
        $stmt->bind_param("s", $user_id_login);
        $stmt->execute();
    }

    // Clear session
    $_SESSION = array();

    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Clear remember_token cookie
    setcookie("remember_token", "", time() - 3600, "/", "", false, true); // HttpOnly


    unset($_SESSION['user_login']);

    // Destroy session
    session_destroy();


    // Redirect
    header("location:index.php");
    exit();
}

if(isset($_POST['delete_notifictaions'])){
    $id = $_POST['id'];

    $delete=$conn->prepare("DELETE FROM `notifications` WHERE `noti_id` = ?");
    $delete->bind_param("i", $id);
    $delete->execute();

    $_SESSION['success'] = "Successfully Deleted";
    header("location:users/notifications");
    exit;
}


if(isset($_POST['change_profile'])){
    $profile_image = $_POST['profile_image'];


    $profile_image = $_FILES['profile_image']['name'];
    $profile_image_tmp = $_FILES['profile_image']['tmp_name'];
    $profile_image_size = $_FILES['profile_image']['size'];
    $location = "assets/uploads/" . $profile_image;
    $max_size = 2 * 1024 * 1024;

    if(empty($profile_image)){
        $_SESSION['error'] = "No Selected Image";
        header("location:users/change_profile.php");
        exit;
    }elseif($profile_image_size > $max_size){
        $_SESSION['error'] = "Selected image exceeds 2MB.";
        header("location:users/change_profile.php");
        exit;
    }else{
        if(move_uploaded_file($profile_image_tmp,$location)){
            $update = $conn->prepare("UPDATE accounts SET profile = ? WHERE `user_id` = ?");
            $update->bind_param("ss", $profile_image, $user_id_login);
            $update->execute();
            $_SESSION['success'] = "Successfully Updated";
            header("location:users/my_account.php");
            exit;
        }
    }

   
}

if (isset($_POST['change_credentials'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    if (empty($username) && empty($password)) {
        header("location:change_credentials.php");
        exit;
    } elseif (!empty($username) && empty($password)) {
        //dapat 7 pataas an username
        if (strlen($username) < 7) {
            $_SESSION['error'] = "Username must be at least 7 characters long";
            header("location:change_credentials.php");
            exit;
        } else {

            // Generate 6 digit random number
            $verification_code = rand(100000, 999999);
            //my expire siya 5 min
            $expiry_time = time() + (5 * 60);

            $_SESSION['email_verification'] = [
                'code' => $verification_code,
                'email' => $email,
                'expires_at' => $expiry_time
            ];



            //message na email
            $subject = "RENTSPACE: Change Username Credentials";
            $message = "
            <p>Dear <strong>$firstnameko</strong>,</p>
            <p>We received a request to Change the Username Credentials associated with your <strong>Rentspace</strong> account.</p>
            <p>To proceed with your Change the Username Credentials, please use the verification code below:</p>
            
            <h2 style='color:#2c7be5; letter-spacing:4px; text-align:center; background-color:#f8f9fa; padding:15px; border-radius:5px; margin:20px 0;'>$verification_code</h2>
            
            <p>This code will expire in <strong>5 minutes</strong> for security purposes.<br>
            Please do not share this code with anyone.</p>
            
            <hr style='border:0; border-top:1px solid #eef2f6; margin:20px 0;'>
            <p style='color:#7c8ba1; font-size:13px;'>If you did not request a username reset, you can safely ignore this email. Your account remains secure.</p>
            
            <p>Best regards,</p>
            <p><strong>The Rentspace Security Team</strong></p>
        ";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rentspace4707@gmail.com';
                $mail->Password = 'hmmv thkm hoqs gzhi';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
                $mail->addAddress($emailko, $firstnameko);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();

                $_SESSION['type'] = "username";
                $_SESSION['username_verification'] = $verification_code;
                $_SESSION['username_entered'] = $username;
                header("location:users/enter_code_credentials.php");
                exit;


            } catch (Exception $e) {
                //check kun my internet
                $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
                header("Location: users/change_credentials.php");
                exit;
            }


        }
    } elseif (empty($username) && !empty($password)) {

        // kun diri match ang passsword sa format
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
        if (strlen($password) < 7 || !$hasNumber || !$hasSymbol) {

            $_SESSION['error'] = "Password must be at least 7 characters long, contain 1 number, and 1 symbol!";
            header("location:signup.php");
            exit;
        } elseif ($password !== $repeat_password) {
            $_SESSION['error'] = "Password and Repeat Password do not Match";
            header("location:signup.php");
            exit;
        }else{
            
            // Generate 6 digit random number
            $verification_code = rand(100000, 999999);
            //my expire siya 5 min
            $expiry_time = time() + (5 * 60);

            $_SESSION['email_verification'] = [
                'code' => $verification_code,
                'email' => $email,
                'expires_at' => $expiry_time
            ];



            //message na email
            $subject = "RENTSPACE: Change Password Credentials";
            $message = "
            <p>Dear <strong>$firstnameko</strong>,</p>
            <p>We received a request to Change the Password Credentials associated with your <strong>Rentspace</strong> account.</p>
            <p>To proceed with your Change the Password Credentials, please use the verification code below:</p>
            
            <h2 style='color:#2c7be5; letter-spacing:4px; text-align:center; background-color:#f8f9fa; padding:15px; border-radius:5px; margin:20px 0;'>$verification_code</h2>
            
            <p>This code will expire in <strong>5 minutes</strong> for security purposes.<br>
            Please do not share this code with anyone.</p>
            
            <hr style='border:0; border-top:1px solid #eef2f6; margin:20px 0;'>
            <p style='color:#7c8ba1; font-size:13px;'>If you did not request a password reset, you can safely ignore this email. Your account remains secure.</p>
            
            <p>Best regards,</p>
            <p><strong>The Rentspace Security Team</strong></p>
        ";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rentspace4707@gmail.com';
                $mail->Password = 'hmmv thkm hoqs gzhi';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
                $mail->addAddress($emailko, $firstnameko);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();

                $_SESSION['type'] = "password";
                $_SESSION['password_verification'] = $verification_code;
                $_SESSION['password_entered'] = $password;
                header("location:users/enter_code_credentials.php");
                exit;


            } catch (Exception $e) {
                //check kun my internet
                $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
                header("Location: users/change_credentials.php");
                exit;
            }

        }
    }else{
        $hasNumber = preg_match('/[0-9]/', $password);
        $hasSymbol = preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password);
        if (strlen($password) < 7 || !$hasNumber || !$hasSymbol) {

            $_SESSION['error'] = "Password must be at least 7 characters long, contain 1 number, and 1 symbol!";
            header("location:signup.php");
            exit;
        } elseif ($password !== $repeat_password) {
            $_SESSION['error'] = "Password and Repeat Password do not Match";
            header("location:signup.php");
            exit;
        }elseif (strlen($username) < 7) {
            $_SESSION['error'] = "Username must be at least 7 characters long";
            header("location:change_credentials.php");
            exit;
        }elseif($username === $password){
            $_SESSION['error'] = "Username & Password must not be same!";
            header("location:change_credentials.php");
            exit;
        }else{
              // Generate 6 digit random number
            $verification_code = rand(100000, 999999);
            //my expire siya 5 min
            $expiry_time = time() + (5 * 60);

            $_SESSION['email_verification'] = [
                'code' => $verification_code,
                'email' => $email,
                'expires_at' => $expiry_time
            ];



            //message na email
            $subject = "RENTSPACE: Change Password & Username Credentials";
            $message = "
            <p>Dear <strong>$firstnameko</strong>,</p>
            <p>We received a request to Change the Password & Username Credentials associated with your <strong>Rentspace</strong> account.</p>
            <p>To proceed with your Change the Password Credentials, please use the verification code below:</p>
            
            <h2 style='color:#2c7be5; letter-spacing:4px; text-align:center; background-color:#f8f9fa; padding:15px; border-radius:5px; margin:20px 0;'>$verification_code</h2>
            
            <p>This code will expire in <strong>5 minutes</strong> for security purposes.<br>
            Please do not share this code with anyone.</p>
            
            <hr style='border:0; border-top:1px solid #eef2f6; margin:20px 0;'>
            <p style='color:#7c8ba1; font-size:13px;'>If you did not request a password & Username reset, you can safely ignore this email. Your account remains secure.</p>
            
            <p>Best regards,</p>
            <p><strong>The Rentspace Security Team</strong></p>
        ";

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rentspace4707@gmail.com';
                $mail->Password = 'hmmv thkm hoqs gzhi';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('rentspace4707@gmail.com', 'RENTSPACE');
                $mail->addAddress($emailko, $firstnameko);

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();

                $_SESSION['type'] = "password&username";
                $_SESSION['password&username'] = $verification_code;
                $_SESSION['password_entered'] = $password;
                $_SESSION['username_entered'] = $username;
                header("location:users/enter_code_credentials.php");
                exit;


            } catch (Exception $e) {
                //check kun my internet
                $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
                header("Location: users/change_credentials.php");
                exit;
            }
        }
    }
}


if(isset($_POST['confirm_code_credentials'])){
    $code = $_POST['code'];
    

    if($_SESSION['type'] === "username"){
        if($code == $_SESSION['username_verification']){
            $update = $conn->prepare("UPDATE `accounts` SET `username` = ? WHERE user_id = ?");
            $update->bind_param("ss", $_SESSION['username_entered'], $user_id_login);
            $update->execute();

            unset($_SESSION['type']);
            unset($_SESSION['username_verification']);
            unset($_SESSION['username_entered']);

            $_SESSION['success'] = "Successfully Updated";
            header("location:users/my_account.php");
            exit;

        }else{
            $_SESSION['error'] = "Code Does not Match!";
            header("location:users/enter_code_credentials.php");
            exit;
        }
    }elseif($_SESSION['type'] === "password"){
        $hashed_password = password_hash($_SESSION['password_entered'], PASSWORD_DEFAULT);
        if($code == $_SESSION['password_verification']){
            $update = $conn->prepare("UPDATE `accounts` SET `password` = ? WHERE user_id = ?");
            $update->bind_param("ss", $hashed_password, $user_id_login);
            $update->execute();

            unset($_SESSION['type']);
            unset($_SESSION['password_verification']);
            unset($_SESSION['password_entered']);

            $_SESSION['success'] = "Successfully Updated";
            header("location:users/my_account.php");
            exit;

        }else{
            $_SESSION['error'] = "Code Does not Match!";
            header("location:users/enter_code_credentials.php");
            exit;
        }
    }else{
        $hashed_password = password_hash($_SESSION['password_entered'], PASSWORD_DEFAULT);
        if($code == $_SESSION['password&username']){
            $update = $conn->prepare("UPDATE `accounts` SET `password` = ?, `username` = ? WHERE user_id = ?");
            $update->bind_param("sss", $hashed_password,$_SESSION['username_entered'],$user_id_login);
            $update->execute();

            unset($_SESSION['type']);
            unset($_SESSION['password_verification']);
            unset($_SESSION['password_entered']);
            unset($_SESSION['username_entered']);

            $_SESSION['success'] = "Successfully Updated";
            header("location:users/my_account.php");
            exit;

        }else{
            $_SESSION['error'] = "Code Does not Match!";
            header("location:users/enter_code_credentials.php");
            exit;
        }
    }

    

}



