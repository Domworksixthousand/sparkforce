<?php
include 'my_property.php';
if(isset($_GET['property_id'])){
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    header("location:index.php");
    exit;
}
?>

<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-5xl">
    <form method="dialog">
      <button 
        type="button" 
        onclick="window.location.href='my_property.php?property_id=<?php echo $landlord_id; ?>';" 
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
        ✕
      </button>
    </form>
    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <p class="mb-3">Room Information</p>
        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Room Name / Number *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="name" value="<?php echo $_SESSION['name'] ?? ''; ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price /Month *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-[100%]" name="firstname"  value="<?php echo $_SESSION['firstname'] ?? ''; ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm"> Cover Photo *</p>
                 <?php if (isset($_SESSION['id_photo_name'])): ?>
                    <input type="hidden" name="old_id_photo" value="<?php echo htmlspecialchars($_SESSION['cover']); ?>">
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                        <span> old upload: <strong class="underline"><?php echo htmlspecialchars($_SESSION['cover']); ?></strong></span>
                        <span class="badge badge-success text-white">Saved</span>
                    </div>
                <?php endif; ?>
                <label class="input w-[100%] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    
                    <input 
                        type="file" 
                        class="file-input grow w-[100%]" 
                        id="cover" 
                        name="cover" 
                        accept="image/jpeg, image/jpg" 
                        <?php echo isset($_SESSION['cover']) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>
        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Informations *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="other_info" placeholder="Enter Other Informations"><?php echo $_SESSION['other_info'] ?? ''; ?></textarea>
            </span>
        </div>
        <p class="mb-3">Beds Information</p>
        <div class="mb-5">
            <div class="mb-3">
                <button type="button" id="addBedBtn" class="btn btn-success text-white btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-plus"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                    Add Bed
                </button>
            </div>

            <div id="beds">
                <div class="bed-item border-b border-base-200 pb-4 mb-5">
                    <div class="w-full flex flex-col lg:flex-row gap-3 mb-5">
                        <span class="w-full">
                            <p class="mb-2 text-sm bed-title">Bed 1</p>
                            <label class="input w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                <input type="text" class="autoInput grow w-full " name="bednum[]" value="Bed 1" placeholder="Enter Bed Number" readonly />
                            </label>
                        </span>
                        
                        <span class="w-full">
                            <p class="mb-2 text-sm">Image *</p>
                            <?php if (isset($_SESSION['id_photo_name'])): ?>
                                <input type="hidden" name="old_id_photo[]" value="<?php echo htmlspecialchars($_SESSION['image']); ?>">
                                <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                                    <span> old upload: <strong class="underline"><?php echo htmlspecialchars($_SESSION['image']); ?></strong></span>
                                    <span class="badge badge-success text-white">Saved</span>
                                </div>
                            <?php endif; ?>
                            <label class="input w-full flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <input 
                                    type="file" 
                                    class="file-input grow w-full" 
                                    name="image[]" 
                                    accept="image/jpeg, image/jpg" 
                                    <?php echo isset($_SESSION['image']) ? '' : 'required'; ?> 
                                />
                            </label>
                        </span>
                    </div>

                    <div class="w-full flex flex-col lg:flex-row gap-3">
                        <span class="w-full">
                            <p class="mb-2 text-sm">Number of Deck</p>
                            <label class="input w-[100%] lg:w-[50%] ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                                <input type="text" class="autoInput grow w-full " name="num_deck[]" value="<?php echo $_SESSION['num_deck'] ?? ''; ?>" placeholder="Enter Deck Num" required />
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
         <p class="mb-3">Room Amenities</p>
        <div class="mb-5  w-[100%]">
            <select class="select" name="amenity[]" class="w-[100%]" required>
                <option value="<?php echo $_SESSION['amenity'] ?? 'Select Amenity' ?>"><?php echo $_SESSION['amenity'] ?? 'Select Amenity' ?></option>
                <?php
                    $active = "yes";
                    $get_amen = $conn->prepare("SELECT * FROM `amenities` WHERE `user_id` = ? AND `active` = ?");
                    $get_amen->bind_param("ss", $user_id_login,$active);
                    $get_amen->execute();
                    $result_amen = $get_amen->get_result();
                    if($result_amen->num_rows>0){
                        while($row_get = mysqli_fetch_assoc($result_amen)){
                            $amenity = $row_get['amenity'];
                            $amen_id = $row_get['amen_id'];
                            echo "<option value='$amen_id'>$amenity</option>";
                        }
                    }
                ?>
            </select>                  
        </div>
    </form>
  </div>
</dialog>





<!-- Vanilla JavaScript (Add & Remove dynamic beds) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bedsContainer = document.getElementById('beds');
    const addBedBtn = document.getElementById('addBedBtn');

    // Keep track of total beds
    let bedCount = bedsContainer.getElementsByClassName('bed-item').length;

    addBedBtn.addEventListener('click', function () {
        bedCount++;

        // Template for new bed item
        const newBedHTML = `
            <div class="bed-item border-b border-base-200 pb-4 mb-5 relative">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-sm  bed-title">Bed ${bedCount}</p>
                    <button type="button" class="btn btn-error btn-xs text-white remove-bed-btn">
                        Remove
                    </button>
                </div>

           
                <div class="w-full flex flex-col lg:flex-row items-end gap-3 mb-5">
                    <!-- Bed Input (No <p> label) -->
                    <span class="w-full">
                        <label class="input w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <input type="text" class="autoInput grow w-full" name="bednum[]" value="Bed ${bedCount}" placeholder="Enter Bed Number" readonly />
                        </label>
                    </span>

                    <!-- Image Input -->
                    <span class="w-full">
                        <p class="mb-2 text-sm">Image *</p>
                        <label class="input w-full flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-full" name="image[]" accept="image/jpeg, image/jpg" required />
                        </label>
                    </span>
                </div>

                <div class="w-full flex flex-col lg:flex-row gap-3">
                    <span class="w-full">
                        <p class="mb-2 text-sm">Number of Deck</p>
                        <label class="input  w-[100%] lg:w-[50%]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                            <input type="text" class="autoInput grow w-full" name="num_deck[]" placeholder="Enter Deck Num" required />
                        </label>
                    </span>
                </div>
            </div>
        `;

        // Append the new bed block
        bedsContainer.insertAdjacentHTML('beforeend', newBedHTML);
    });

    // Handle Removing dynamic beds & re-indexing
    bedsContainer.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-bed-btn')) {
            e.target.closest('.bed-item').remove();
            reindexBeds();
        }
    });

    // Recalculate Bed Numbers after a bed is removed
    function reindexBeds() {
        const bedItems = bedsContainer.querySelectorAll('.bed-item');
        bedCount = bedItems.length;

        bedItems.forEach((item, index) => {
            const currentBedNum = index + 1;
            
            // Update title text
            const title = item.querySelector('.bed-title');
            if (title) title.textContent = `Bed ${currentBedNum}`;

            // Update hidden/readonly bednum value
            const bedInput = item.querySelector('input[name="bednum[]"]');
            if (bedInput) bedInput.value = `Bed ${currentBedNum}`;
        });
    }
});
</script>