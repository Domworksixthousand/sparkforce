  /*
        $mail->isSMTP();
        $mail->Host       = 'mail.rentspace.asia';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'renthub@rentspace.asia';
        $mail->Password   = 'paulobubloCflmanZ@123';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('renthub@rentspace.asia', 'RENTHUB');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        */
        
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