<?php
include 'my_property.php';

if (isset($_GET['property_id']) && isset($_GET['id'])) {
    $landlord_id = $_GET['property_id'] ?? '';
    $rent_id = $_GET['id'] ?? '';
} else {
    header("location:index.php");
    exit;
}

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info,rate
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
    $rate  = $rent_row['rate'];
}

// COMMERCIAL DETAILS
$cs_id  = "";
$type   = "";
$area   = "";
$status = "";

$get_house = $conn->prepare("SELECT * FROM `commercial_space` WHERE `rent_id` = ?");
$get_house->bind_param("s", $rent_id);
$get_house->execute();
$result_execute_house = $get_house->get_result();

if ($result_execute_house->num_rows > 0) {
    while ($row_h = mysqli_fetch_assoc($result_execute_house)) {
        $cs_id  = $row_h['cs_id'];
        $type   = $row_h['type'];
        $area   = $row_h['area'];
        $status = $row_h['status'];
    }
}

// GALLERY IMAGES
$house = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

if ($result_gallery->num_rows > 0) {
    while ($row_gallery = mysqli_fetch_assoc($result_gallery)) {
        $house[] = $row_gallery['image'];
    }
}

// 1. GET ALL SAVED AMENITIES FOR THIS RENT_ID
$get_amen = $conn->prepare("
    SELECT a.amen_id, a.amenity, ra.rent_amen_id
    FROM rentspace_amenities AS ra
    INNER JOIN amenities AS a ON a.amen_id = ra.amen_id
    WHERE ra.rent_id = ?
");
$get_amen->bind_param("s", $rent_id);
$get_amen->execute();
$amen_res = $get_amen->get_result();

$selected_amen_ids = [];
while ($row = $amen_res->fetch_assoc()) {
    $selected_amen_ids[] = $row['amen_id'];
}

// If no saved amenities exist, initialize with one empty option for UI default
if (empty($selected_amen_ids)) {
    $selected_amen_ids = [""];
}

// 2. GET ALL AVAILABLE AMENITIES FROM DATABASE (Masterlist)
$active = "yes";
if (isset($user_id_login) && !empty($user_id_login)) {
    $get_amen_all = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
    $get_amen_all->bind_param("ss", $user_id_login, $active);
} else {
    // Fallback kung walang user_id_login, kukunin pa rin ang lahat ng active amenities
    $get_amen_all = $conn->prepare("SELECT * FROM amenities WHERE active=?");
    $get_amen_all->bind_param("s", $active);
}

$get_amen_all->execute();
$result_all = $get_amen_all->get_result();

$all_amenities = [];
while ($row = $result_all->fetch_assoc()) {
    $all_amenities[] = $row;
}
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-1xl">
    <form method="dialog">
      <button 
        type="button" 
        onclick="window.location.href='my_property.php?property_id=<?php echo urlencode($landlord_id); ?>';" 
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
        ✕
      </button>
    </form>

    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">
        <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">
        <input type="hidden" name="cs_id" value="<?php echo htmlspecialchars($cs_id); ?>">

        <p class="mb-3">Commercial Space Information</p>
        <div class="w-[100%] flex flex-col gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Commercial Space Name / Number *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="cs_name" value="<?php echo htmlspecialchars($name ?? ''); ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price /Month *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-[100%]" name="cs_price" value="<?php echo htmlspecialchars($price ?? ''); ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
                 <span class="w-[100%]">
                <p class="mb-2 text-sm">Rate(Per Month/Night/Week/Hour) *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="cs_rate"  value="<?php echo $rate ?? ''; ?>" placeholder="Enter Rate" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm"> Cover Photo * </p>
                <?php if (!empty($image_cover)): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($image_cover); ?>">
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                        <div class="flex items-center gap-2">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                            <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($image_cover); ?></strong></span>
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
                        name="cs_cover" 
                        accept="image/jpeg, image/png, image/jpg" 
                        <?php echo !empty($image_cover) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>

        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Informations *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="cs_other_info" placeholder="Enter Other Informations" required><?php echo htmlspecialchars($other_info ?? ''); ?></textarea>
            </span>
        </div>

        <p class="mb-3">Units Specification</p>
        <div class="mb-5">        
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Commercial Space Type *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    <select class="select w-[100%]" name="type" required>
                        <option value="" disabled <?php echo empty($type) ? 'selected' : ''; ?>>Select Commercial Type</option>
                        <option value="Office Space" <?php echo ($type == 'Office Space') ? 'selected' : ''; ?>>Office Space</option>
                        <option value="Retail Space" <?php echo ($type == 'Retail Space') ? 'selected' : ''; ?>>Retail Space</option>
                        <option value="Industrial & Warehouse Space" <?php echo ($type == 'Industrial & Warehouse Space') ? 'selected' : ''; ?>>Industrial & Warehouse Space</option>
                        <option value="Hospitality & Food Service" <?php echo ($type == 'Hospitality & Food Service') ? 'selected' : ''; ?>>Hospitality & Food Service</option>
                        <option value="Multifamily (Commercial Classification)" <?php echo ($type == 'Multifamily (Commercial Classification)') ? 'selected' : ''; ?>>Multifamily (Commercial Classification)</option>
                        <option value="Special Purpose & Miscellaneous" <?php echo ($type == 'Special Purpose & Miscellaneous') ? 'selected' : ''; ?>>Special Purpose & Miscellaneous</option>
                    </select>
                </label>
            </div>

            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Square Area *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                    <input type="text" class="grow w-[100%]" name="square_area" value="<?php echo htmlspecialchars($area ?? ''); ?>" placeholder="Enter Unit Square Area" required />
                </label>
            </div> 
            
             <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Status *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 16v-4" />
                        <path d="M12 8h.01" />
                    </svg>
                    <select class="select w-[100%]" name="status" required>
                        <option value="" disabled <?= empty($status) ? 'selected' : '' ?>>Select Status</option>
                        <option value="Occupied" <?= ($status ?? '') === 'Occupied' ? 'selected' : '' ?>>Occupied</option>
                        <option value="Available" <?= ($status ?? '') === 'Available' ? 'selected' : '' ?>>Available</option>
                        <option value="Out of Order" <?= ($status ?? '') === 'Out of Order' ? 'selected' : '' ?>>Out of Order</option>
                    </select>
                </label>
            </div>

            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Multiple Photos (Upload 3 to 10 Photos) *</p>

                <?php if (!empty($house)): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-3">
                        <?php foreach ($house as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" class="w-full h-16 object-cover rounded border border-success/30">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs rounded-lg">
                        <?php echo count($house); ?> photo(s) retained. Upload new photos below to replace them.
                    </div>
                <?php endif; ?>

                <label class="input w-[100%] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/></svg>
                    <input 
                        type="file" 
                        class="file-input grow w-[100%]" 
                        id="gallery" 
                        name="gallery[]" 
                        accept="image/jpeg, image/png, image/jpg" 
                        multiple
                        <?php echo !empty($house) ? '' : 'required'; ?>
                    />
                </label>
                <p class="text-xs text-gray-400 mt-1">Please select 3–10 photos.</p>
            </div>               
        </div>          

        <p class="mb-3">Room Amenities</p>
        <div class="mb-3">
            <button type="button" id="addamenBtn2" class="btn btn-success text-white btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12h8"/>
                    <path d="M12 8v8"/>
                </svg>
                Add Amenities
            </button>
        </div>

        <div id="amenities-container2">
            <?php
            $index = 0;
            foreach ($selected_amen_ids as $selectedAmen) {
            ?>

            <div class="amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2">
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
                    <path d="M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/>
                    <path d="M8 2h2"/>
                </svg>

                <select class="select w-[100%]" name="cs_amenity[]" required>
                    <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>

                    <?php foreach ($all_amenities as $amen) { ?>
                        <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>"
                            <?php echo ($selectedAmen == $amen['amen_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($amen['amenity']); ?>
                        </option>
                    <?php } ?>
                </select>

                <?php if ($index > 0) { ?>
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
                <?php } ?>
            </div>

            <?php
            $index++;
            }
            ?>
        </div>

        <div class="text-end mt-4">
            <button type="submit" name="update_cs" class="btn btn-success text-white">Update</button>
        </div>
    </form>
  </div>
</dialog>