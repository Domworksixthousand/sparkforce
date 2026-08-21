<?php
include 'my_property.php';

if (isset($_GET['property_id'])) {
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    header("Location: index.php");
    exit;
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

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error text-white mb-4 text-sm">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">
        <p class="mb-3 font-bold">Event Space Information</p>
        
        <div class="w-[100%] flex flex-col gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Event Space Name / Number *</p>
                <label class="input w-[100%] flex items-center gap-2">
                    <input type="text" class="autoInput grow w-[100%]" name="es_name" value="<?php echo htmlspecialchars($_SESSION['es_name'] ?? ''); ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price / Hour *</p>
                <label class="input w-[100%] flex items-center gap-2">
                    <input type="text" class="numbers_only grow w-[100%]" name="es_price" value="<?php echo htmlspecialchars($_SESSION['es_price'] ?? ''); ?>" placeholder="Enter Price / Hour" required />
                </label>
            </span>
             <span class="w-[100%]">
                <p class="mb-2 text-sm">Rate(Per Month/Night/Week/Hour) *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="es_rate"  value="<?php echo $_SESSION['es_rate'] ?? ''; ?>" placeholder="Enter Rate" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Cover Photo *</p>
                <?php if (!empty($_SESSION['es_cover'])): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($_SESSION['es_cover']); ?>">
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                        <div class="flex items-center gap-2">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($_SESSION['es_cover']); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                            <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($_SESSION['es_cover']); ?></strong></span>
                        </div>
                        <span class="badge badge-success text-white">Retained</span>
                    </div>
                <?php endif; ?>

                <label class="input w-[100%] flex items-center gap-2">
                    <input 
                        type="file" 
                        class="file-input grow w-[100%]" 
                        id="cover" 
                        name="es_cover" 
                        accept="image/jpeg, image/png, image/jpg" 
                        <?php echo !empty($_SESSION['es_cover']) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>

        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Information *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="es_other_info" placeholder="Enter Other Information" required><?php echo htmlspecialchars($_SESSION['es_other_info'] ?? ''); ?></textarea>
            </span>
        </div>

        <p class="mb-3 font-bold">Units Specification</p>
        <div class="mb-5">        
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Event Space Type *</p>
                <label class="input w-[100%] flex items-center gap-2">
                    <select class="select w-[100%]" name="type" required>
                        <option value="" disabled <?php echo empty($_SESSION['type']) ? 'selected' : ''; ?>>Select Event space Type</option>
                        <option value="Ballrooms & Function Halls" <?php echo (($_SESSION['type'] ?? '') == 'Ballrooms & Function Halls') ? 'selected' : ''; ?>>Ballrooms & Function Halls</option>
                        <option value="Gardens & Outdoor Pavilions" <?php echo (($_SESSION['type'] ?? '') == 'Gardens & Outdoor Pavilions') ? 'selected' : ''; ?>>Gardens & Outdoor Pavilions</option>
                        <option value="Rooftop Decks & Sky Lounges" <?php echo (($_SESSION['type'] ?? '') == 'Rooftop Decks & Sky Lounges') ? 'selected' : ''; ?>>Rooftop Decks & Sky Lounges</option>
                        <option value="Industrial & Warehouse Venues (Raw Spaces)" <?php echo (($_SESSION['type'] ?? '') == 'Industrial & Warehouse Venues (Raw Spaces)') ? 'selected' : ''; ?>>Industrial & Warehouse Venues (Raw Spaces)</option>
                        <option value="Glasshouses & Solariums" <?php echo (($_SESSION['type'] ?? '') == 'Glasshouses & Solariums') ? 'selected' : ''; ?>>Glasshouses & Solariums</option>
                        <option value="Art Galleries & Creative Studios" <?php echo (($_SESSION['type'] ?? '') == 'Art Galleries & Creative Studios') ? 'selected' : ''; ?>>Art Galleries & Creative Studios</option>
                        <option value="Auditoriums & Amphitheaters" <?php echo (($_SESSION['type'] ?? '') == 'Auditoriums & Amphitheaters') ? 'selected' : ''; ?>>Auditoriums & Amphitheaters</option>
                        <option value="Private Dining Rooms & Restaurant Venues" <?php echo (($_SESSION['type'] ?? '') == 'Private Dining Rooms & Restaurant Venues') ? 'selected' : ''; ?>>Private Dining Rooms & Restaurant Venues</option>
                    </select>
                </label>
            </div>

            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Square Area *</p>
                <label class="input w-[100%] flex items-center gap-2">
                    <input type="text" class="grow w-[100%]" name="square_area" value="<?php echo htmlspecialchars($_SESSION['square_area'] ?? ''); ?>" placeholder="Enter Unit Square Area" required />
                </label>
            </div>     

            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Multiple Photos (Upload 3 to 10 Photos) *</p>

                <?php if (!empty($_SESSION['gallery'])): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-3">
                        <?php foreach ($_SESSION['gallery'] as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" class="w-full h-16 object-cover rounded border border-success/30">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs rounded-lg">
                        <?php echo count($_SESSION['gallery']); ?> photo(s) retained. Upload new photos below to replace them.
                    </div>
                <?php endif; ?>

                <label class="input w-[100%] flex items-center gap-2">
                    <input 
                        type="file" 
                        class="file-input grow w-[100%]" 
                        id="gallery" 
                        name="gallery[]" 
                        accept="image/jpeg, image/png, image/jpg" 
                        multiple
                        <?php echo !empty($_SESSION['gallery']) ? '' : 'required'; ?>
                    />
                </label>
                <p class="text-xs text-gray-400 mt-1">Please select 3–10 photos.</p>
            </div>              
        </div>          

        <p class="mb-3 font-bold">Room Amenities</p>
        <div class="mb-3">
            <button type="button" id="addamenBtn3" class="btn btn-success text-white btn-sm flex items-center gap-1">
                Add Amenities
            </button>
        </div>

        <div id="amenities-container3">
            <?php
            $active = "yes";
            $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
            $get_amen->bind_param("ss", $user_id_login, $active);
            $get_amen->execute();
            $result = $get_amen->get_result();

            $amenities = [];
            while($row = $result->fetch_assoc()){
                $amenities[] = $row;
            }

            if(empty($_SESSION['amenities'])){
                $_SESSION['amenities'] = [""];
            }

            $index = 0;
            foreach($_SESSION['amenities'] as $selectedAmen){
            ?>
            <div class="amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2">
                <!-- FIXED FIELD NAME BELOW: es_amenity[] instead of apartment_amenity[] -->
                <select class="select w-[100%]" name="es_amenity[]" required>
                    <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>
                    <?php foreach($amenities as $amen){ ?>
                        <option value="<?= $amen['amen_id']; ?>" <?= ($selectedAmen == $amen['amen_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($amen['amenity']); ?>
                        </option>
                    <?php } ?>
                </select>

                <?php if($index > 0){ ?>
                    <button type="button" class="remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1" title="Remove">✕</button>
                <?php } ?>
            </div>
            <?php
            $index++;
            }
            ?>
        </div>

        <div class="text-end mt-4">
            <button type="submit" name="save_es" class="btn btn-success text-white">Save</button>
        </div>
    </form>
  </div>
</dialog>

