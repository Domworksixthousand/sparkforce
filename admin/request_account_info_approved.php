<?php
    
    include "request_accounts.php";
    $location_back = htmlspecialchars($_GET['location_back'] ?? '');
    if(isset($_GET['id'])){
        $user_id = $_GET['id'] ?? '';
    }
?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="approved_request_account" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure Approved?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = 'request_account_info.php?id=<?php echo $user_id ?>&location_back=<?php echo $location_back; ?>';
        }
    });
});
</script>