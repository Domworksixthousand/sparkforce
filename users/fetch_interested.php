<?php
include '../config.php';
$fetch_pending = $conn->prepare("SELECT COUNT(*) as count FROM interested as i 
    JOIN landlord as l ON 
    l.landlord_id = i.landlord_id
    WHERE i.status = 'Pending' AND l.user_id = ?");
    $fetch_pending->bind_param("s", $user_id_login);
    $fetch_pending->execute();
    $result_fetch = $fetch_pending->get_result();
    if($result_fetch->num_rows > 0){
        $row = $result_fetch->fetch_assoc();
        $pending_count = $row['count'];
    } else {
        $pending_count = 0;
    }



      if($pending_count > 0){
            echo "
                <span class='indicator-item badge badge-error text-white'>
                    $pending_count
                </span>
             ";

    }