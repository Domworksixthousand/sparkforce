<?php
include '../config.php';

$status = "Pending";
$status_approved = 'Approved';
$total = $conn->prepare("
    SELECT COUNT(*) as total_pending 
    FROM `request_visit` r
    JOIN `landlord` l ON r.landlord_id = l.landlord_id
    WHERE r.status = ? AND l.status = ? AND l.user_id = ?
");
$total->bind_param("sss", $status, $status_approved, $user_id_login);
$total->execute();
$row_total = $total->get_result()->fetch_assoc();
$total_pending = $row_total['total_pending'];


    if($total_pending > 0){
            echo "
                <span class='indicator-item badge badge-error text-white'>
                    $total_pending
                </span>
             ";

    }