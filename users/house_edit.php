<?php
include 'my_property.php';
if(isset($_GET['property_id'])){
    $landlord_id = $_GET['property_id'] ?? '';
    $rent_id = $_GET['id']; 
}else{
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

// HOUSE DETAILS
$get_house = $conn->prepare("SELECT * FROM `house` WHERE `rent_id` = ?");
$get_house->bind_param("s", $rent_id);
$get_house->execute();
$result_execute_house = $get_house->get_result();

$house_id  = "";
$area      = "";
$type      = "";
$bedroom   = "";
$bathrooms = "";
$flooring  = "";
$parking   = "";

if($result_execute_house->num_rows > 0){
    while($row_h = mysqli_fetch_assoc($result_execute_house)){
        $house_id  = $row_h['house_id'];
        $area      = $row_h['area'];
        $type      = $row_h['type'];
        $bedroom   = $row_h['bedroom'];
        $bathrooms = $row_h['bathrooms'];
        $flooring  = $row_h['flooring'];
        $parking   = $row_h['parking'];
        $status   = $row_h['status'];
    }
}

// GALLERY IMAGES
$house = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

if($result_gallery->num_rows > 0){
    while($row_gallery = mysqli_fetch_assoc($result_gallery)){
        $house[] = $row_gallery['image'];
    }
}

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

// Fallback for dropdowns if no amenities exist yet
if (empty($saved_amenities)) {
    $selected_amen_ids = [""];
} else {
    $selected_amen_ids = array_column($saved_amenities, 'amen_id');
}
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-1xl">
    <form method="dialog">
      <button 
        type="button" 
        onclick="window.location.href='my_property.php?property_id=<?php echo $landlord_id; ?>';" 
        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
        ✕
      </button>
    </form>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error text-white mb-4 text-sm">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">
        <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">
        <p class="mb-3">House Information</p>
        
        <div class="w-[100%] flex flex-col gap-3 mb-5">
            <!-- House Name -->
            <span class="w-[100%]">
                <p class="mb-2 text-sm">House Name / Number *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="house_name" value="<?php echo htmlspecialchars($name ?? ''); ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>

            <!-- Price -->
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price /Month *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-[100%]" name="house_price" value="<?php echo htmlspecialchars($price ?? ''); ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
             <span class="w-[100%]">
                <p class="mb-2 text-sm">Rate(Per Month/Night/Week/Hour) *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="house_rate"  value="<?php echo $rate ?? ''; ?>" placeholder="Enter Rate" required />
                </label>
            </span>

            <!-- Cover Photo -->
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Cover Photo *</p>
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
                        name="apartment_cover" 
                        accept="image/jpeg, image/png, image/jpg" 
                        <?php echo !empty($image_cover) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>

        <!-- Other Info -->
        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Information *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="apartment_other_info" placeholder="Enter Other Information" required><?php echo htmlspecialchars($other_info ?? ''); ?></textarea>
            </span>
        </div>

        <p class="mb-3">Units Specification</p>
        <div class="mb-5">        
            <!-- House Type -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">House Type *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    <select class="select w-[100%]" name="type" required>
                        <option value="" disabled <?php echo empty($type) ? 'selected' : ''; ?>>Select House Type</option>
                        <option value="Single-Family" <?php echo ($type == 'Single-Family') ? 'selected' : ''; ?>>Single-Family</option>
                        <option value="Townhouse" <?php echo ($type == 'Townhouse') ? 'selected' : ''; ?>>Townhouse</option>
                        <option value="Duplex" <?php echo ($type == 'Duplex') ? 'selected' : ''; ?>>Duplex</option>
                    </select>
                </label>
            </div>

            <!-- Square Area -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Square Area *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                    <input type="text" class="grow w-[100%]" name="square_area" value="<?php echo htmlspecialchars($area ?? ''); ?>" placeholder="Enter Unit Square Area" required />
                </label>
            </div> 

            <!-- Bedrooms -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Bedrooms *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    <select class="select w-[100%]" name="bedroom" required>
                        <option value="" disabled <?php echo empty($bedroom) ? 'selected' : ''; ?>>Select Bedroom</option>
                        <option value="Studio" <?php echo ($bedroom == 'Studio') ? 'selected' : ''; ?>>Studio</option>
                        <option value="1 Bed Room" <?php echo ($bedroom == '1 Bed Room') ? 'selected' : ''; ?>>1 Bed Room</option>
                        <option value="2 Bed Room" <?php echo ($bedroom == '2 Bed Room') ? 'selected' : ''; ?>>2 Bed Room</option>
                        <option value="3 Bed Room" <?php echo ($bedroom == '3 Bed Room') ? 'selected' : ''; ?>>3 Bed Room</option>
                    </select>
                </label>
            </div>

            <!-- Bathrooms -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Bathrooms *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4 4 2.5 2.5"/><path d="M13.5 6.5a4.95 4.95 0 0 0-7 7"/><path d="M15 5 5 15"/><path d="M14 17v.01"/><path d="M10 16v.01"/><path d="M13 13v.01"/><path d="M16 10v.01"/><path d="M11 20v.01"/><path d="M17 14v.01"/><path d="M20 11v.01"/></svg>
                    <select class="select w-[100%]" name="bathrooms" required>
                        <option value="" disabled <?php echo empty($bathrooms) ? 'selected' : ''; ?>>Select Bathroom</option>
                        <option value="1 Bathroom" <?php echo ($bathrooms == '1 Bathroom') ? 'selected' : ''; ?>>1 Bathroom</option>
                        <option value="2 Bathroom" <?php echo ($bathrooms == '2 Bathroom') ? 'selected' : ''; ?>>2 Bathroom</option>
                        <option value="3 Bathroom" <?php echo ($bathrooms == '3 Bathroom') ? 'selected' : ''; ?>>3 Bathroom</option>
                        <option value="4+ Bathroom" <?php echo ($bathrooms == '4+ Bathroom') ? 'selected' : ''; ?>>4+ Bathroom</option>
                    </select>
                </label>
            </div>

            <!-- Flooring -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Flooring *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M12 9v6"/><path d="M16 15v6"/><path d="M16 3v6"/><path d="M3 15h18"/><path d="M3 9h18"/><path d="M8 15v6"/><path d="M8 3v6"/></svg>
                    <select class="select w-[100%]" name="flooring" required>
                        <option value="" disabled <?php echo empty($flooring) ? 'selected' : ''; ?>>Select Flooring</option>
                        <option value="Tiles" <?php echo ($flooring == 'Tiles') ? 'selected' : ''; ?>>Tiles</option>
                        <option value="Vinyl" <?php echo ($flooring == 'Vinyl') ? 'selected' : ''; ?>>Vinyl</option>
                        <option value="Wood" <?php echo ($flooring == 'Wood') ? 'selected' : ''; ?>>Wood</option>
                    </select>
                </label>
            </div>

            <!-- Parking -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Parking / Outdoor *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9 17V7h4a3 3 0 0 1 0 6H9"/></svg>
                    <select class="select w-[100%]" name="parking" required>
                        <option value="" disabled <?php echo empty($parking) ? 'selected' : ''; ?>>Select Parking / Outdoor</option>
                        <option value="Garage" <?php echo ($parking == 'Garage') ? 'selected' : ''; ?>>Garage</option>
                        <option value="Private Yard" <?php echo ($parking == 'Private Yard') ? 'selected' : ''; ?>>Private Yard</option>
                        <option value="Balcony" <?php echo ($parking == 'Balcony') ? 'selected' : ''; ?>>Balcony</option>
                    </select>
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

            <!-- Gallery Images -->
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Multiple Photos (Upload 3 to 10 Photos) *</p>

                <?php if (!empty($house)): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-3">
                        <?php foreach ($house as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" 
                                    class="w-full h-16 object-cover rounded border border-success/30">
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

        <!-- Room Amenities -->
        <p class="mb-3">Room Amenities</p>
        <div class="mb-3">
            <button type="button" id="addamenBtn1" class="btn btn-success text-white btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12h8"/>
                    <path d="M12 8v8"/>
                </svg>
                Add Amenities
            </button>
        </div>

        <div id="amenities-container1">
            <?php
            $active = "yes";

            // Get available master amenities from DB
            $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
            $get_amen->bind_param("ss", $user_id_login, $active);
            $get_amen->execute();
            $result = $get_amen->get_result();

            $amenities = [];
            while($row = $result->fetch_assoc()){
                $amenities[] = $row;
            }

            $index = 0;
            foreach($selected_amen_ids as $selectedAmen){
            ?>
            <div class="amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

                <select class="select w-[100%]" name="apartment_amenity[]" required>
                    <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>
                    <?php foreach($amenities as $amen){ ?>
                        <option value="<?= $amen['amen_id']; ?>" <?= ($selectedAmen == $amen['amen_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($amen['amenity']); ?>
                        </option>
                    <?php } ?>
                </select>

                <?php if($index > 0){ ?>
                    <button type="button" class="remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <button type="submit" name="edit_house" class="btn btn-success text-white">Update</button>
        </div>
    </form>
  </div>
</dialog>