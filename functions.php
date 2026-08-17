<?php
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Isa lang na require line ang kailangan para sa buong project!
require __DIR__ . '/vendor/autoload.php';

// Pwede mo nang gamitin ang PHPMailer dito:
$mail = new PHPMailer(true);


if(isset($_GET['update_messages'])){
    $update_messages = $_GET['update_messages'];
    $seen_status = "seen";
    $update = $conn->prepare("UPDATE messages SET `status` = ? WHERE `sender_id` = ? AND `receiver_id` = ? AND `status` != ?");
    $update->bind_param("ssss", $seen_status, $update_messages, $user_id_login, $seen_status);
    $update->execute();

    header("location:users/chat_portal.php");
    exit;
}

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

    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;


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

                unset($_SESSION['username'], $_SESSION['password']);

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
        header("location:users/change_credentials.php");
        exit;
    } elseif (!empty($username) && empty($password)) {
        //dapat 7 pataas an username
        if (strlen($username) < 7) {
            $_SESSION['error'] = "Username must be at least 7 characters long";
            header("location:users/change_credentials.php");
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

if (isset($_POST['landlord_registration'])) {
    
  
    if (isset($_FILES['owner_docs']['name']) && !empty(array_filter($_FILES['owner_docs']['name']))) {
     
        $owner_docs_validation = $_FILES['owner_docs']['name'];
    } else {
        $owner_docs_validation = $_SESSION['owner_docs'] ?? [];
    }

    if (isset($_FILES['photo_gallery']['name']) && !empty(array_filter($_FILES['photo_gallery']['name']))) {
        $photo_gallery_validation = $_FILES['photo_gallery']['name'];
    } else {
        $photo_gallery_validation = $_SESSION['photo_gallery'] ?? [];
    }


    $latitud = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';
    $province = $_POST['province'] ?? '';
    $municipality = $_POST['municipality'] ?? ''; 
    $barangay = $_POST['barangay'] ?? '';
    $property_type = $_POST['property_type'] ?? '';
    $property_name = $_POST['property_name'] ?? '';
    $terms_agree = $_POST['terms_agree'] ?? '';

    if (isset($_POST['terms_agree'])) {
        $_SESSION['terms_agree1'] = 1;
    } 

    $_SESSION['property_name'] = $property_name;
    $_SESSION['latitud'] = $latitud;
    $_SESSION['longitude'] = $longitude;
    $_SESSION['province'] = $province;
    $_SESSION['municipality'] = $municipality;
    $_SESSION['barangay'] = $barangay;
    $_SESSION['property_type'] = $property_type;
    

    $count_owner_docs = count($owner_docs_validation);
    $count_photo_gallery = count($photo_gallery_validation);
    $location_folder = "assets/uploads/";


    if($count_owner_docs < 1){ 
        $_SESSION['error'] = "Please upload at least 1 Proof of Ownership document.";
        header("Location:users/register.php");
        exit;
    }


    if($count_photo_gallery < 3 || $count_photo_gallery > 10){
        $_SESSION['error'] = "Gallery photos uploaded must be 3 to 10 images.";
        header("Location:users/register.php");
        exit;
    }

    if(empty($latitud) || empty($longitude) || empty($province) || empty($municipality) || empty($barangay)){
        $_SESSION['error'] = "Please select property location using the map control.";
        header("Location:users/register.php");
        exit;
    }

    if(empty($property_type) || $property_type == "Select Property type"){
        $_SESSION['error'] = "Please Select Property Type";
        header("Location:users/register.php");
        exit;
    }

    if(empty($property_name)){
        $_SESSION['error'] = "Please enter your property name";
        header("Location:users/register.php");
        exit;
    }

    $data_status = "Approved";
    $check = $conn->prepare("SELECT * FROM `landlord` WHERE `property_name` = ? AND `status` = ?");
    $check->bind_param("ss", $property_name,$data_status);
    $check->execute();
    $result_check = $check->get_result();
    
    if($result_check->num_rows > 0){
        $_SESSION['error'] = "Property name already exists. Please choose another one.";
        header("Location:users/register.php");
        exit;
    }


    if (!isset($_SESSION['owner_docs']) || !is_array($_SESSION['owner_docs'])) {
        $_SESSION['owner_docs'] = [];
    }
    if (!isset($_SESSION['photo_gallery']) || !is_array($_SESSION['photo_gallery'])) {
        $_SESSION['photo_gallery'] = [];
    }

    // Upload Owner Documents
    if (isset($_FILES['owner_docs']['name']) && !empty(array_filter($_FILES['owner_docs']['name']))) {
        $_SESSION['owner_docs'] = [];
        foreach ($_FILES['owner_docs']['name'] as $key => $filename) {
            if (!empty($filename)) {
                $unique_name = time() . '_' . uniqid() . '_' . $filename;
                $target_file = $location_folder . $unique_name;
                $tmp_name = $_FILES['owner_docs']['tmp_name'][$key];

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $_SESSION['owner_docs'][] = $unique_name; 
                } else {
                    $_SESSION['error'] = "Failed to upload ownership document: $filename";
                    header("Location:users/register.php");
                    exit;
                }
            }
        }
    }

    // Upload Photo Gallery
    if (isset($_FILES['photo_gallery']['name']) && !empty(array_filter($_FILES['photo_gallery']['name']))) {
        $_SESSION['photo_gallery'] = [];
        foreach ($_FILES['photo_gallery']['name'] as $key => $filename) {
            if (!empty($filename)) {
                $unique_image_name = time() . '_' . uniqid() . '_' . $filename;
                $target_image_file = $location_folder . $unique_image_name;
                $tmp_image_name = $_FILES['photo_gallery']['tmp_name'][$key];

                if (move_uploaded_file($tmp_image_name, $target_image_file)) {
                    $_SESSION['photo_gallery'][] = $unique_image_name; // Dito natin sinisave ang final filename
                } else {
                    $_SESSION['error'] = "Failed to upload gallery image: $filename";
                    header("Location:users/register.php");
                    exit;
                }
            }
        }
    }


    $landlord_id = $property_name . '_' . rand(1000, 9999);
    

    foreach($_SESSION['owner_docs'] as $docs){
        $insert_docs = $conn->prepare("INSERT INTO `documents` (`doc_name`,`user_id`,`landlord_id`) VALUES (?,?,?)");
        $insert_docs->bind_param("sss", $docs, $user_id_login, $landlord_id);
        $insert_docs->execute();
    }

    foreach($_SESSION['photo_gallery'] as $images){
        $insert_gallery = $conn->prepare("INSERT INTO `gallery` (`image_name`,`user_id`,`landlord_id`) VALUES (?,?,?)");
        $insert_gallery->bind_param("sss", $images, $user_id_login, $landlord_id);
        $insert_gallery->execute();
    }

 
    $status = "Pending";
    $insert_details = $conn->prepare("INSERT INTO `landlord` (`landlord_id`,`user_id`,`province`,`municipality`,`barangay`,`type`,`property_name`,`date_request`,`status`,`longitude`,`latitude`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    $insert_details->bind_param("sssssssssss", $landlord_id, $user_id_login, $province, $municipality, $barangay, $property_type, $property_name, $datetoday, $status,$longitude,$latitud);
    $insert_details->execute();


    unset($_SESSION['property_name']);
    unset($_SESSION['latitud']);
    unset($_SESSION['longitude']);
    unset($_SESSION['province']);
    unset($_SESSION['municipality']);
    unset($_SESSION['barangay']);
    unset($_SESSION['property_type']);
    unset($_SESSION['owner_docs']);
    unset($_SESSION['photo_gallery']);
    unset($_SESSION['terms_agree1']);

    $_SESSION['success'] = "Submitted successfully. Please wait for approval in your notifications.";
    header("Location:users/register.php");
    exit;
}



if(isset($_POST['disapproved_pending_property'])){
    $id = $_POST['id'];

    $get_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
    $get_info->bind_param("s",$id);
    $get_info->execute();
    $result_info = $get_info->get_result();
    if($result_info->num_rows>0){
        while($row_info = mysqli_fetch_assoc($result_info)){
            $user_id = $row_info['user_id'];
            $property_name = $row_info['property_name'];
        }
    }
    $Disapproved = "Disapproved";
    $update = $conn->prepare("UPDATE `landlord` SET `status` = ? WHERE `landlord_id` = ?");
    $update->bind_param("ss",$Disapproved,$id);
    $update->execute();

    $text = "Thank you for submitting your application to rent out your property, $property_name, on our platform.After reviewing the details and requirements, we regret to inform you that your application has been disapproved" .
    $status = "unseen";
    $sender = "RENTSPACE TEAM";
    $link = "property_requests.php";

    $insert_notifications = $conn->prepare("INSERT INTO `notifications` (`text_noti`,`status`,`date_sent`,`time_sent`,`sender`,`receiver`,`link`) VALUES (?,?,?,?,?,?,?)");
    $insert_notifications->bind_param("sssssss", $text, $status, $datetoday, $timetoday_24_hourformat, $sender, $user_id, $link);
    $insert_notifications->execute();

    $_SESSION['success'] = "Successfully Disapproved";
    header("location:admin/pending_properties.php");
    exit;
}




if(isset($_POST['approved_pending_property'])){
    $id = $_POST['id'];

    $get_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
    $get_info->bind_param("s",$id);
    $get_info->execute();
    $result_info = $get_info->get_result();
    if($result_info->num_rows>0){
        while($row_info = mysqli_fetch_assoc($result_info)){
            $user_id = $row_info['user_id'];
            $property_name = $row_info['property_name'];
        }
    }

    $Approved = "Approved";
    $update = $conn->prepare("UPDATE `landlord` SET `status` = ? WHERE `landlord_id` = ?");
    $update->bind_param("ss",$Approved,$id);
    $update->execute();

    $text = "Congratulations! We are pleased to inform you that your application to rent out your property, $property_name, has been approved and is now officially live on our platform. Tenants can now view your listing and send inquiries. Thank you for partnering with us!" .
    $status = "unseen";
    $sender = "RENTSPACE TEAM";
    $link = "property_requests.php";

    $insert_notifications = $conn->prepare("INSERT INTO `notifications` (`text_noti`,`status`,`date_sent`,`time_sent`,`sender`,`receiver`,`link`) VALUES (?,?,?,?,?,?,?)");
    $insert_notifications->bind_param("sssssss",$text, $status, $datetoday, $timetoday_24_hourformat, $sender, $user_id, $link);
    $insert_notifications->execute();

    $user_status = "2";
    $update_account_status = $conn->prepare("UPDATE `accounts` SET `user_type` = ? WHERE `user_id` = ?");
    $update_account_status->bind_param("ss", $user_status, $user_id);
    $update_account_status->execute();

    $_SESSION['success'] = "Successfully Approved";
    header("location:admin/pending_properties.php");
    exit;
}

if(isset($_POST['save_amenity'])){
    $desc = $_POST['desc'] ?? NULL;
    $amenity = $_POST['amenity'];
    $active = "yes";

    $_SESSION['amenity'] = $amenity;
    $_SESSION['desc'] = $desc;


    $check_same = $conn->prepare("SELECT * FROM `amenities` WHERE `active` = ? AND `amenity` = ? AND `user_id` = ?");
    
  
    $check_same->bind_param("sss", $active, $amenity, $user_id_login);
    $check_same->execute();
    $result_same = $check_same->get_result();

    if($result_same->num_rows > 0){
        $_SESSION['error'] = "$amenity Already Exist";
        header("location: users/amenities_add.php");
        exit;
    } else {
     
        $insert = $conn->prepare("INSERT INTO `amenities` (`amenity`, `user_id`, `description`, `active`) VALUES (?, ?, ?, ?)");
        
      
        $insert->bind_param("ssss", $amenity, $user_id_login, $desc, $active);
        $insert->execute();

     
        unset($_SESSION['amenity']);
        unset($_SESSION['desc']);

        $_SESSION['success'] = "Successfully Saved";
        header("location: users/amenities.php");
        exit;
    }
}

if (isset($_POST['delete_amenities'])) {
    $id = $_POST['id'];
    $active = "no";

 
    $update = $conn->prepare("UPDATE `amenities` SET `active` = ? WHERE `amen_id` = ?");
    

    $update->bind_param("si", $active, $id);
    
    if ($update->execute()) {
        $_SESSION['success'] = "Successfully Deleted";
    } else {
        $_SESSION['error'] = "Failed to delete amenity: " . $conn->error;
    }

  
    header("location: users/amenities.php"); 

    exit;
}

if(isset($_POST['edit_amenity'])){
    $id = $_POST['id'];
    $desc = $_POST['desc'] ?? NULL;;
    $amenity = $_POST['amenity'];

    $check = $conn->prepare("SELECT * FROM `amenities` WHERE `amenity` = ? AND `amen_id` != ?");
    $check->bind_param("si",$amenity,$id);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        $_SESSION['error'] = "$amenity already exist";
        header("location:users/amenities_edit.php?id=$id");
        exit;
    }else{
        $update = $conn->prepare("UPDATE  `amenities` SET `amenity` = ?,`description` = ? WHERE `amen_id` = ?");
        $update->bind_param("ssi", $amenity, $desc, $id);
        $update->execute();
        $_SESSION['success'] = "Successfully Edited";
        header("location:users/amenities.php");
        exit;

    }
}


if (isset($_POST['save_boarding'])) {
    $landlord_id = $_POST['landlord_id'] ?? '';
    $room_name   = trim($_POST['name'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $other_info  = trim($_POST['other_info'] ?? '');
    $old_cover   = trim($_POST['old_cover'] ?? '');

    // 1. BED SESSION / PROCESSING
    $beds = [];

    if (!empty($_POST['bednum']) && is_array($_POST['bednum'])) {
        foreach ($_POST['bednum'] as $index => $bed_number) {
            $bed_number = trim($bed_number);
            // Default to 1 deck if not specified
            $num_deck   = !empty($_POST['num_deck'][$index]) ? (int)$_POST['num_deck'][$index] : 1; 
            $bed_image  = $_POST['old_image'][$index] ?? '';

            // Handle individual bed image upload
            if (
                isset($_FILES['image']['name'][$index]) &&
                $_FILES['image']['error'][$index] === UPLOAD_ERR_OK
            ) {
                $fileTmp   = $_FILES['image']['tmp_name'][$index];
                $fileName  = $_FILES['image']['name'][$index];
                $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                $bed_image = time() . "_{$index}_bed." . $fileExt;
                $uploadDir = 'assets/uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                move_uploaded_file($fileTmp, $uploadDir . $bed_image);
            }

            $beds[] = [
                'bed_number' => $bed_number,
                'num_deck'   => $num_deck,
                'bed_image'  => $bed_image
            ];
        }
    }

    // 2. VALIDATE AMENITIES INPUT (Duplicates Check)
    $selected_amenities = [];
    if (!empty($_POST['amenity']) && is_array($_POST['amenity'])) {
        foreach ($_POST['amenity'] as $amenity_id) {
            $amenity_id = trim($amenity_id);

            if ($amenity_id !== '') {
                if (in_array($amenity_id, $selected_amenities)) {
                    $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
                    header("Location: users/boarding_house_add.php?property_id=" . urlencode($landlord_id));
                    exit;
                }
                $selected_amenities[] = $amenity_id;
            }
        }
    }

    $rent_id = $room_name . rand(1000, 9999);
    $type    = "Boarding House / Bedspace";

    // 3. COVER PHOTO UPLOAD HANDLER
    $cover_photo_filename = '';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['cover']['tmp_name'];
        $fileName      = $_FILES['cover']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $cover_photo_filename = time() . '_cover.' . $fileExtension;
        $uploadDir            = 'assets/uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($fileTmpPath, $uploadDir . $cover_photo_filename)) {
            $cover_photo_filename = '';
        }
    }

    // 4. CHECK DUPLICATE ROOM NAME
    $check_room = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
    $check_room->bind_param("ss", $landlord_id, $room_name);
    $check_room->execute();
    $result_room_name = $check_room->get_result();

    if ($result_room_name->num_rows > 0) {
        $_SESSION['error'] = "$room_name Already Exists";
        header("Location: users/boarding_house_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // 5. INSERT INTO RENTSPACE
    $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("sssssiss", $rent_id, $room_name, $landlord_id, $user_id_login, $type, $price, $cover_photo_filename, $other_info);
    $insert->execute();

    // INSERT AMENITIES
    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("ss", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // 6. INSERT BEDS (MULTIPLE DECKS CREATION)
    if (!empty($beds)) {
        $insert_beds = $conn->prepare("INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($beds as $bed) {
            $deck_count = (int)$bed['num_deck'];
            $status     = "Available";

            // If num_deck is 2, this loops twice (i = 1, i = 2)
            for ($d = 1; $d <= $deck_count; $d++) {
                $boarding_id    = $rent_id . '_' . uniqid(); 
                
                // Formats as "Bed 1 - Deck 1", "Bed 1 - Deck 2", etc.
                // If there's only 1 deck, it just keeps the bed number or appends " - Deck 1"
                $deck_bed_number = ($deck_count > 1) 
                    ? $bed['bed_number'] . " - Deck " . $d 
                    : $bed['bed_number'];

                $insert_beds->bind_param("sssiss", $boarding_id, $deck_bed_number, $status, $d, $bed['bed_image'], $rent_id);
                $insert_beds->execute();
            }
        }
    }

    $_SESSION['success'] = "Successfully Inserted";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}


if (isset($_POST['edit_boarding'])) {
    $rent_id     = $_POST['rent_id'];
    $landlord_id = $_POST['landlord_id'];
    $room_name   = $_POST['name'];
    $price       = $_POST['price'];
    $other_info  = $_POST['other_info'];
    $old_cover   = $_POST['old_cover'] ?? '';

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // 1. BED SESSION / PROCESSING
    $beds = [];
    if (!empty($_POST['bednum']) && is_array($_POST['bednum'])) {
        foreach ($_POST['bednum'] as $index => $bed_number) {
            $base_bed_name = trim($bed_number);
            $num_deck      = (int)($_POST['num_deck'][$index] ?? 1);
            $bed_image     = $_POST['old_image'][$index] ?? '';

            // Handling bed image upload
            if (
                isset($_FILES['image']['name'][$index]) &&
                $_FILES['image']['error'][$index] === UPLOAD_ERR_OK
            ) {
                $fileTmp   = $_FILES['image']['tmp_name'][$index];
                $fileName  = $_FILES['image']['name'][$index];
                $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $bed_image = time() . "_{$index}_bed." . $fileExt;
                move_uploaded_file($fileTmp, $uploadDir . $bed_image);
            }

            // Extract status array for this specific bed index
            $deck_statuses = $_POST['status'][$index] ?? [];

            // Loop through each deck for multi-deck beds
            for ($d = 0; $d < $num_deck; $d++) {
                $deck_status = $deck_statuses[$d] ?? 'Available';
                
                // Append deck suffix if more than 1 deck exists
                $formatted_bed_name = ($num_deck > 1) 
                    ? "{$base_bed_name} - Deck " . ($d + 1) 
                    : $base_bed_name;

                $beds[] = [
                    'bed_number' => $formatted_bed_name,
                    'num_deck'   => $num_deck,
                    'bed_image'  => $bed_image,
                    'status'     => $deck_status
                ];
            }
        }
    }

    // 2. AMENITIES VALIDATION
    $selected_amenities = [];
    $processed_amenities_data = [];

    if (!empty($_POST['amenity']) && is_array($_POST['amenity'])) {
        $submitted_rent_amen_ids = $_POST['rentspace_amenities_id'] ?? [];

        foreach ($_POST['amenity'] as $index => $amenity_id) {
            $amenity_id = trim($amenity_id);
            $rentspace_amenity_id = trim($submitted_rent_amen_ids[$index] ?? '');

            if ($amenity_id !== '') {
                if (in_array($amenity_id, $selected_amenities)) {
                    $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
                    header("Location: users/my_bh_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
                    exit;
                }
                $selected_amenities[] = $amenity_id;
                $processed_amenities_data[] = [
                    'rentspace_amenity_id' => $rentspace_amenity_id,
                    'amenity_id'           => $amenity_id
                ];
            }
        }
    }

    // 3. CHECK DUPLICATE ROOM NAME
    $check_room = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ? AND `rent_id` != ?");
    $check_room->bind_param("sss", $landlord_id, $room_name, $rent_id);
    $check_room->execute();
    $result_room_name = $check_room->get_result();

    if ($result_room_name->num_rows > 0) {
        $_SESSION['error'] = "$room_name Already Exists";
        header("Location: users/my_bh_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // 4. UPDATE RENTSPACE BASIC INFO
    $update_rentspace = $conn->prepare("UPDATE rentspace SET `name` = ?, `price` = ?, `other_info` = ? WHERE `rent_id` = ?");
    $update_rentspace->bind_param("ssss", $room_name, $price, $other_info, $rent_id);
    $update_rentspace->execute();

    // 5. UPDATE COVER PHOTO
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['cover']['tmp_name'];
        $fileName      = $_FILES['cover']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $cover_photo   = time() . '_cover.' . $fileExtension;
        $location      = $uploadDir . $cover_photo;

        if (move_uploaded_file($fileTmpPath, $location)) {
            if (!empty($old_cover) && file_exists($uploadDir . $old_cover)) {
                unlink($uploadDir . $old_cover);
            }

            $update = $conn->prepare("UPDATE `rentspace` SET `image_cover` = ? WHERE `rent_id` = ?");
            $update->bind_param("ss", $cover_photo, $rent_id);
            $update->execute();
        } else {
            $_SESSION['error'] = "Failed to upload cover photo";
            header("Location: users/my_bh_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }
    }

    // 6. UPDATE BEDS IN DATABASE
    if (!empty($beds)) {
        // Delete old bed records for this rent_id
        $delete = $conn->prepare("DELETE FROM `boarding_house` WHERE `rent_id` = ?");
        $delete->bind_param("s", $rent_id);
        $delete->execute();

        // Re-insert with individual status per deck
        $insert_beds = $conn->prepare("INSERT INTO `boarding_house` (`boarding_id`, `bed_number`, `status`, `num_decks`, `image`, `rent_id`) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($beds as $bed) {
            $boarding_id = $rent_id . '_' . uniqid();
            $bed_number  = $bed['bed_number'];
            $status      = $bed['status'];
            $num_deck    = $bed['num_deck'];
            $bed_image   = $bed['bed_image'];

            $insert_beds->bind_param("sssiss", $boarding_id, $bed_number, $status, $num_deck, $bed_image, $rent_id);
            $insert_beds->execute();
        }
    }

    // 7. UPDATE AMENITIES IN DATABASE
    if (!empty($selected_amenities)) {
        $delete_amen = $conn->prepare("DELETE FROM `rentspace_amenities` WHERE `rent_id` = ?");
        $delete_amen->bind_param("s", $rent_id);
        $delete_amen->execute();

        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("ss", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    $_SESSION['success'] = "Successfully Updated";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}

if(isset($_POST['delete_room'])){
    $landlord_id = $_POST['landlord_id'];
    $rent_id = $_POST['rent_id'];

    $get_type = $conn->prepare("SELECT `type` FROM `landlord` WHERE `landlord_id` = ?");
    $get_type->bind_param("s", $landlord_id);
    $get_type->execute();
    $result_type = $get_type->get_result();
    if($result_type->num_rows>0){
        while($row_type = mysqli_fetch_assoc($result_type)){
            $type = $row_type['type'];
        }
    }

    if($type === "Boarding House / Bedspace"){
        $delete = $conn->prepare("DELETE  FROM `boarding_house` WHERE `rent_id` = ?");
        $delete->bind_param("s", $rent_id);
        $delete->execute();
    }

    $delete = $conn->prepare("DELETE FROM `rentspace` WHERE `rent_id` = ?");
    $delete->bind_param("s",$rent_id);
    $delete->execute();

    $delete_amen = $conn->prepare("DELETE FROM `rentspace_amenities` WHERE `rent_id` = ?");
    $delete_amen->bind_param("s",$rent_id);
    $delete_amen->execute();

    $_SESSION['success'] = "Sucessfully Deleted";
    header("location:users/my_property.php?property_id=". urlencode($landlord_id));
    exit;
}

//insert apartment


if (isset($_POST['save_apartment'])) {


    $landlord_id    = $_POST['landlord_id'] ?? '';
    $room_name      = trim($_POST['apartment_name'] ?? '');
    $price          = trim($_POST['apartment_price'] ?? '');
    $other_info     = trim($_POST['apartment_other_info'] ?? '');
    $apartment_type = $_POST['type'] ?? '';
    $raw_amenities  = $_POST['apartment_amenity'] ?? [];

    foreach($raw_amenities as $yeah){
        echo "$yeah <br>";
    }
 

    $status       = "Available";
    $rent_id      = $room_name . rand(1000, 9999);
    $type         = "Apartment";
    $apartment_id = $room_name . rand();

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }


    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/apartment_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }
       $_SESSION['apartment_name']       = $room_name;
    $_SESSION['apartment_price']      = $price;
    $_SESSION['apartment_other_info'] = $other_info;
    $_SESSION['type']                 = $apartment_type;
    $_SESSION['amenities']            = $raw_amenities ?? [];
    if (isset($_FILES['apartment_cover']) && $_FILES['apartment_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['apartment_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['apartment_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $_SESSION['apartment_cover'] = $new_cover;
        }
    }
    $cover_photo = $_SESSION['apartment_cover'] ?? '';

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/apartment_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

  
    if (!empty($_FILES['gallery']['name'][0])) {
        $new_gallery = [];
        foreach ($_FILES['gallery']['name'] as $i => $img_name) {
            if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext      = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $new_name = uniqid() . '_' . $i . '.' . $ext;
            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                $new_gallery[] = $new_name;
            }
        }
        if (!empty($new_gallery)) {
            $_SESSION['gallery'] = $new_gallery;
        }
    }
    $gallery_images = $_SESSION['gallery'] ?? [];

    if (count($gallery_images) < 3 || count($gallery_images) > 10) {
        $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
        header("Location: users/apartment_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }


    $check_room = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
    $check_room->bind_param("ss", $landlord_id, $room_name);
    $check_room->execute();
    $result_room_name = $check_room->get_result();

    if ($result_room_name->num_rows > 0) {
        $_SESSION['error'] = "$room_name Already Exists";
        header("Location: users/apartment_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }


    $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssssss", $rent_id, $room_name, $landlord_id, $user_id_login, $type, $price, $cover_photo, $other_info);
    $insert->execute();

    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    if (!empty($gallery_images)) {
        $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
        foreach ($gallery_images as $img) {
            $insert_gallery->bind_param("ss", $img, $rent_id);
            $insert_gallery->execute();
        }
    }

    if (!empty($apartment_type)) {
        $insert_apartment = $conn->prepare("INSERT INTO `apartment` (`apartment_id`, `apartment_type`, `status`, `rent_id`) VALUES (?, ?, ?, ?)");
        $insert_apartment->bind_param("ssss", $apartment_id, $apartment_type, $status, $rent_id);
        $insert_apartment->execute();
    }

    unset(
        $_SESSION['apartment_name'],
        $_SESSION['apartment_price'],
        $_SESSION['apartment_other_info'],
        $_SESSION['apartment_cover'],
        $_SESSION['gallery'],
        $_SESSION['type'],
        $_SESSION['amenities']
    );

    $_SESSION['success'] = "Successfully Inserted";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}



if (isset($_POST['edit_apartment'])) {
    $landlord_id       = $_POST['landlord_id'] ?? '';
    $rent_id           = $_POST['rent_id'] ?? '';
    $apartment_id      = $_POST['apartment_id'] ?? '';
    $apartment_name    = trim($_POST['apartment_name'] ?? '');
    $apartment_price   = trim($_POST['apartment_price'] ?? '');
    $apartment_other   = trim($_POST['apartment_other_info'] ?? '');
    $apartment_type    = trim($_POST['type'] ?? '');
    $old_cover         = $_POST['old_cover'] ?? '';
    $amenities         = $_POST['apartment_amenity'] ?? [];
    $status         = $_POST['status'] ?? '';

    if ($rent_id === '' || $apartment_id === '') {
        header("location: users/index.php");
        exit;
    }

    $image_cover = $old_cover;

    if (!empty($_FILES['apartment_cover']['name'])) {
        $file = $_FILES['apartment_cover'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'assets/uploads/';
            $ext       = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName   = uniqid('cover_') . '.' . $ext;

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                if (!empty($old_cover) && file_exists($uploadDir . $old_cover)) {
                    unlink($uploadDir . $old_cover);
                }
                $image_cover = $newName;
            } else {
                error_log("move_uploaded_file failed for {$file['tmp_name']} -> {$uploadDir}{$newName}");
            }
        } else {
            error_log("Cover upload error code: " . $file['error']);
        }
    }

    
    $update_rent = $conn->prepare("
        UPDATE rentspace
        SET name = ?, price = ?, image_cover = ?, other_info = ?
        WHERE rent_id = ?
    ");
    $update_rent->bind_param(
        "sssss",
        $apartment_name,
        $apartment_price,
        $image_cover,
        $apartment_other,
        $rent_id
    );
    $update_rent->execute();

    $update_apartment = $conn->prepare("
        UPDATE apartment
        SET apartment_type = ?, status = ?
        WHERE apartment_id = ? AND rent_id = ?
    ");
    $update_apartment->bind_param("ssss", $apartment_type,$status,$apartment_id, $rent_id);
    $update_apartment->execute();

 
    if (!empty($_FILES['gallery']['name'][0])) {

        $get_old = $conn->prepare("SELECT image FROM gallery2 WHERE rent_id = ?");
        $get_old->bind_param("s", $rent_id);
        $get_old->execute();
        $old_res = $get_old->get_result();
        while ($row = $old_res->fetch_assoc()) {
            $path = 'assets/uploads/' . $row['image'];
            if (file_exists($path)) unlink($path);
        }

        $del_gallery = $conn->prepare("DELETE FROM gallery2 WHERE rent_id = ?");
        $del_gallery->bind_param("s", $rent_id);
        $del_gallery->execute();

        // insert new gallery photos
        $insert_gallery = $conn->prepare("INSERT INTO gallery2 (rent_id, image) VALUES (?, ?)");
        foreach ($_FILES['gallery']['tmp_name'] as $i => $tmpName) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $ext     = pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION);
                $newName = uniqid('gallery_') . '.' . $ext;
                if (move_uploaded_file($tmpName, 'assets/uploads/' . $newName)) {
                    $insert_gallery->bind_param("ss", $rent_id, $newName);
                    $insert_gallery->execute();
                }
            }
        }
    }

    $amenities_clean = array_filter($amenities, fn($id) => $id !== '');

if (count($amenities_clean) !== count(array_unique($amenities_clean))) {
    $_SESSION['error'] = "You selected the same amenity more than once. Please choose different amenities.";
    header("location: users/apartment_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
    exit;
}


    $del_amen = $conn->prepare("DELETE FROM rentspace_amenities WHERE rent_id = ?");
    $del_amen->bind_param("s", $rent_id);
    $del_amen->execute();

    if (!empty($amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO rentspace_amenities (rent_id, amen_id) VALUES (?, ?)");
        foreach ($amenities as $amen_id) {
            if ($amen_id !== '') {
                $insert_amen->bind_param("ss", $rent_id, $amen_id);
                $insert_amen->execute();
            }
        }
    }

    $_SESSION['success'] = "Successfully Updated";
    header("location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}

if(isset($_POST['add_favorite_btn'])){

    $locate = $_POST['locate'];
    $type = $_POST['type'];
    $rent_id = $_POST['rent_id'];

    $check = $conn->prepare("SELECT * FROM `favorites` WHERE `user_id` = ? AND `rent_id` = ?");
    $check->bind_param("ss",$user_id_login,$rent_id);
    $check->execute();
    $result_data = $check->get_result();
    if($result_data->num_rows>0){
        while($data_fav = mysqli_fetch_assoc($result_data)){
            $fav_id = $data_fav['fav_id'];
            
            $delete= $conn->prepare("DELETE FROM favorites WHERE `fav_id` =?");
            $delete->bind_param("i",$fav_id);
            $delete->execute();

            header("location:users/$locate?id=" . urlencode($rent_id));
            exit;

        }
    }

    $insert = $conn->prepare("INSERT INTO `favorites` (`rent_id`,`user_id`,`type`) VALUES (?,?,?)");
    $insert->bind_param("sss", $rent_id, $user_id_login,$type);
    $insert->execute();

    
    header("location:users/$locate?id=" . urlencode($rent_id));
    exit;
}



if (isset($_POST['send_message'])) {


    $sender_id   = $user_id_login; 
    $receiver_id = trim($_POST['receiver_id'] ?? '');
    $message     = trim($_POST['message'] ?? '');

    if (empty($sender_id) || empty($receiver_id)) {
        die("<h3 style='color:red;'>ERROR: Empty Sender ID or Receiver ID!</h3>" . 
            "Sender ID: '" . htmlspecialchars($sender_id) . "'<br>" . 
            "Receiver ID: '" . htmlspecialchars($receiver_id) . "'<br>" . 
            "<i>Paki-check kung tama ang \$_SESSION variable name ng logged in user.</i>");
    }

    $has_files = false;
    $valid_files = [];

    if (isset($_FILES['chat_attachments']) && !empty($_FILES['chat_attachments']['name'][0])) {
        $files = $_FILES['chat_attachments'];
        $file_count = count($files['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $has_files = true;
                $valid_files[] = [
                    'name'     => $files['name'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'size'     => $files['size'][$i]
                ];
            }
        }
    }

    $has_text = !empty($message);

    if (!$has_text && !$has_files) {
        die("<h3 style='color:orange;'>WARNING: Walang mensahe o file na ipinadala!</h3>");
    }

    // Determine Message Type
    if ($has_text && $has_files) {
        $message_type = 'text_and_files';
    } else if ($has_text && !$has_files) {
        $message_type = 'text_only';
    } else {
        $message_type = 'files_only';
    }

    $status    = 'unseen';
    $time_sent = date('H:i:s'); 
    $date_sent = date('Y-m-d'); 
    
    // Gawa ng unique message ID (VARCHAR)
    $message_id = 'MSG-' . uniqid(); 

    // INSERT QUERY
    $sql = "INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `status`, `time_sent`, `date_sent`, `message_type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("<h3 style='color:red;'>SQL Prepare Error sa 'messages' table:</h3> " . $conn->error);
    }

    $stmt->bind_param("ssssssss", $message_id, $sender_id, $receiver_id, $message, $status, $time_sent, $date_sent, $message_type);

    if ($stmt->execute()) {
        $stmt->close();

        // FILE UPLOAD PROCESS
        if ($has_files) {
            $upload_dir = "assets/uploads/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $stmt_file = $conn->prepare("INSERT INTO `messages_uploaded` (`message_id`, `file_name`) VALUES (?, ?)");
            
            if (!$stmt_file) {
                die("<h3 style='color:red;'>SQL Prepare Error sa 'messages_uploaded' table:</h3> " . $conn->error);
            }

            foreach ($valid_files as $file) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $unique_file_name = time() . '_' . uniqid() . '.' . $file_extension;
                $target_path = $upload_dir . $unique_file_name;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $stmt_file->bind_param("ss", $message_id, $unique_file_name);
                    if (!$stmt_file->execute()) {
                        echo "<p style='color:red;'>Failed to insert file record: " . $stmt_file->error . "</p>";
                    }
                } else {
                    echo "<p style='color:red;'>Failed to move uploaded file to target path!</p>";
                }
            }
            $stmt_file->close();
        }


       header("Location: users/chat_portal.php?id=" . urlencode($receiver_id));
        exit;

    } else {
        die("<h3 style='color:red;'>Database Execute Error:</h3> " . $stmt->error);
    }
}



if(isset($_POST['save_condo'])){
    
    $landlord_id    = $_POST['landlord_id'] ?? '';
    $condo_name     = trim($_POST['condo_name'] ?? '');
    $condo_price    = trim($_POST['condo_price'] ?? '');
    $square_area    = trim($_POST['square_area'] ?? '');
    $bedroom_type   = $_POST['type'] ?? '';
    $bathrooms      = $_POST['bathrooms'] ?? '';
    $condition      = $_POST['condition'] ?? '';
    $flooring       = $_POST['flooring'] ?? '';
    $other_info     = trim($_POST['apartment_other_info'] ?? '');
    $raw_amenities  = $_POST['apartment_amenity'] ?? [];

    $status       = "Available";
    $rent_id      = $condo_name . rand(1000, 9999);
    $type         = "Condominium";
    $condo_id     = $condo_name . rand();

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Validate amenities — no duplicates
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/apartment_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }

    // Store in session para ma-retain pag may error
    $_SESSION['condo_name']           = $condo_name;
    $_SESSION['condo_price']          = $condo_price;
    $_SESSION['square_area']          = $square_area;
    $_SESSION['type']                 = $bedroom_type;
    $_SESSION['bathrooms']            = $bathrooms;
    $_SESSION['condition']            = $condition;
    $_SESSION['flooring']             = $flooring;
    $_SESSION['apartment_other_info'] = $other_info;
    $_SESSION['amenities']            = $raw_amenities ?? [];

    // --- COVER PHOTO ---
    if (isset($_FILES['apartment_cover']) && $_FILES['apartment_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['apartment_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['apartment_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_condo_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $_SESSION['condo_cover'] = $new_cover;
        }
    } elseif (!empty($_POST['old_cover'])) {
        // Retain old cover if walang bagong upload
        $_SESSION['condo_cover'] = $_POST['old_cover'];
    }

    $cover_photo = $_SESSION['condo_cover'] ?? '';

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/condo_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- GALLERY PHOTOS ---
    if (!empty($_FILES['gallery']['name'][0])) {
        $new_gallery = [];
        foreach ($_FILES['gallery']['name'] as $i => $img_name) {
            if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext      = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $new_name = uniqid() . '_condo_' . $i . '.' . $ext;
            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                $new_gallery[] = $new_name;
            }
        }
        if (!empty($new_gallery)) {
            $_SESSION['gallery'] = $new_gallery;
        }
    }
    $gallery_images = $_SESSION['gallery'] ?? [];

    if (count($gallery_images) < 3 || count($gallery_images) > 10) {
        $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
        header("Location: users/condo_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- CHECK DUPLICATE ---
    $check_condo = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
    $check_condo->bind_param("ss", $landlord_id, $condo_name);
    $check_condo->execute();
    $result_condo_name = $check_condo->get_result();

    if ($result_condo_name->num_rows > 0) {
        $_SESSION['error'] = "$condo_name Already Exists";
        header("Location: users/condo_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- INSERT MAIN TABLE ---
    $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssssss", $rent_id, $condo_name, $landlord_id, $user_id_login, $type, $condo_price, $cover_photo, $other_info);
    $insert->execute();

    // --- INSERT AMENITIES ---
    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- INSERT GALLERY ---
    if (!empty($gallery_images)) {
        $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
        foreach ($gallery_images as $img) {
            $insert_gallery->bind_param("ss", $img, $rent_id);
            $insert_gallery->execute();
        }
    }


    if (!empty($bedroom_type)) {
        $insert_condo = $conn->prepare("INSERT INTO `condo` (`condo_id`, `rent_id`, `square_area`, `bedroom_type`, `bathrooms`, `cond_condition`, `flooring`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_condo->bind_param("ssssssss", $condo_id, $rent_id, $square_area, $bedroom_type, $bathrooms, $condition, $flooring, $status);
        $insert_condo->execute();
    }

    // --- CLEAR SESSION ---
    unset(
        $_SESSION['condo_name'],
        $_SESSION['condo_price'],
        $_SESSION['square_area'],
        $_SESSION['type'],
        $_SESSION['bathrooms'],
        $_SESSION['condition'],
        $_SESSION['flooring'],
        $_SESSION['apartment_other_info'],
        $_SESSION['condo_cover'],
        $_SESSION['gallery'],
        $_SESSION['amenities']
    );

    $_SESSION['success'] = "Condominium Successfully Inserted";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}


if (isset($_POST['edit_condo'])) {

    $rent_id               = $_POST['rent_id'] ?? '';
    $landlord_id           = $_POST['landlord_id'] ?? '';
    $condo_name            = trim($_POST['condo_name'] ?? '');
    $condo_price           = trim($_POST['condo_price'] ?? '');
    $apartment_other_info  = trim($_POST['apartment_other_info'] ?? '');
    
    $square_area           = trim($_POST['square_area'] ?? '');
    $bedroom_type          = $_POST['type'] ?? '';
    $bathrooms             = $_POST['bathrooms'] ?? '';
    $condition             = $_POST['condition'] ?? '';
    $flooring              = $_POST['flooring'] ?? '';
    $status                = $_POST['status'] ?? '';
    $rent_id                = $_POST['id'] ?? '';
    
    $amenities             = $_POST['apartment_amenity'] ?? [];

     // Validate amenities — no duplicates
    $selected_amenities = [];
    foreach ($amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/condo_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }


    if (empty($rent_id) || empty($condo_name) || empty($condo_price)) {
        $_SESSION['error'] = "fillup all fields.";
        header("Location: users/edit_condo.php?property_id={$landlord_id}&id={$rent_id}");
        exit;
    }


    $cover_filename = $_POST['old_cover'] ?? ''; 

    if (isset($_FILES['apartment_cover']) && $_FILES['apartment_cover']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['apartment_cover']['tmp_name'];
        $file_name = $_FILES['apartment_cover']['name'];
        $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png'];
        if (in_array($ext, $allowed)) {
            $new_cover_name = time() . '_cover_' . uniqid() . '.' . $ext;
            $upload_path    = 'assets/uploads/' . $new_cover_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $cover_filename = $new_cover_name;
            }
        }
    }

        // --- CHECK DUPLICATE ---
    $check_condo = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ? AND `rent_id` != ?");
    $check_condo->bind_param("sss", $landlord_id, $condo_name,$rent_id);
    $check_condo->execute();
    $result_condo_name = $check_condo->get_result();

    if ($result_condo_name->num_rows > 0) {
        $_SESSION['error'] = "$condo_name Already Exists";
        header("Location: users/condo_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }


    $stmt_rent = $conn->prepare("
        UPDATE rentspace 
        SET name = ?, price = ?, image_cover = ?, other_info = ? 
        WHERE rent_id = ?
    ");
    $stmt_rent->bind_param("sssss", $condo_name, $condo_price, $cover_filename, $apartment_other_info, $rent_id);
    $stmt_rent->execute();


    $stmt_condo = $conn->prepare("
        UPDATE condo 
        SET square_area = ?, bedroom_type = ?, bathrooms = ?, `cond_condition` = ?, flooring = ?, status = ? 
        WHERE rent_id = ?
    ");
    $stmt_condo->bind_param("sssssss", $square_area, $bedroom_type, $bathrooms, $condition, $flooring, $status, $rent_id);
    $stmt_condo->execute();

  
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
      
        $del_gallery = $conn->prepare("DELETE FROM gallery2 WHERE rent_id = ?");
        $del_gallery->bind_param("s", $rent_id);
        $del_gallery->execute();


        $total_files = count($_FILES['gallery']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $g_tmp  = $_FILES['gallery']['tmp_name'][$i];
                $g_name = $_FILES['gallery']['name'][$i];
                $g_ext  = strtolower(pathinfo($g_name, PATHINFO_EXTENSION));

                if (in_array($g_ext, ['jpg', 'jpeg', 'png'])) {
                    $new_gallery_name = time() . '_gallery_' . $i . '_' . uniqid() . '.' . $g_ext;
                    $g_upload_path    = 'assets/uploads/' . $new_gallery_name;

                    if (move_uploaded_file($g_tmp, $g_upload_path)) {
                        $ins_gallery = $conn->prepare("INSERT INTO gallery2 (rent_id, image) VALUES (?, ?)");
                        $ins_gallery->bind_param("ss", $rent_id, $new_gallery_name);
                        $ins_gallery->execute();
                    }
                }
            }
        }
    }

    $del_amen = $conn->prepare("DELETE FROM rentspace_amenities WHERE rent_id = ?");
    $del_amen->bind_param("s", $rent_id);
    $del_amen->execute();


    if (!empty($amenities)) {
        $ins_amen = $conn->prepare("INSERT INTO rentspace_amenities (rent_id, amen_id) VALUES (?, ?)");
        foreach ($amenities as $amen_id) {
            if (!empty($amen_id)) {
                $ins_amen->bind_param("ss", $rent_id, $amen_id);
                $ins_amen->execute();
            }
        }
    }


    $_SESSION['success'] = "Successfully Updated Condominium details!";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}

if (isset($_POST['save_house'])) {
    
    // Core details
    $landlord_id    = $_POST['landlord_id'] ?? '';
    $house_name     = trim($_POST['house_name'] ?? '');
    $house_price    = trim($_POST['house_price'] ?? '');
    $square_area    = trim($_POST['square_area'] ?? '');
    $house_type     = $_POST['type'] ?? '';
    $bedroom        = $_POST['bedroom'] ?? '';
    $bathrooms      = $_POST['bathrooms'] ?? '';
    $flooring       = $_POST['flooring'] ?? '';
    $parking        = $_POST['parking'] ?? '';
    $other_info     = trim($_POST['apartment_other_info'] ?? '');
    $raw_amenities  = $_POST['apartment_amenity'] ?? [];

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    
    // --- COVER PHOTO ---
    if (isset($_FILES['house_cover']) && $_FILES['house_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['house_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['house_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_house_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $_SESSION['house_cover'] = $new_cover;
        }
    } elseif (!empty($_POST['old_cover'])) {
        $_SESSION['house_cover'] = $_POST['old_cover'];
    }

    $cover_photo = $_SESSION['house_cover'] ?? '';

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/house_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    
    // --- GALLERY PHOTOS ---
    if (!empty($_FILES['gallery']['name'][0])) {
        $new_gallery = [];
        foreach ($_FILES['gallery']['name'] as $i => $img_name) {
            if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext      = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $new_name = uniqid() . '_house_' . $i . '.' . $ext;
            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                $new_gallery[] = $new_name;
            }
        }
        if (!empty($new_gallery)) {
            $_SESSION['gallery'] = $new_gallery;
        }
    }
    $gallery_images = $_SESSION['gallery'] ?? [];

    if (count($gallery_images) < 3 || count($gallery_images) > 10) {
        $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
        header("Location: users/house_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }
 
    // --- STORE IN SESSION FOR FORM RE-POPULATION ---
    $_SESSION['house_name']           = $house_name;
    $_SESSION['house_price']          = $house_price;
    $_SESSION['square_area']          = $square_area;
    $_SESSION['type']                 = $house_type;
    $_SESSION['bedroom']              = $bedroom;
    $_SESSION['bathrooms']            = $bathrooms;
    $_SESSION['flooring']             = $flooring;
    $_SESSION['parking']              = $parking;
    $_SESSION['apartment_other_info'] = $other_info;
    $_SESSION['amenities']            = $raw_amenities;
    $_SESSION['house_cover']            = $cover_photo;

    
    $status   = "Available";
    $rent_id  = "HS" . rand(1000, 9999);
    $type     = "House";
    $house_id = "HOU" . rand(10000, 99999);



       // --- VALIDATE AMENITIES (No duplicates) ---
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/house_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }




    // --- CHECK DUPLICATE ---
    $check_house = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
    $check_house->bind_param("ss", $landlord_id, $house_name);
    $check_house->execute();
    $result_house_name = $check_house->get_result();

    if ($result_house_name->num_rows > 0) {
        $_SESSION['error'] = "$house_name Already Exists";
        header("Location: users/house_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- INSERT MAIN RENTSPACE TABLE ---
    $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssssss", $rent_id, $house_name, $landlord_id, $user_id_login, $type, $house_price, $cover_photo, $other_info);
    $insert->execute();
    $status = "Available";
    // --- INSERT HOUSE TABLE ---
    $insert_house = $conn->prepare("INSERT INTO `house` (`house_id`, `rent_id`, `area`, `type`, `bedroom`, `bathrooms`, `flooring`, `parking`,`status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
    $insert_house->bind_param("sssssssss", $house_id, $rent_id, $square_area, $house_type, $bedroom, $bathrooms, $flooring, $parking,$status);
    $insert_house->execute();

    // --- INSERT AMENITIES ---
    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- INSERT GALLERY ---
    if (!empty($gallery_images)) {
        $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
        foreach ($gallery_images as $img) {
            $insert_gallery->bind_param("ss", $img, $rent_id);
            $insert_gallery->execute();
        }
    }

    // --- CLEAR SESSION FORM DATA ---
    unset(
        $_SESSION['house_name'],
        $_SESSION['house_price'],
        $_SESSION['square_area'],
        $_SESSION['type'],
        $_SESSION['bedroom'],
        $_SESSION['bathrooms'],
        $_SESSION['flooring'],
        $_SESSION['parking'],
        $_SESSION['apartment_other_info'],
        $_SESSION['house_cover'],
        $_SESSION['gallery'],
        $_SESSION['amenities']
    );

    $_SESSION['success'] = "House Successfully Inserted";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}

if (isset($_POST['edit_house'])) {

    $rent_id        = $_POST['rent_id'] ?? '';
    $landlord_id    = $_POST['landlord_id'] ?? '';
    $house_name     = trim($_POST['house_name'] ?? '');
    $house_price    = trim($_POST['house_price'] ?? '');
    $square_area    = trim($_POST['square_area'] ?? '');
    $house_type     = $_POST['type'] ?? '';
    $bedroom        = $_POST['bedroom'] ?? '';
    $bathrooms      = $_POST['bathrooms'] ?? '';
    $flooring       = $_POST['flooring'] ?? '';
    $parking        = $_POST['parking'] ?? '';
    $status        = $_POST['status'] ?? '';
    $other_info     = trim($_POST['apartment_other_info'] ?? '');
    $raw_amenities  = $_POST['apartment_amenity'] ?? [];
    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // --- COVER PHOTO PROCESSING ---
    $cover_photo = $_POST['old_cover'] ?? '';

    if (isset($_FILES['house_cover']) && $_FILES['house_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['house_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['house_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_house_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $cover_photo = $new_cover;
        }
    }

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- AMENITIES VALIDATION ---
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }

    // --- CHECK DUPLICATE NAME ---
    $check_house = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ? AND `rent_id` != ?");
    $check_house->bind_param("sss", $landlord_id, $house_name, $rent_id);
    $check_house->execute();
    $result_house_name = $check_house->get_result();

    if ($result_house_name->num_rows > 0) {
        $_SESSION['error'] = "$house_name Already Exists";
        header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- UPDATE MAIN RENTSPACE TABLE ---
    $update_rentspace = $conn->prepare("UPDATE `rentspace` SET `name` = ?, `price` = ?, `image_cover` = ?, `other_info` = ? WHERE `rent_id` = ? AND `landlord_id` = ?");
    $update_rentspace->bind_param("ssssss", $house_name, $house_price, $cover_photo, $other_info, $rent_id, $landlord_id);
    $update_rentspace->execute();

    // --- UPDATE HOUSE SPECIFICATIONS TABLE ---
    $update_house = $conn->prepare("UPDATE `house` SET `area` = ?, `type` = ?, `bedroom` = ?, `bathrooms` = ?, `flooring` = ?, `parking` = ?, `status` = ? WHERE `rent_id` = ?");
    $update_house->bind_param("ssssssss", $square_area, $house_type, $bedroom, $bathrooms, $flooring, $parking, $rent_id,$status);
    $update_house->execute();

    // --- UPDATE AMENITIES ---
    $del_amen = $conn->prepare("DELETE FROM `rentspace_amenities` WHERE `rent_id` = ?");
    $del_amen->bind_param("s", $rent_id);
    $del_amen->execute();

    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- GALLERY PROCESSING (UPDATES ONLY WHEN NEW FILES ARE SELECTED) ---
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {

        $total_files = count($_FILES['gallery']['name']);

        // Optional: Validate 3 to 10 photos only when user uploads new ones
        if ($total_files < 3 || $total_files > 10) {
            $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
            header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }

        // 1. Delete old gallery entries from database
        $del_gallery = $conn->prepare("DELETE FROM gallery2 WHERE rent_id = ?");
        $del_gallery->bind_param("s", $rent_id);
        $del_gallery->execute();

        // 2. Upload and insert new gallery images
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $g_tmp  = $_FILES['gallery']['tmp_name'][$i];
                $g_name = $_FILES['gallery']['name'][$i];
                $g_ext  = strtolower(pathinfo($g_name, PATHINFO_EXTENSION));

                if (in_array($g_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $new_gallery_name = time() . '_gallery_' . $i . '_' . uniqid() . '.' . $g_ext;
                    $g_upload_path    = $uploadDir . $new_gallery_name;

                    if (move_uploaded_file($g_tmp, $g_upload_path)) {
                        $ins_gallery = $conn->prepare("INSERT INTO gallery2 (rent_id, image) VALUES (?, ?)");
                        $ins_gallery->bind_param("ss", $rent_id, $new_gallery_name);
                        $ins_gallery->execute();
                    }
                }
            }
        }
    }

    $_SESSION['success'] = "House Details Successfully Updated";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}



if (isset($_POST['save_cs'])) {
    
    // Core details
    $landlord_id   = $_POST['landlord_id'] ?? '';
    $cs_name       = trim($_POST['cs_name'] ?? '');
    $cs_price      = trim($_POST['cs_price'] ?? '');
    $square_area   = trim($_POST['square_area'] ?? '');
    $cs_type       = $_POST['type'] ?? '';
    $other_info    = trim($_POST['cs_other_info'] ?? '');
    $raw_amenities = $_POST['cs_amenity'] ?? [];

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach($raw_amenities as $amen){
        echo "$amen <br>";
    }

    // --- COVER PHOTO ---

    if (isset($_FILES['cs_cover']) && $_FILES['cs_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['cs_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['cs_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_cs_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $_SESSION['cs_cover'] = $new_cover;
        }
    } elseif (!empty($_POST['old_cover'])) {
        $_SESSION['cs_cover'] = $_POST['old_cover'];
    }

    $cover_photo = $_SESSION['cs_cover'] ?? '';

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- GALLERY PHOTOS ---
    if (!empty($_FILES['gallery']['name'][0])) {
        $new_gallery = [];
        foreach ($_FILES['gallery']['name'] as $i => $img_name) {
            if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext      = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $new_name = uniqid() . '_cs_' . $i . '.' . $ext;
            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                $new_gallery[] = $new_name;
            }
        }
        if (!empty($new_gallery)) {
            $_SESSION['gallery'] = $new_gallery;
        }
    }
    $gallery_images = $_SESSION['gallery'] ?? [];

    if (count($gallery_images) < 3 || count($gallery_images) > 10) {
        $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
        header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- STORE IN SESSION FOR FORM RE-POPULATION ---
    $_SESSION['cs_name']       = $cs_name;
    $_SESSION['cs_price']      = $cs_price;
    $_SESSION['square_area']   = $square_area;
    $_SESSION['type']          = $cs_type;
    $_SESSION['cs_other_info'] = $other_info;
    $_SESSION['amenities']     = $raw_amenities;
    $_SESSION['cs_cover']      = $cover_photo;

    $status = "Available";
    $rent_id = "CS" . rand(1000, 9999);
    $type = "Commercial Space";
    $cs_id = "COM" . rand(10000, 99999);

    // --- VALIDATE AMENITIES (No duplicates) ---
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }

    // --- CHECK DUPLICATE ---
    $check_cs = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
    $check_cs->bind_param("ss", $landlord_id, $cs_name);
    $check_cs->execute();
    $result_cs_name = $check_cs->get_result();

    if ($result_cs_name->num_rows > 0) {
        $_SESSION['error'] = "$cs_name Already Exists";
        header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
        exit;
    }

    // --- INSERT MAIN RENTSPACE TABLE ---
    $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssssss", $rent_id, $cs_name, $landlord_id, $user_id_login, $type, $cs_price, $cover_photo, $other_info);
    $insert->execute();

    // --- INSERT COMMERCIAL_SPACE TABLE ---
    $insert_cs = $conn->prepare("INSERT INTO `commercial_space` (`cs_id`, `rent_id`, `area`, `type`, `status`) VALUES (?, ?, ?, ?, ?)");
    $insert_cs->bind_param("sssss", $cs_id, $rent_id, $square_area, $cs_type, $status);
    $insert_cs->execute();

    // --- INSERT AMENITIES ---
    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- INSERT GALLERY ---
    if (!empty($gallery_images)) {
        $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
        foreach ($gallery_images as $img) {
            $insert_gallery->bind_param("ss", $img, $rent_id);
            $insert_gallery->execute();
        }
    }

    // --- CLEAR SESSION FORM DATA ---
    unset(
        $_SESSION['cs_name'],
        $_SESSION['cs_price'],
        $_SESSION['square_area'],
        $_SESSION['type'],
        $_SESSION['cs_other_info'],
        $_SESSION['cs_cover'],
        $_SESSION['gallery'],
        $_SESSION['amenities']
    );

    $_SESSION['success'] = "Commercial Space Successfully Inserted";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}


if (isset($_POST['update_cs'])) {

    $rent_id        = $_POST['rent_id'] ?? '';
    $landlord_id    = $_POST['landlord_id'] ?? '';
    $cs_name        = trim($_POST['cs_name'] ?? '');
    $cs_price       = trim($_POST['cs_price'] ?? '');
    $square_area    = trim($_POST['square_area'] ?? '');
    $house_type     = $_POST['type'] ?? '';
    $other_info     = trim($_POST['cs_other_info'] ?? '');
    $raw_amenities  = $_POST['cs_amenity'] ?? [];
    $status     = $_POST['status'] ?? '';

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // --- COVER PHOTO PROCESSING ---
    $cover_photo = $_POST['old_cover'] ?? '';

    if (isset($_FILES['cs_cover']) && $_FILES['cs_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['cs_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['cs_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_cs_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            $cover_photo = $new_cover;
        }
    }

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- AMENITIES VALIDATION (PREVENT DUPLICATES & SAVE STOP) ---
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '') continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Duplicate amenities selected. Please choose unique amenities.";
            header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit; 
        }
        $selected_amenities[] = $amenity_id;
    }

    // --- CHECK DUPLICATE NAME ---
    $check_house = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ? AND `rent_id` != ?");
    $check_house->bind_param("sss", $landlord_id, $cs_name, $rent_id);
    $check_house->execute();
    $result_house_name = $check_house->get_result();

    if ($result_house_name->num_rows > 0) {
        $_SESSION['error'] = "$cs_name Already Exists";
        header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- GALLERY VALIDATION (CHECK COUNT BEFORE DB UPDATES) ---
    $has_new_gallery = isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0]);
    if ($has_new_gallery) {
        $total_files = count($_FILES['gallery']['name']);
        if ($total_files < 3 || $total_files > 10) {
            $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
            header("Location: users/house_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }
    }

    // --- UPDATE MAIN RENTSPACE TABLE ---
    $update_rentspace = $conn->prepare("UPDATE `rentspace` SET `name` = ?, `price` = ?, `image_cover` = ?, `other_info` = ? WHERE `rent_id` = ? AND `landlord_id` = ?");
    $update_rentspace->bind_param("ssssss", $cs_name, $cs_price, $cover_photo, $other_info, $rent_id, $landlord_id);
    $update_rentspace->execute();

    // --- UPDATE COMMERCIAL SPACE TABLE ---
    $update_cs = $conn->prepare("UPDATE `commercial_space` SET `area` = ?, `type` = ?, `status` =? WHERE `rent_id` = ?");
    $update_cs->bind_param("ssss", $square_area, $house_type, $rent_id,$status);
    $update_cs->execute();

    // --- UPDATE AMENITIES ---
    $del_amen = $conn->prepare("DELETE FROM `rentspace_amenities` WHERE `rent_id` = ?");
    $del_amen->bind_param("s", $rent_id);
    $del_amen->execute();

    // Secondary safety check using array_unique
    $unique_amenities = array_unique($selected_amenities);
    if (!empty($unique_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($unique_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- GALLERY PROCESSING ---
    if ($has_new_gallery) {
        // 1. Delete old gallery entries from database
        $del_gallery = $conn->prepare("DELETE FROM gallery2 WHERE rent_id = ?");
        $del_gallery->bind_param("s", $rent_id);
        $del_gallery->execute();

        // 2. Upload and insert new gallery images
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $g_tmp  = $_FILES['gallery']['tmp_name'][$i];
                $g_name = $_FILES['gallery']['name'][$i];
                $g_ext  = strtolower(pathinfo($g_name, PATHINFO_EXTENSION));

                if (in_array($g_ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $new_gallery_name = time() . '_gallery_' . $i . '_' . uniqid() . '.' . $g_ext;
                    $g_upload_path    = $uploadDir . $new_gallery_name;

                    if (move_uploaded_file($g_tmp, $g_upload_path)) {
                        $ins_gallery = $conn->prepare("INSERT INTO gallery2 (rent_id, image) VALUES (?, ?)");
                        $ins_gallery->bind_param("ss", $rent_id, $new_gallery_name);
                        $ins_gallery->execute();
                    }
                }
            }
        }
    }

    $_SESSION['success'] = "Commercial Space Details Successfully Updated";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}


if(isset($_POST['save_es'])){


  
        $landlord_id = $_POST['landlord_id'] ?? '';
        $es_name = trim($_POST['es_name'] ?? '');
        $es_price = trim($_POST['es_price'] ?? '');
        $square_area = trim($_POST['square_area'] ?? '');
        $es_type = $_POST['type'] ?? '';
        $other_info = trim($_POST['es_other_info'] ?? '');
        $raw_amenities = $_POST['es_amenity'] ?? [];


        $uploadDir = 'assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // --- COVER PHOTO ---
        if (isset($_FILES['es_cover']) && $_FILES['es_cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['es_cover']['tmp_name'];
            $fileExtension = strtolower(pathinfo($_FILES['es_cover']['name'], PATHINFO_EXTENSION));
            $new_cover = time() . '_es_cover.' . $fileExtension;

            if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
                $_SESSION['es_cover'] = $new_cover;
            }
        } elseif (!empty($_POST['old_cover'])) {
            $_SESSION['es_cover'] = $_POST['old_cover'];
        }

        $cover_photo = $_SESSION['es_cover'] ?? '';

        if (empty($cover_photo)) {
            $_SESSION['error'] = "Cover photo is required";
            header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }

        // --- GALLERY PHOTOS ---
        if (!empty($_FILES['gallery']['name'][0])) {
            $new_gallery = [];
            foreach ($_FILES['gallery']['name'] as $i => $img_name) {
                if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK)
                    continue;
                $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
                $new_name = uniqid() . '_cs_' . $i . '.' . $ext;
                if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                    $new_gallery[] = $new_name;
                }
            }
            if (!empty($new_gallery)) {
                $_SESSION['gallery'] = $new_gallery;
            }
        }
        $gallery_images = $_SESSION['gallery'] ?? [];

        if (count($gallery_images) < 3 || count($gallery_images) > 10) {
            $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
            header("Location: users/cs_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }

        // --- VALIDATE AMENITIES ---
        $selected_amenities = [];
        foreach ($raw_amenities as $amenity_id) {
            $amenity_id = trim($amenity_id);
            if ($amenity_id === '')
                continue;

            if (in_array($amenity_id, $selected_amenities)) {
                $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
                header("Location: users/es_add.php?property_id=" . urlencode($landlord_id));
                exit;
            }
            $selected_amenities[] = $amenity_id;
        }


        foreach ($raw_amenities as $amen) {
            echo "$amen <br>";
        }
  

        // --- STORE IN SESSION FOR FORM RE-POPULATION ---
        $_SESSION['es_name']       = $es_name;
        $_SESSION['es_price']      = $es_price;
        $_SESSION['square_area']   = $square_area;
        $_SESSION['type']          = $es_type;
        $_SESSION['es_other_info'] = $other_info;
        $_SESSION['amenities']     = $raw_amenities;

 
        // --- CHECK DUPLICATE ---
        $check_cs = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ?");
        $check_cs->bind_param("ss", $landlord_id, $es_name);
        $check_cs->execute();
        $result_cs_name = $check_cs->get_result();

        if ($result_cs_name->num_rows > 0) {
            $_SESSION['error'] = "$es_name Already Exists";
            header("Location: users/es_add.php?property_id=" . urlencode($landlord_id));
            exit;
        }

        // --- GENERATE UNIQUE IDS ---
        $status = "Available";
        $rent_id = "ES" . rand(1000, 9999);
        $type = "Event Space";
        $es_id = "COM" . rand(10000, 99999);

        // --- INSERT MAIN RENTSPACE TABLE ---
        $insert = $conn->prepare("INSERT INTO `rentspace` (`rent_id`, `name`, `landlord_id`, `user_id`, `type`, `price`, `image_cover`, `other_info`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssssss", $rent_id, $es_name, $landlord_id, $user_id_login, $type, $es_price, $cover_photo, $other_info);
        $insert->execute();

        // --- INSERT EVENT_SPACE TABLE ---
        $insert_cs = $conn->prepare("INSERT INTO `event_space` (`es_id`, `rent_id`, `area`, `type`, `status`) VALUES (?, ?, ?, ?, ?)");
        $insert_cs->bind_param("sssss", $es_id, $rent_id, $square_area, $es_type, $status);
        $insert_cs->execute();

        // --- INSERT AMENITIES ---
        if (!empty($selected_amenities)) {
            $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
            foreach ($selected_amenities as $amenity_id) {
                $insert_amen->bind_param("si", $rent_id, $amenity_id);
                $insert_amen->execute();
            }
        }

        // --- INSERT GALLERY ---
        if (!empty($gallery_images)) {
            $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
            foreach ($gallery_images as $img) {
                $insert_gallery->bind_param("ss", $img, $rent_id);
                $insert_gallery->execute();
            }
        }

        // --- CLEAR SESSION FORM DATA ---
        unset(
            $_SESSION['es_name'],
            $_SESSION['es_price'],
            $_SESSION['square_area'],
            $_SESSION['type'],
            $_SESSION['es_other_info'],
            $_SESSION['es_cover'],
            $_SESSION['gallery'],
            $_SESSION['amenities']
        );

        $_SESSION['success'] = "Event Space Successfully Inserted";
        header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
        exit;

    } 

if (isset($_POST['update_es'])) {

    $landlord_id = $_POST['landlord_id'] ?? '';
    $rent_id     = $_POST['rent_id'] ?? '';
    $es_id       = $_POST['cs_id'] ?? ''; // From input name="cs_id"

    $es_name     = trim($_POST['es_name'] ?? '');
    $es_price    = trim($_POST['es_price'] ?? '');
    $square_area = trim($_POST['square_area'] ?? '');
    $es_type     = $_POST['type'] ?? '';
    $status      = $_POST['status'] ?? '';
    $other_info  = trim($_POST['cs_other_info'] ?? '');
    $raw_amenities = $_POST['cs_amenity'] ?? [];

    $uploadDir = 'assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // --- 1. COVER PHOTO HANDLER ---
    $cover_photo = $_POST['old_cover'] ?? '';
    if (isset($_FILES['cs_cover']) && $_FILES['cs_cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['cs_cover']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['cs_cover']['name'], PATHINFO_EXTENSION));
        $new_cover     = time() . '_es_cover.' . $fileExtension;

        if (move_uploaded_file($fileTmpPath, $uploadDir . $new_cover)) {
            // Delete old file if a new one is uploaded
            if (!empty($cover_photo) && file_exists($uploadDir . $cover_photo)) {
                @unlink($uploadDir . $cover_photo);
            }
            $cover_photo = $new_cover;
        }
    }

    if (empty($cover_photo)) {
        $_SESSION['error'] = "Cover photo is required";
        header("Location: users/es_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- 2. GALLERY PHOTOS HANDLER ---
    // Retrieve existing photos from DB
    $existing_gallery = [];
    $get_existing_gal = $conn->prepare("SELECT image FROM `gallery2` WHERE `rent_id` = ?");
    $get_existing_gal->bind_param("s", $rent_id);
    $get_existing_gal->execute();
    $res_gal = $get_existing_gal->get_result();
    while ($row = $res_gal->fetch_assoc()) {
        $existing_gallery[] = $row['image'];
    }

    $gallery_images = $existing_gallery;

    // Replace gallery if new files are uploaded
    if (!empty($_FILES['gallery']['name'][0])) {
        $new_gallery = [];
        foreach ($_FILES['gallery']['name'] as $i => $img_name) {
            if ($_FILES['gallery']['error'][$i] !== UPLOAD_ERR_OK)
                continue;

            $ext      = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
            $new_name = uniqid() . '_es_' . $i . '.' . $ext;

            if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $uploadDir . $new_name)) {
                $new_gallery[] = $new_name;
            }
        }

        if (!empty($new_gallery)) {
            // Delete old physical files from server
            foreach ($existing_gallery as $old_img) {
                if (file_exists($uploadDir . $old_img)) {
                    @unlink($uploadDir . $old_img);
                }
            }
            $gallery_images = $new_gallery;
        }
    }

    if (count($gallery_images) < 3 || count($gallery_images) > 10) {
        $_SESSION['error'] = "Please upload 3 to 10 photos for the gallery";
        header("Location: users/es_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- 3. AMENITIES VALIDATION ---
    $selected_amenities = [];
    foreach ($raw_amenities as $amenity_id) {
        $amenity_id = trim($amenity_id);
        if ($amenity_id === '')
            continue;

        if (in_array($amenity_id, $selected_amenities)) {
            $_SESSION['error'] = "Amenities Selected Must Not Be The Same";
            header("Location: users/es_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
            exit;
        }
        $selected_amenities[] = $amenity_id;
    }

    // --- 4. CHECK DUPLICATE NAME (EXCLUDING CURRENT RENT_ID) ---
    $check_cs = $conn->prepare("SELECT 1 FROM `rentspace` WHERE `landlord_id` = ? AND `name` = ? AND `rent_id` != ?");
    $check_cs->bind_param("sss", $landlord_id, $es_name, $rent_id);
    $check_cs->execute();
    if ($check_cs->get_result()->num_rows > 0) {
        $_SESSION['error'] = "$es_name Already Exists";
        header("Location: users/es_edit.php?property_id=" . urlencode($landlord_id) . "&id=" . urlencode($rent_id));
        exit;
    }

    // --- 5. UPDATE MAIN RENTSPACE TABLE ---
    $update_rent = $conn->prepare("UPDATE `rentspace` SET `name` = ?, `price` = ?, `image_cover` = ?, `other_info` = ? WHERE `rent_id` = ?");
    $update_rent->bind_param("sssss", $es_name, $es_price, $cover_photo, $other_info, $rent_id);
    $update_rent->execute();

    // --- 6. UPDATE EVENT_SPACE TABLE ---
    $update_es = $conn->prepare("UPDATE `event_space` SET `area` = ?, `type` = ?, `status` = ? WHERE `rent_id` = ?");
    $update_es->bind_param("ssss", $square_area, $es_type, $status, $rent_id);
    $update_es->execute();

    // --- 7. REFRESH AMENITIES ---
    $del_amen = $conn->prepare("DELETE FROM `rentspace_amenities` WHERE `rent_id` = ?");
    $del_amen->bind_param("s", $rent_id);
    $del_amen->execute();

    if (!empty($selected_amenities)) {
        $insert_amen = $conn->prepare("INSERT INTO `rentspace_amenities` (`rent_id`, `amen_id`) VALUES (?, ?)");
        foreach ($selected_amenities as $amenity_id) {
            $insert_amen->bind_param("si", $rent_id, $amenity_id);
            $insert_amen->execute();
        }
    }

    // --- 8. REFRESH GALLERY (IF NEW FILES WERE UPLOADED) ---
    if (!empty($_FILES['gallery']['name'][0]) && !empty($new_gallery)) {
        $del_gal = $conn->prepare("DELETE FROM `gallery2` WHERE `rent_id` = ?");
        $del_gal->bind_param("s", $rent_id);
        $del_gal->execute();

        $insert_gallery = $conn->prepare("INSERT INTO `gallery2` (`image`, `rent_id`) VALUES (?, ?)");
        foreach ($gallery_images as $img) {
            $insert_gallery->bind_param("ss", $img, $rent_id);
            $insert_gallery->execute();
        }
    }

    $_SESSION['success'] = "Event Space Updated Successfully";
    header("Location: users/my_property.php?property_id=" . urlencode($landlord_id));
    exit;
}