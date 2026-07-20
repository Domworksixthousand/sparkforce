<?php
    
    include "pending_properties.php";
    $location_back = htmlspecialchars($_GET['location_back'] ?? '');
    if(isset($_GET['id'])){
        $landlord_id = $_GET['id'] ?? '';
    }
?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="id" value="<?php echo $landlord_id; ?>">
    <input type="hidden" name="disapproved_pending_property" value="1">
</form>

<script>
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure Disapproved?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        allowOutsideClick: FileSystemWritableFileStream
    }).then((result) => {
 
            CoolAlert.show({
                title: 'Please Wait...',
                text: 'Sending  email to user.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                showCancelButton: false,
                didOpen: () => {
                
                    if (typeof CoolAlert.showLoading === 'function') {
                        CoolAlert.showLoading();
                    }
                }
            });

          
            document.getElementById('assignForm').submit();
     
        } else {
       
            location.href = 'request_account_info.php?id=<?php echo $user_id ?>&location_back=<?php echo $location_back; ?>';
        }
    });
});
</script>

