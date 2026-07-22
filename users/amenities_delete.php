<?php
    
    include "amenities.php";

    if(isset($_GET['id'])){
        $amen_id = $_GET['id'] ?? '';
    }
?>
<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($amen_id); ?>">
    <input type="hidden" name="delete_amenities" value="1">
</form>
<script>
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure you want to delete?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        allowOutsideClick: false 
    }).then((result) => {
        if (result.isConfirmed) {
        
            document.getElementById('assignForm').submit(); 
        } else {

            window.location.href = 'amenities.php';
        }
    });
});
</script>