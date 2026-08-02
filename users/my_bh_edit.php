<?php
include 'my_property.php';
if (isset($_GET['id']) && isset($_GET['property_id'])) {
    $rent_id     = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    echo "<script>location.href='index.php';</script>";
    exit;
}

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info 
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name        = "";
$price       = "";
$image_cover = "";
$other_info  = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
}

// BOARDING HOUSES (Aggregated by bed_number base)
$get_bh = $conn->prepare("
    SELECT boarding_id, status, num_decks, image, bed_number 
    FROM boarding_house 
    WHERE rent_id = ? 
    ORDER BY bed_number, num_decks ASC
");
$get_bh->bind_param("s", $rent_id);
$get_bh->execute();
$bh_res = $get_bh->get_result();

$boarding_houses = [];
while ($row = $bh_res->fetch_assoc()) {
    // Extract base bed name (e.g. "Bed 1 - Deck 1" -> "Bed 1")
    $full_bed_name = $row['bed_number'];
    $base_bed_name = preg_replace('/\s*-\s*Deck\s*\d+/i', '', $full_bed_name);

    if (!isset($boarding_houses[$base_bed_name])) {
        $boarding_houses[$base_bed_name] = [
            'boarding_id' => $row['boarding_id'],
            'bed_number'  => $base_bed_name,
            'image'       => $row['image'],
            'num_decks'   => (int)$row['num_decks'], // Fetch true deck count from DB
            'statuses'    => [$row['status']]
        ];
    } else {
        $boarding_houses[$base_bed_name]['statuses'][] = $row['status'];
        // Ensure num_decks reflects the max deck count or total status entries
        $boarding_houses[$base_bed_name]['num_decks'] = max(
            (int)$row['num_decks'], 
            count($boarding_houses[$base_bed_name]['statuses'])
        );
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
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-2xl">
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
        <p class="mb-3 font-bold text-lg">Room Information</p>
        
        <div class="w-full flex flex-col gap-3 mb-5">
            <span class="w-full">
                <p class="mb-2 text-sm">Room Name / Number *</p>
                <label class="input w-full flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-full" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            <span class="w-full">
                <p class="mb-2 text-sm">Price / Month *</p>
                <label class="input w-full flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-full" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
            <span class="w-full">
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

                <label class="input w-full flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <input 
                        type="file" 
                        class="file-input grow w-full" 
                        id="cover" 
                        name="cover" 
                        accept="image/jpeg, image/png, image/jpg" 
                        <?php echo !empty($image_cover) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>

        <div class="w-full flex flex-col gap-3 mb-5">
            <span class="w-full">
                <p class="mb-2 text-sm">Other Information *</p>
                <textarea class="input w-full border border-gray-300 rounded-sm min-h-30 p-3" name="other_info" placeholder="Enter Other Information" required><?php echo htmlspecialchars($other_info ?? ''); ?></textarea>
            </span>
        </div>

        <p class="mb-3 font-bold text-lg">Beds Information</p>
        
        <!-- ADD BED BUTTON -->
        <div class="mb-3">
            <button type="button" id="addBedBtn" class="btn btn-success text-white btn-sm flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M8 12h8"/>
                    <path d="M12 8v8"/>
                </svg>
                Add Bed
            </button>
        </div>

        <div id="beds-container" class="mb-5">
            <?php 
            $bedIndex = 0; 
            if (!empty($boarding_houses)): 
                foreach ($boarding_houses as $bed_name => $boarding_house): 
            ?>
                <div class="bed-item border-b border-base-200 pb-4 mb-5" data-bed-index="<?php echo $bedIndex; ?>">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm font-semibold bed-title">
                            <?php echo htmlspecialchars($bed_name); ?>
                        </p>
                        <?php if ($bedIndex > 0): ?>
                           <!-- <button type="button" class="remove-bed-btn btn btn-error btn-xs text-white">Remove Bed</button>-->
                        <?php endif; ?>
                    </div>

                    <div class="w-full flex flex-col gap-3 mb-3">
                        <span class="w-full">
                            <label class="input w-full flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                <input
                                    type="text"
                                    name="bednum[]"
                                    class="grow bed-input-name"
                                    value="<?php echo htmlspecialchars($bed_name); ?>"
                                    placeholder="Enter Bed Name"
                                    readonly>
                            </label>
                        </span>

                        <span class="w-full">
                            <p class="text-sm bed-title mb-2">Bed Image</p>
                            <input type="hidden" name="old_image[]" value="<?php echo htmlspecialchars($boarding_house['image'] ?? ''); ?>">
                            <?php if (!empty($boarding_house['image'])): ?>
                                <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <img src="../assets/uploads/<?php echo htmlspecialchars($boarding_house['image']); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                                        <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($boarding_house['image']); ?></strong></span>
                                    </div>
                                    <span class="badge badge-success text-white">Retained</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="file-input w-full" name="image[]" accept="image/jpeg,image/jpg,image/png">
                        </span>
                    </div>

                    <div class="w-full flex flex-col gap-3 mb-3">
                        <span class="w-full">
                            <p class="mb-2 text-sm">Number of Decks</p>
                            <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                                <select class="select w-full num-deck-select" name="num_deck[]" required>
                                    <option value="1" <?= (int)$boarding_house['num_decks'] === 1 ? 'selected' : '' ?>>1</option>
                                    <option value="2" <?= (int)$boarding_house['num_decks'] === 2 ? 'selected' : '' ?>>2</option>
                                    <option value="3" <?= (int)$boarding_house['num_decks'] === 3 ? 'selected' : '' ?>>3</option>
                                </select>     
                            </div>     
                        </span>
                    </div>

                    <!-- DYNAMIC STATUS PER DECK -->
                    <div class="w-full flex flex-col gap-2 deck-status-wrapper">
                        <p class="mb-1 text-sm font-semibold">Status per Deck</p>
                        <?php
                            $numDecks = (int)$boarding_house['num_decks'];
                            $savedStatuses = $boarding_house['statuses'];

                            for ($d = 0; $d < $numDecks; $d++):
                                $deckLabel = ($numDecks > 1) ? "Deck " . ($d + 1) : "Status";
                                $currentStatus = $savedStatuses[$d] ?? 'Available';
                        ?>
                            <div class="w-full flex items-center gap-2 border border-gray-300 rounded-md p-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                <label class="text-xs text-gray-500 w-20 flex-shrink-0"><?php echo $deckLabel; ?></label>
                                <select class="select w-full" name="status[<?php echo $bedIndex; ?>][]" required>
                                    <option value="Available" <?= $currentStatus == 'Available' ? 'selected' : '' ?>>Available</option>
                                    <option value="Occupied" <?= $currentStatus == 'Occupied' ? 'selected' : '' ?>>Occupied</option>
                                    <option value="Out of Order" <?= $currentStatus == 'Out of Order' ? 'selected' : '' ?>>Out of Order</option>
                                </select>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php 
                $bedIndex++; 
                endforeach; 
            endif; 
            ?>
        </div>

        <p class="mb-3 font-bold text-lg">Room Amenities</p>
        <div class="mb-3">
            <button type="button" id="addamenBtn" class="btn btn-success text-white btn-sm flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                        $currentAmenId = is_array($selectedAmen) ? ($selectedAmen['amen_id'] ?? '') : $selectedAmen;
                    ?>
                    <div class="amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 22a2 2 0 0 1-2-2"/><path d="M14 2a2 2 0 0 1 2 2"/><path d="M16 22h-2"/><path d="M2 10V8"/><path d="M2 4a2 2 0 0 1 2-2"/><path d="M20 8a2 2 0 0 1 2 2"/><path d="M22 14v2"/><path d="M22 20a2 2 0 0 1-2 2"/><path d="M4 16a2 2 0 0 1-2-2"/><path d="M8 10a2 2 0 0 1 2 2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/><path d="M8 2h2"/></svg>

                        <select class="select w-full" name="amenity[]" required>
                            <option value="" disabled <?= empty($currentAmenId) ? 'selected' : ''; ?>>-- Select Amenity --</option>
                            <?php foreach ($amenities as $id => $amenName): ?>
                                <option value="<?= htmlspecialchars($id); ?>" <?= ($currentAmenId == $id) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($amenName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if ($index > 0): ?>
                            <button type="button" class="remove-amen-btn btn btn-error btn-sm text-white px-2 py-1">✕</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="text-end mt-4">
            <button type="submit" name="edit_boarding" class="btn btn-success text-white">Update</button>
        </div>
    </form>
  </div>
</dialog>

<!-- JS FOR ADD/REMOVE BEDS AND DYNAMIC DECK STATUSES -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bedsContainer = document.getElementById('beds-container');
    const addBedBtn = document.getElementById('addBedBtn');

    function updateDeckStatusUI(bedItem, numDecks) {
        const bedIndex = bedItem.getAttribute('data-bed-index');
        const wrapper = bedItem.querySelector('.deck-status-wrapper');

        wrapper.innerHTML = '<p class="mb-1 text-sm font-semibold">Status per Deck</p>';

        for (let d = 0; d < numDecks; d++) {
            const deckLabel = (numDecks > 1) ? `Deck ${d + 1}` : 'Status';
            const statusRow = document.createElement('div');
            statusRow.className = 'w-full flex items-center gap-2 border border-gray-300 rounded-md p-1';

            statusRow.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                <label class="text-xs text-gray-500 w-20 flex-shrink-0">${deckLabel}</label>
                <select class="select w-full" name="status[${bedIndex}][]" required>
                    <option value="Available" selected>Available</option>
                    <option value="Not Available">Occupied</option>
                    <option value="Out of Order">Out of Order</option>
                </select>
            `;
            wrapper.appendChild(statusRow);
        }
    }

    // LISTENER FOR DECKS CHANGE & REMOVE BED
    if (bedsContainer) {
        bedsContainer.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('num-deck-select')) {
                const bedItem = e.target.closest('.bed-item');
                const numDecks = parseInt(e.target.value) || 1;
                updateDeckStatusUI(bedItem, numDecks);
            }
        });

        bedsContainer.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-bed-btn')) {
                e.target.closest('.bed-item').remove();
            }
        });
    }

    // ADD BED FUNCTIONALITY
    if (addBedBtn) {
        addBedBtn.addEventListener('click', function () {
            const bedItems = bedsContainer.querySelectorAll('.bed-item');
            const newIndex = bedItems.length;
            const newBedName = `Bed ${newIndex + 1}`;

            const newBedDiv = document.createElement('div');
            newBedDiv.className = 'bed-item border-b border-base-200 pb-4 mb-5';
            newBedDiv.setAttribute('data-bed-index', newIndex);

            newBedDiv.innerHTML = `
                <div class="flex justify-between items-center mb-2">
                    <p class="text-sm font-semibold bed-title">${newBedName}</p>
                    <button type="button" class="remove-bed-btn btn btn-error btn-xs text-white">Remove Bed</button>
                </div>

                <div class="w-full flex flex-col gap-3 mb-3">
                    <span class="w-full">
                        <label class="input w-full flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <input type="text" name="bednum[]" class="grow bed-input-name" value="${newBedName}" placeholder="Enter Bed Name" required>
                        </label>
                    </span>

                    <span class="w-full">
                        <p class="text-sm bed-title mb-2">Bed Image</p>
                        <input type="hidden" name="old_image[]" value="">
                        <input type="file" class="file-input w-full" name="image[]" accept="image/jpeg,image/jpg,image/png">
                    </span>
                </div>

                <div class="w-full flex flex-col gap-3 mb-3">
                    <span class="w-full">
                        <p class="mb-2 text-sm">Number of Decks</p>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                            <select class="select w-full num-deck-select" name="num_deck[]" required>
                                <option value="1" selected>1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>     
                        </div>     
                    </span>
                </div>

                <div class="w-full flex flex-col gap-2 deck-status-wrapper">
                    <p class="mb-1 text-sm font-semibold">Status per Deck</p>
                    <div class="w-full flex items-center gap-2 border border-gray-300 rounded-md p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <label class="text-xs text-gray-500 w-20 flex-shrink-0">Status</label>
                        <select class="select w-full" name="status[${newIndex}][]" required>
                            <option value="Available" selected>Available</option>
                            <option value="Not Available">Occupied</option>
                            <option value="Out of Order">Out of Order</option>
                        </select>
                    </div>
                </div>
            `;

            bedsContainer.appendChild(newBedDiv);
        });
    }
});
</script>