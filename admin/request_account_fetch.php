<?php
    include '../config.php';
    $user_type = "3";
    $status = "Pending";
    $count = $conn->prepare("SELECT COUNT(*) as total_acount_request FROM `accounts` WHERE user_type = ? AND `status` = ? ");
    $count->bind_param("ss", $user_type, $status);
    $count->execute();
    $count1 = $count->get_result();
    if($count1->num_rows>0){
        while($user_c = mysqli_fetch_assoc($count1)){
        $total_acount_request = $user_c['total_acount_request'] ?? 0;
        }
    }

    if($total_acount_request > 0){
            echo "
            <span class='indicator-item badge badge-error text-white'>
                $total_acount_request
            </span>
        ";

    }