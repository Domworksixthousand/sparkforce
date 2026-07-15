<?php
include 'notifications.php';

$id = $_GET['id'] ?? '';



?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <input type="hidden" name="delete_notifictaions" value="1">
 
</form>

<script>
window.addEventListener('DOMContentLoaded', function() {
   
    
    CoolAlert.show({
        icon: "warning",
        title: "",
        text: "Are you sure to Delete this Notifications? ",
        confirmButtonText: "Yes",
        showCancelButton: true,
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('assignForm').submit();
        } else {
            location.href = 'notifications.php';
        }
    });
});
</script>