<?php

include 'config.php';

//connection sa email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


if (isset($_POST['register'])) {
    
    // 1. KUHANIN AT I-SANITIZE ANG MGA STANDARD INPUTS
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
    
    // Kunin ang nakatagong pangalan ng lumang file galing sa HTML hidden input kung mayroon man
    $old_id_photo   = mysqli_real_escape_string($conn, $_POST['old_id_photo'] ?? '');

    // [CRITICAL FIX]: I-lock agad ang nakaraang photo sa session kung mayroon na
    if (!empty($old_id_photo)) {
        $_SESSION['id_photo_name'] = $old_id_photo;
    }

    // Kunin ang hilaw na password para sa validation at hashing
    $password        = $_POST['password'] ?? '';
    $repeat_password = $_POST['repeat_password'] ?? '';

    // Iba pang default values
    $usertype  = "4";
    $status    = "Pending";
    $datetoday = date("Y-m-d"); 
    $user_id   = $id_number . rand();

    // 2. I-SAVE ANG MGA INPUT SA SESSIONS (Para hindi mabura ang mga sinagutan kapag nag-error)
    $fields = [
        'lastname', 'firstname', 'middlename', 'suffix', 'email', 'contact_number', 
        'province', 'municipality', 'barangay', 'zipcode', 'username', 'id_type', 
        'id_number', 'occupation'
    ];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION[$field] = $_POST[$field]; 
        }
    }

    if (isset($_POST['terms_agree'])) {
        $_SESSION['terms_agree'] = 1;
    }


    // =========================================================================
    // [STEP A]: FILE UPLOAD & TRANSFER HANDLING (DINALA SA TAAS)
    // =========================================================================
    $img_name = $_FILES['id_photo']['name'] ?? '';
    $tmp_name = $_FILES['id_photo']['tmp_name'] ?? ''; 
    $error    = $_FILES['id_photo']['error'] ?? UPLOAD_ERR_NO_FILE;

    $final_photo_name = ""; 
    $tomorrow         = date("Y-m-d", strtotime("+1 day"));

    // Siguraduhin nating gawa na ang mga folders bago mag-manipula ng files
    if (!file_exists('assets/trash_uploads')) mkdir('assets/trash_uploads', 0777, true);
    if (!file_exists('assets/uploads')) mkdir('assets/uploads', 0777, true);

    // KASO A: Walang bagong in-upload AT walang lumang photo sa session cache
    if ($error === UPLOAD_ERR_NO_FILE && empty($old_id_photo)) {
        $_SESSION['error'] = "Please upload ID photo!";
        header("location:signup.php");
        exit;
    }

    // KASO B: May tunay na error sa mismong upload mechanism ng PHP
    if ($error !== UPLOAD_ERR_OK && $error !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['error'] = "An error occurred during file upload. Error Code: " . $error;
        header("location:signup.php");
        exit;
    }

    // KASO C: May BAGONG larawan na in-upload ngayon
    if ($error === UPLOAD_ERR_OK && !empty($img_name)) {
        
        $image_trash   = $tomorrow . "_" . $img_name;
        $trash_folder  = 'assets/trash_uploads/' . $image_trash; 
        $location      = 'assets/uploads/' . $img_name;

        // I-upload muna sa iyong trash folder
        if (move_uploaded_file($tmp_name, $trash_folder)) {
            
            // I-move agad ito mula trash folder papunta sa permanent uploads directory
            if (rename($trash_folder, $location)) {
                $final_photo_name = $img_name;          
                $_SESSION['id_photo_name'] = $img_name; // I-update ang session para sa preview
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
    // KASO D: Gagamitin ang 'old_id_photo' galing sa trash cache
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


    // =========================================================================
    // [STEP B]: MGA VALIDATION CHECKS (DINALA SA BABA NG UPLOAD)
    // =========================================================================
    
    // Check kung magkatugma ang password
    if ($repeat_password !== $password) {
        $_SESSION['error'] = "Password and Repeat Password do not Match!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Email
    $get_email = $conn->prepare("SELECT email FROM accounts WHERE `email` = ? ");
    $get_email->bind_param("s", $email);
    $get_email->execute();
    if ($get_email->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email already Taken!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Contact Number
    $get_contact = $conn->prepare("SELECT contact_number FROM accounts WHERE `contact_number` = ? ");
    $get_contact->bind_param("s", $contact_number);
    $get_contact->execute();
    if ($get_contact->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Contact Number already Taken!";
        header("location:signup.php");
        exit;
    }

    // Check kung gamit na ang Username
    $get_user = $conn->prepare("SELECT username FROM accounts WHERE `username` = ? ");
    $get_user->bind_param("s", $username); 
    $get_user->execute();
    if ($get_user->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Username already Taken!";
        header("location:signup.php");
        exit;
    }


    // =========================================================================
    // [STEP C]: EMAIL VERIFICATION & TRANSACTION COMMIT
    // =========================================================================
    
    // Generate 6 digit random number
    $verification_code = rand(100000, 999999);
    $expiry_time       = time() + (5 * 60); 

    $_SESSION['email_verification'] = [
        'code'       => $verification_code,
        'email'      => $email, 
        'expires_at' => $expiry_time
    ];

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
        $mail->Username   = 'les.ballers2025@gmail.com';
        $mail->Password   = 'hzyi mwys xdsk npjv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('les.ballers2025@gmail.com', 'RENTSPACE');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();

        // 5. SECURELY HASH THE PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 6. DATA ENTRY
        $insert = $conn->prepare("INSERT INTO `accounts` (`user_id`,`middlename`,`lastname`,`firstname`,`suffix`,`email`,`contact_number`,`province`,`municipality`,`barangay`,`zipcode`,`username`,`password`,`id_type`,`id_number`,`id_photo`,`occupation`,`status`,`user_type`,`date_request`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        $insert->bind_param(
            "ssssssssssssssssssss", 
            $user_id, $middlename, $lastname, $firstname, $suffix, 
            $email, $contact_number, $province, $municipality, $barangay, 
            $zipcode, $username, $hashed_password, $id_type, $id_number, 
            $final_photo_name, $occupation, $status, $usertype, $datetoday
        );
        
        if ($insert->execute()) {
            // Linisin ang mga regular fields kapag success
            foreach ($fields as $field) {
                unset($_SESSION[$field]);
            }
            unset($_SESSION['terms_agree']);
            unset($_SESSION['error']);
            
            header("location:signup_confirmation.php");
            exit;
        } else {
            $_SESSION['error'] = "Registration failed. Database error occurred.";
            header("location:signup.php");
            exit;
        }
      
    } catch (Exception $e) {
        $_SESSION['error'] = "Failed to send email. Please check your internet connection or try again.";
        header("Location: signup.php");
        exit;
    }
}