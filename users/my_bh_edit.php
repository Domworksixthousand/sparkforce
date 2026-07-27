<?php
include 'my_property.php';
if(isset($_GET['id']) && isset($_GET['property_id'])){
    $rent_id = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    echo "<script>location.href='index.php';</script>";
    exit;
}


//RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info 
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name = "";
$price = "";
$image_cover = "";
$other_info = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
}

// BOARDING HOUSES
$get_bh = $conn->prepare("
    SELECT boarding_id, status, num_decks, image, bed_number 
    FROM boarding_house 
    WHERE rent_id = ? ORDER BY bed_number
");
$get_bh->bind_param("s", $rent_id);
$get_bh->execute();
$bh_res = $get_bh->get_result();

$boarding_houses = [];
while ($row = $bh_res->fetch_assoc()) {
    $boarding_houses[$row['boarding_id']] = $row;
}

//  AMENITIES
// AMENITIES FROM DATABASE
$get_amen = $conn->prepare("
    SELECT a.amen_id, a.amenity, ra.rent_amen_id
    FROM rentspace_amenities AS ra
    INNER JOIN amenities AS a ON a.amen_id = ra.amen_id
    WHERE ra.rent_id = ?
");
$get_amen->bind_param("s", $rent_id);
$get_amen->execute();
$amen_res = $get_amen->get_result();

$saved_amenities = [];
while ($row = $amen_res->fetch_assoc()) {
    $saved_amenities[] = [
        "amen_id"      => $row['amen_id'],
        "amenity"      => $row['amenity'],
        "rent_amen_id" => $row['rent_amen_id']
    ];
}

?>

<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-1xl">
    <form method="dialog">
      <button 
        type="button" 
        onclick="window.location.href='my_property.php?property_id=<?php echo $landlord_id; ?>';" 
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
        ✕
      </button>
    </form>
    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="landlord_id" value="<?php echo $landlord_id; ?>">
        <input type="hidden" name="rent_id" value="<?php echo $rent_id; ?>">
        <p class="mb-3">Room Information </p>
        <div class="w-[100%] flex flex-col  gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Room Name / Number *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="name" value="<?php echo $name ?? ''; ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price /Month *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-[100%]" name="price"  value="<?php echo $price ?? ''; ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm"> Cover Photo * </p>
                <?php if (!empty($image_cover)): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($image_cover); ?>">
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                        <div class="flex items-center gap-2">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                            <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($image_cover ?? 'Cover Image'); ?></strong></span>
                        </div>
                        <span class="badge badge-success text-white">Retained</span>
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
                        accept="image/jpeg, image/png, image/jpg" 
                    
                        <?php echo !empty($image_cover) ? '' : 'required'; ?> 
                    />
                                       
                </label>
            </span>
        </div>
        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Informations *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="other_info" placeholder="Enter Other Informations" required><?php echo $other_info ?? ''; ?></textarea>
            </span>
        </div>
        <p class="mb-3">Beds Information</p>
        <div class="mb-5">
        <div class="mb-3">
            <button type="button" id="addBedBtn1" class="btn btn-success text-white btn-sm">
                Add Bed
            </button>
        </div>

       <?php if (!empty($boarding_houses)): ?>
            <?php foreach ($boarding_houses as $boarding_house): ?>

                <div class="bed-item border-b border-base-200 pb-4 mb-5">
                
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm bed-title">
                             <?php echo $boarding_house['bed_number'] ?? 'no data'; ?>
                        </p>
                        <!--<button type="button" class="btn btn-error btn-xs text-white remove-bed-btn">
                            Remove
                        </button>-->
                    </div>
                    <input type="hidden" value="<?php echo $boarding_house['boarding_id'] ?? '' ?>" name="boarding_id">
                    <div class="w-full flex flex-col gap-3 mb-3">
                        <span class="w-full">
                            <label class="input w-full flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                <input
                                    type="text"
                                    name="bednum[]"
                                    class="grow"
                                    value="<?php echo $boarding_house['bed_number']; ?>"
                                    readonly>
                            </label>
                        </span>

                        <span class="w-full">
                            <p class="text-sm bed-title mb-2">Bed Image</p>
                            
                        
                            <input type="hidden" name="old_image[]" value="<?php echo htmlspecialchars($boarding_house['image'] ?? ''); ?>">
                            <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                                <div class="flex items-center gap-2">
                                    <img src="../assets/uploads/<?php echo htmlspecialchars($boarding_house['image'] ?? ''); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                                    <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($boarding_house['image'] ?? ''); ?></strong></span>
                                </div>
                                <span class="badge badge-success text-white">Retained</span>
                            </div>
                    

                            <input
                                type="file"
                                class="file-input w-full"
                                name="image[]"
                                accept="image/jpeg,image/jpg">
                               
                        </span>
                    </div>

                    <div class="w-full flex flex-col lg:flex-row gap-3 mb-3">
                        <span class="w-full">
                            <p class="mb-2 text-sm">Number of Deck</p>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                                <select class="select w-[100%]" name="num_deck[]" required>
                                    <option value="" disabled <?php echo empty($boarding_house['num_decks']) ? 'selected' : ''; ?>>Select Number of Deck</option>
                                    <option value="1" <?= $boarding_house['num_decks'] == 1 ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= $boarding_house['num_decks'] == 2 ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= $boarding_house['num_decks'] == 3 ? 'selected' : '' ?>>3</option>
                                </select>     
                            </div>     
                        </span>
                    </div>

                    <div class="w-full flex flex-col lg:flex-row gap-3">
                        <span class="w-full">
                            <p class="mb-2 text-sm">Status</p>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                               <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <select class="select w-[100%]" name="status[]" required>
                                    <option value=" <?php echo $boarding_house['status'] ? 'selected' : ''; ?>"><?php echo $boarding_house['status'] ?? 'Select Number of Deck' ?></option>
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                    <option value="Out of Order">Out of Order</option>
                                </select>     
                            </div>     
                        </span>
                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>
            <p>No beds found.</p>
        <?php endif; ?>
            </div>
         <p class="mb-3">Room Amenities</p>
         <div class="mb-3">
            <button type="button" id="addamenBtn" class="btn btn-success text-white btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12h8"/>
                    <path d="M12 8v8"/>
                </svg>
                Add Amenities
            </button>
        </div>
                <?php 

                $active = "yes";
                $get_amen = $conn->prepare("SELECT DISTINCT amen_id, amenity FROM amenities WHERE user_id=? AND active=?");
                $get_amen->bind_param("ss", $user_id_login, $active);
                $get_amen->execute();
                $result = $get_amen->get_result();

                $amenities = [];
                while ($row = $result->fetch_assoc()) {
                    if (!empty($row['amen_id'])) {
                        $amenities[$row['amen_id']] = $row['amenity'];
                    }
                }


             $selectedAmenities = !empty($saved_amenities) ? $saved_amenities : [""];
                ?> 



           <div id="amenities-container">
            <?php if (!empty($amenities)): ?>
                <?php foreach ($selectedAmenities as $index => $selectedAmen): ?>
                    <?php 
                      
                        $rentAmenId = is_array($selectedAmen) ? ($selectedAmen['rent_amen_id'] ?? '') : '';
                        $currentAmenId = is_array($selectedAmen) ? ($selectedAmen['amen_id'] ?? '') : $selectedAmen;
                    ?>

       
                    <input type="hidden" name="rentspace_amenities_id[]" value="<?= htmlspecialchars($currentAmenId); ?>">

                    <div class="amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2">
                        <!-- Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 22a2 2 0 0 1-2-2"/>
                            <path d="M14 2a2 2 0 0 1 2 2"/>
                            <path d="M16 22h-2"/>
                            <path d="M2 10V8"/>
                            <path d="M2 4a2 2 0 0 1 2-2"/>
                            <path d="M20 8a2 2 0 0 1 2 2"/>
                            <path d="M22 14v2"/>
                            <path d="M22 20a2 2 0 0 1-2 2"/>
                            <path d="M4 16a2 2 0 0 1-2-2"/>
                            <path d="M8 10a2 2 0 0 1 2 2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/>
                            <path d="M8 2h2"/>
                        </svg>

                        <!-- Select Dropdown -->
                        <select class="select w-[100%]" name="amenity[]" required>
                            <option value="" disabled <?= empty($currentAmenId) ? 'selected' : ''; ?>>-- Select Amenity --</option>
                            
                            <?php foreach ($amenities as $id => $amen): ?>
                                <?php 
                                    // Kung $amenities ay nested array:
                                    $amenName = is_array($amen) ? $amen['amenity'] : $amen;
                                    $isSelected = ($currentAmenId == $id) ? 'selected' : '';
                                ?>
                                <option value="<?= htmlspecialchars($id); ?>" <?= $isSelected; ?>>
                                    <?= htmlspecialchars($amenName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if ($index > 0): ?>
                            <button type="button"
                                    class="remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1"
                                    title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"/>
                                    <path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-gray-500 italic p-2">No active amenities found in database.</p>
            <?php endif; ?>
            </div>
        <div class="text-end mt-4">
            <button type="submit" name="edit_boarding" class="btn btn-success text-white">Update</button>
        </div>
    </form>
  </div>
</dialog>

