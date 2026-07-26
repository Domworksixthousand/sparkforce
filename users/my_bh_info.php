<?php
include 'my_property.php';
if(isset($_GET['id']) && isset($_GET['property_id'])){
    $rent_id = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    echo "<script>location.href='index.php';</script>";
    exit;
}


?>

<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-7xl">
    <form method="dialog">
      <button 
        type="button" 
        onclick="window.location.href='my_property.php?property_id=<?php echo $landlord_id; ?>';" 
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
        ✕
      </button>
    </form>
    <div>
        
    </div>
  </div>
</dialog>

