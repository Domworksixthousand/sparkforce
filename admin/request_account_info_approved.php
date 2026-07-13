<?php
    include "request_accounts.php";
    include '../loading_animation.php';
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
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure Approved?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel",
        allowOutsideClick: FileSystemWritableFileStream
    }).then((result) => {
        if (result.isConfirmed) {
            

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