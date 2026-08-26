<?php
include "reports.php";
 if(isset($_GET['report_id'])){
    $report_id = $_GET['report_id'];
 }
$get_details = $conn->prepare("SELECT * FROM `report` WHERE `report_id` = ?");
$get_details->bind_param("s", $report_id);
$get_details->execute();
$result_details = $get_details->get_result();

if ($result_details && $result_details->num_rows > 0) {
    $row_details       = $result_details->fetch_assoc();
    $report_type       = $row_details['report_type'];
    $user_id_reporter  = $row_details['user_id_reporter'];
    $user_id_reported  = $row_details['user_id_reported'];
    $reason            = $row_details['reason'];
    $post_id           = $row_details['post_id'];
    $status            = $row_details['status'];
    $date_reported     = $row_details['date_reported'];
}


?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box max-w-1xl">
    <form method="dialog" class="mb-6">
      <button type="button" onclick="location.href='reports.php'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <div>
     <form action="../functions.php" method="POST" >
        <input type="hidden" name="report_id" value="<?php echo $report_id; ?>">
        <p class="font-bold mb-2">Message Warning</p>
        <textarea class="textarea w-[100%] p-[20px] h-[10rem] mb-3" name="message" placeholder="Enter Message"></textarea>
        <div class="flex justify-end ">
            <input type="submit" name="send_warning" class="btn btn-warning" value="Send Warning">
        </div>
     </form>
    </div>
  </div>
</dialog>

