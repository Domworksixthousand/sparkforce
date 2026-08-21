<?php
include 'my_property.php';
if(isset($_GET['property_id'])){
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    header("location:index.php");
    exit;
}


?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-1xl">
    <form method="dialog">

      <a href="my_property.php?property_id=<?php echo $landlord_id; ?>"  class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"> ✕</a>
    </form>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error text-white mb-4 text-sm">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="../functions.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="landlord_id" value="<?php echo $landlord_id; ?>">
        <p class="mb-3">Apartment Information </p>
        <div class="w-[100%] flex flex-col  gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Apartment Name / Number *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/><path d="M6 13h12"/><path d="M6 17h12"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="apartment_name" value="<?php echo htmlspecialchars($_SESSION['apartment_name'] ?? ''); ?>" placeholder="Enter Name / Number" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Price /Month *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="numbers_only grow w-[100%]" name="apartment_price"  value="<?php echo htmlspecialchars($_SESSION['apartment_price'] ?? ''); ?>" placeholder="Enter Price /Month" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Rate(Per Month/Night/Week/Hour) *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-5 text-gray-500"><path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/></svg>
                    <input type="text" class="autoInput grow w-[100%]" name="apartment_rate"  value="<?php echo $_SESSION['apartment_rate'] ?? ''; ?>" placeholder="Enter Rate" required />
                </label>
            </span>
            <span class="w-[100%]">
                <p class="mb-2 text-sm"> Cover Photo * </p>
                <?php if (!empty($_SESSION['apartment_cover'])): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($_SESSION['apartment_cover']); ?>">
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                        <div class="flex items-center gap-2">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($_SESSION['apartment_cover']); ?>" class="w-10 h-10 object-cover rounded" alt="Preview">
                            <span>Previously selected: <strong class="underline"><?php echo htmlspecialchars($_SESSION['apartment_cover']); ?></strong></span>
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
                    
                        <?php echo !empty($_SESSION['apartment_cover']) ? '' : 'required'; ?> 
                    />
                </label>
            </span>
        </div>
        <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
            <span class="w-[100%]">
                <p class="mb-2 text-sm">Other Informations *</p>
                <textarea class="input w-[100%] border border-gray-300 rounded-sm min-h-50 p-3" name="apartment_other_info" placeholder="Enter Other Informations" required><?php echo htmlspecialchars($_SESSION['apartment_other_info'] ?? ''); ?></textarea>
            </span>
        </div>
        <p class="mb-3">Units Specification</p>
        <div class="mb-5">        
            <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Apartment Type *</p>
                <label class="input w-[100%]">
                    <svg xmlns="http://www.w3.org/2000/svg"  class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                    <select class="select w-[100%]" name="type" required>
                        <option value="" disabled <?php echo empty($_SESSION['type']) ? 'selected' : ''; ?>>Select Apartment Type</option>
                        <option value="Studio" <?php echo (($_SESSION['type'] ?? '') == 'Studio') ? 'selected' : ''; ?>>Studio</option>
                        <option value="1 Bed Room" <?php echo (($_SESSION['type'] ?? '') == '1 Bed Room') ? 'selected' : ''; ?>>1 Bed Room</option>
                        <option value="2 Bed Room" <?php echo (($_SESSION['type'] ?? '') == '2 Bed Room') ? 'selected' : ''; ?>>2 Bed Room</option>
                        <option value="3 Bed Room" <?php echo (($_SESSION['type'] ?? '') == '3 Bed Room') ? 'selected' : ''; ?>>3 Bed Room</option>
                    </select>
                </label>
            </div>
             <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm">Multiple Photos (Upload 3 to 10 Photos) *</p>

                <?php if (!empty($_SESSION['gallery'])): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-3">
                        <?php foreach ($_SESSION['gallery'] as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" 
                                    class="w-full h-16 object-cover rounded border border-success/30">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs rounded-lg">
                        <?php echo count($_SESSION['gallery']); ?> photo(s) retained. Upload new photos below to replace them.
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
                        <?php echo !empty($_SESSION['gallery']) ? '' : 'required'; ?>
                    />
                </label>
                <p class="text-xs text-gray-400 mt-1">Please select 3–10 photos.</p>
            </div>               
        </div>           
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

            // Kunin lahat ng amenities isang beses lang
            $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
            $get_amen->bind_param("ss", $user_id_login, $active);
            $get_amen->execute();
            $result = $get_amen->get_result();

            $amenities = [];
            while($row = $result->fetch_assoc()){
                $amenities[] = $row;
            }

            // Kung walang session, gumawa ng isang blank dropdown
            if(empty($_SESSION['amenities'])){
                $_SESSION['amenities'] = [""];
            }

            $index = 0;

            foreach($_SESSION['amenities'] as $selectedAmen){
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

                <select class="select w-[100%]" name="apartment_amenity[]" required>
                    <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>

                    <?php foreach($amenities as $amen){ ?>
                        <option value="<?= $amen['amen_id']; ?>"
                            <?= ($selectedAmen == $amen['amen_id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($amen['amenity']); ?>
                        </option>
                    <?php } ?>
                </select>

                <?php if($index > 0){ ?>
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
            <button type="submit" name="save_apartment" class="btn btn-success text-white">Save</button>
        </div>
    </form>
  </div>
</dialog>


