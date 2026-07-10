<?php

include 'config.php';

if (isset($_POST['register'])) {
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $suffix = mysqli_real_escape_string($conn, $_POST['suffix']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $municipality = mysqli_real_escape_string($conn, $_POST['municipality']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $repeat_password = mysqli_real_escape_string($conn, $_POST['repeat_password']);
    $id_type = mysqli_real_escape_string($conn, $_POST['id_type']);
    $repeat_password = mysqli_real_escape_string($conn, $_POST['repeat_password']);
    $id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);

     //check kung hindi na click
    if(isset($_POST['terms_agree'])){
        $_SESSION['terms_agree'] = 1;
    }

    $img_name = $_FILES['id_photo']['name'];
    $img_size = $_FILES['id_photo']['size'];
    $tmp_name = $_FILES['id_photo']['tmp_name']; 
    $error    = $_FILES['id_photo']['error'];

    //create sessions
    $fields = [
    'lastname', 'firstname', 'middlename', 'suffix', 'email', 'contact_number', 
    'province', 'municipality', 'barangay', 'zipcode', 'username', 'password', 
    'id_type', 'id_number', 'occupation', 'repeat_password'
    ];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $_SESSION[$field] = mysqli_real_escape_string($conn, $_POST[$field]);
        }
    }

    if (isset($_FILES['id_photo']) && $_FILES['id_photo']['error'] === 0) {
        $_SESSION['id_photo_name'] = $_FILES['id_photo']['name'];
        $_SESSION['id_photo_tmp']  = $_FILES['id_photo']['tmp_name'];
        $_SESSION['id_photo_size'] = $_FILES['id_photo']['size'];
    }

   


    $_SESSION['success'] = "hey";
    header("location:signup.php");
    exit;
    




    

}