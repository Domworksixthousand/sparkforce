<?php
include '../config.php';

$status = "Pending";
$reports_count = 0;

$count_report = $conn->prepare("SELECT COUNT(*) as reports_count FROM `report` WHERE `status` = ?");
$count_report->bind_param("s", $status);
$count_report->execute();
$result_count = $count_report->get_result();

if ($result_count && $result_count->num_rows > 0) {
    $row_count = $result_count->fetch_assoc();
    $reports_count = $row_count['reports_count'] ?? 0;
}


    echo "
        <span class='indicator-item badge badge-error text-white'>
            $reports_count
        </span>
    ";
