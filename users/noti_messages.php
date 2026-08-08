<?php
include '../config.php';

        $status1 = "unseen";

        $count_messages = $conn->prepare("SELECT COUNT(*) as `total_messages` FROM `messages` WHERE `receiver_id` = ? AND `status` = ?");
        $count_messages->bind_param("ss", $user_id_login, $status1);
        $count_messages->execute();
        $result_count = $count_messages->get_result();
        if($result_count->num_rows>0){
            while($c = mysqli_fetch_assoc($result_count)){
              $total_messages = $c['total_messages'] ?? 0;
            }
        }

        if($total_messages > 0){
             echo ' <div class="badge badge-sm badge-error text-white font-bold p-2.5 ">' . $total_messages . '</div>';
        }

        ?>

        