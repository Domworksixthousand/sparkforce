<?php
    include "../config.php";
    $status = "Pending";
    $not_user = "4";
    $count = $conn->prepare("SELECT COUNT(*) as total_acount_request FROM `accounts` WHERE `status` = ? AND `user_type` != ? ");
    $count->bind_param("ss", $status, $not_user);
    $count->execute();
    $count1 = $count->get_result();
    $total_acount_request = 0;
    if($count1->num_rows>0){
        while($user_c = mysqli_fetch_assoc($count1)){
            $total_acount_request = $user_c["total_acount_request"] ?? 0;
        }
    }
    if($total_acount_request > 0){
        echo "<span class=\"indicator-item badge badge-error text-white rounded-full\">".$total_acount_request."</span>";
    }
?>
