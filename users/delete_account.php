<?php

include '../config.php';
$status = "Deactivate";
$update = $conn->prepare("UPDATE accounts SET status = ? WHERE `user_id` = ?");
$update->bind_param("ss",$status,$user_id_login);
$update->execute();


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
    header("location:../index.php");
    exit();