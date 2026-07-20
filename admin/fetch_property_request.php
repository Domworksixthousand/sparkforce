<?php
    include '../config.php';
    $status = "Pending";
    $count = $conn->prepare("SELECT COUNT(*) as total_property_request  FROM `landlord` WHERE  `status` = ? ");
    $count->bind_param("s",$status);
    $count->execute();
    $count1 = $count->get_result();
    if($count1->num_rows>0){
        while($user_c = mysqli_fetch_assoc($count1)){
        $total_property_request = $user_c['total_property_request'] ?? 0;
        }
    }

    if($total_property_request > 0){
            echo "
            <span class='indicator-item badge badge-error text-white rounded-full'>
                $total_property_request
            </span>
        ";

    }