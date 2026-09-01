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
<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" value="<?php echo $report_id; ?>" name="report_id">
    <input type="hidden" name="banned_account" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure to Ban this Account?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'reports.php';
        }
    });
});
</script>