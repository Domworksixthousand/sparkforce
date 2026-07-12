<?php
    $location_back = htmlspecialchars($_GET['location_back'] ?? '');
    include "$location_back";
?>

<form action="../functions.php" method="POST" id="assignForm">
    <input type="hidden" name="sigout_admin" value="1">
</form>

<script>
// Auto-show alert on page load
window.addEventListener('DOMContentLoaded', function() {
    CoolAlert.show({
        icon: "question",
        title: "Important!",
        text: "Are you sure to Sign Out?",
        confirmButtonText: "Confirm",
        showCancelButton: true,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
   
            document.getElementById('assignForm').submit();
        } else {
  
            location.href = '<?php echo $location_back; ?>';
        }
    });
});
</script>