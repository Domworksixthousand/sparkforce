<?php
include '../config.php';
$status = "unseen";
$get_count_noti = $conn->prepare("SELECT COUNT(*)  as `total_notifications` FROM `notifications` WHERE `status` = ? AND receiver = ?");
$get_count_noti->bind_param("ss",$status,$user_id_login);
$get_count_noti->execute();
$result_get = $get_count_noti->get_result();
if($result_get->num_rows>0){
    while($row_c = mysqli_fetch_assoc($result_get)){
        $total_notifications = $row_c['total_notifications'];
    }
}

    if($total_notifications > 0){
            echo "
                <span class='indicator-item badge badge-error text-white'>
                    $total_notifications
                </span>
             ";

    }