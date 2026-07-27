<?php
    
    include "my_property.php";

    if(isset($_GET['id']) && isset($_GET['property_id'])){
        $rent_id = $_GET['id'] ?? '';
        $landlord_id = $_GET['property_id'] ?? '';
    }else{
        echo "<script>location.href='index.php';</script>";
        exit;
    }

?>
<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="landlord_id" value="<?php echo $landlord_id;  ?>">
    <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">
    <input type="hidden" name="delete_room" value="1">
</form>
<script>
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure you want to delete, All the data will be Deleted?",
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