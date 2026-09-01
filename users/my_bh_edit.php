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
    SELECT name, price, image_cover, other_info, rate
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
$rate        = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
    $rate        = $rent_row['rate'];
}

// BOARDING HOUSES
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
    $full_bed_name = $row['bed_number'];
    $base_bed_name = preg_replace('/\s*-\s*Deck\s*\d+/i', '', $full_bed_name);

    if (!isset($boarding_houses[$base_bed_name])) {
        $boarding_houses[$base_bed_name] = [
            'boarding_id' => $row['boarding_id'],
            'bed_number'  => $base_bed_name,
            'image'       => $row['image'],
            'num_decks'   => (int)$row['num_decks'],
            'statuses'    => [$row['status']]
        ];
    } else {
        $boarding_houses[$base_bed_name]['statuses'][] = $row['status'];
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

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.tiny.cloud/1/ssew95wvyspsqckuynqjy38ov5ktrks0qbp94mxchgh1ucty/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<!-- Professional Modal Wrapper -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto">
  <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">

    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70 sticky top-0 z-10 backdrop-blur-md">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-slate-800">Edit Room Configuration</h2>
          <p class="text-xs text-slate-500">Update room details, beds, and amenities.</p>
        </div>
      </div>
      <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
         class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
        ✕
      </a>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($_SESSION['error'])): ?>
    <div class="mx-6 mt-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium px-4 py-3 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-8" id="mainForm">
        <input type="hidden" name="landlord_id" value="<?php echo $landlord_id; ?>">
        <input type="hidden" name="rent_id" value="<?php echo $rent_id; ?>">

        <!-- ============================================ -->
        <!-- SECTION 1: ROOM INFORMATION -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Room Name -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Room Name / Number *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/>
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/>
                            </svg>
                        </span>
                        <input type="text"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="name"
                               value="<?php echo htmlspecialchars($name); ?>"
                               placeholder="Enter Name / Number"
                               required />
                    </div>
                </div>

                <!-- Price -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Price / Month *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none font-semibold text-sm">₱</span>
                        <input type="text"
                               class="w-full pl-8 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all numbers_only"
                               name="price"
                               value="<?php echo htmlspecialchars($price); ?>"
                               placeholder="Enter Price / Month"
                               required />
                    </div>
                </div>

                <!-- Rate -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Rate Basis *</label>
                    <div class="relative flex items-center">
                        <input type="text"
                               class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="rate"
                               value="<?php echo htmlspecialchars($rate); ?>"
                               placeholder="e.g. Per Month / Per Night"
                               required />
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- COVER PHOTO -->
            <!-- ============================================ -->
            <div class="space-y-1.5 pt-2" id="cover-section">
                <label class="block text-xs font-semibold text-slate-600">Cover Photo *</label>

                <!-- Retained cover from database -->
                <?php if (!empty($image_cover)): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($image_cover); ?>">
                    <div class="flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <div class="flex items-center gap-3">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>"
                                 class="w-10 h-10 object-cover rounded-lg border border-white shadow-xs"
                                 alt="Preview">
                            <span class="text-emerald-900">Previously selected:
                                <strong class="underline font-medium"><?php echo htmlspecialchars($image_cover); ?></strong>
                            </span>
                        </div>
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider">Retained</span>
                    </div>
                <?php endif; ?>

                <!-- Dropzone -->
                <label id="cover-label" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 hover:border-emerald-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-4 pb-4" id="cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-1">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p class="text-xs text-slate-500"><span class="font-semibold text-emerald-600">Click to upload</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, JPG accepted</p>
                    </div>
                    <input type="file"
                           class="hidden"
                           id="cover"
                           name="cover"
                           accept="image/jpeg, image/png, image/jpg"
                           <?php echo !empty($image_cover) ? '' : 'required'; ?> />
                </label>

                <!-- Preview after select -->
                <div id="cover-preview-container" class="hidden mt-3 flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs">
                    <div class="flex items-center gap-3">
                        <img id="cover-preview-img" src="" class="w-12 h-12 object-cover rounded-lg border border-white shadow-sm" alt="Cover Preview">
                        <div>
                            <p class="text-emerald-900 font-semibold">Selected file:</p>
                            <p id="cover-preview-name" class="text-emerald-700 underline truncate max-w-[200px]"></p>
                            <p id="cover-preview-size" class="text-slate-400 text-[10px] mt-0.5"></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider shrink-0">New</span>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- TINYMCE EDITOR -->
            <!-- ============================================ -->
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-semibold text-slate-600">Other Informations *</label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                    <textarea
                        id="myEditor"
                        name="other_info"><?php echo $other_info; ?></textarea>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- SECTION 2: BEDS MANAGEMENT -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Beds Information</h3>
                <button type="button" id="addBedBtn"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Bed
                </button>
            </div>

            <div id="beds-container" class="space-y-4">
                <?php
                $bedIndex = 0;
                if (!empty($boarding_houses)):
                    foreach ($boarding_houses as $bed_name => $boarding_house):
                ?>
                <div class="bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative" data-bed-index="<?php echo $bedIndex; ?>">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700 bed-title"><?php echo htmlspecialchars($bed_name); ?></span>
                        <?php if ($bedIndex > 0): ?>
                        <button type="button" class="remove-bed-btn text-rose-400 hover:text-rose-600 text-xs font-medium transition-colors flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            Remove
                        </button>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Bed Identifier -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                            <input type="text" name="bednum[]"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none bed-input-name"
                                   value="<?php echo htmlspecialchars($bed_name); ?>"
                                   readonly>
                        </div>

                        <!-- Number of Decks -->
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Number of Decks</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 num-deck-select"
                                    name="num_deck[]" required>
                                <option value="1" <?= (int)$boarding_house['num_decks'] === 1 ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= (int)$boarding_house['num_decks'] === 2 ? 'selected' : '' ?>>2</option>
                                <option value="3" <?= (int)$boarding_house['num_decks'] === 3 ? 'selected' : '' ?>>3</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bed Image -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Bed Image</label>
                        <input type="hidden" name="old_image[]" value="<?php echo htmlspecialchars($boarding_house['image'] ?? ''); ?>">

                        <?php if (!empty($boarding_house['image'])): ?>
                        <div class="flex items-center justify-between bg-emerald-50/50 border border-emerald-100 p-2 rounded-lg text-xs mb-2">
                            <div class="flex items-center gap-2">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($boarding_house['image']); ?>"
                                     class="w-10 h-10 object-cover rounded-lg border border-white shadow-xs" alt="Bed Image">
                                <span class="text-emerald-900 truncate">Previously: <strong><?php echo htmlspecialchars($boarding_house['image']); ?></strong></span>
                            </div>
                            <span class="text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded shrink-0">Retained</span>
                        </div>
                        <?php endif; ?>

                        <input type="file"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer bg-white border border-slate-200 rounded-lg p-1"
                               name="image[]"
                               accept="image/jpeg,image/jpg,image/png">
                    </div>

                    <!-- Status per Deck -->
                    <div class="deck-status-wrapper space-y-2">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>
                        <?php
                            $numDecks    = (int)$boarding_house['num_decks'];
                            $savedStatuses = $boarding_house['statuses'];
                            for ($d = 0; $d < $numDecks; $d++):
                                $deckLabel     = ($numDecks > 1) ? "Deck " . ($d + 1) : "Status";
                                $currentStatus = $savedStatuses[$d] ?? 'Available';
                        ?>
                        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2">
                            <label class="text-xs text-slate-500 w-16 shrink-0"><?php echo $deckLabel; ?></label>
                            <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none focus:ring-0"
                                    name="status[<?php echo $bedIndex; ?>][]" required>
                                <option value="Available" <?= $currentStatus == 'Available' ? 'selected' : '' ?>>Available</option>
                                <option value="Occupied"  <?= $currentStatus == 'Occupied'  ? 'selected' : '' ?>>Occupied</option>
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
        </div>

        <!-- ============================================ -->
        <!-- SECTION 3: ROOM AMENITIES -->
        <!-- ============================================ -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Amenities</h3>
                <button type="button" id="addamenBtn"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Amenities
                </button>
            </div>

            <?php
            $active = "yes";
            $get_amen2 = $conn->prepare("SELECT DISTINCT amen_id, amenity FROM amenities WHERE user_id=? AND active=?");
            $get_amen2->bind_param("ss", $user_id_login, $active);
            $get_amen2->execute();
            $result2 = $get_amen2->get_result();

            $amenities = [];
            while ($row = $result2->fetch_assoc()) {
                if (!empty($row['amen_id'])) {
                    $amenities[$row['amen_id']] = $row['amenity'];
                }
            }

            $selectedAmenities = !empty($saved_amenities) ? $saved_amenities : [""];
            ?>

            <div id="amenities-container" class="space-y-2">
                <?php if (!empty($amenities)): ?>
                    <?php foreach ($selectedAmenities as $index => $selectedAmen): ?>
                    <?php $currentAmenId = is_array($selectedAmen) ? ($selectedAmen['amen_id'] ?? '') : $selectedAmen; ?>
                    <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                        <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                name="amenity[]" required>
                            <option value="" disabled <?= empty($currentAmenId) ? 'selected' : ''; ?>>-- Select Amenity --</option>
                            <?php foreach ($amenities as $id => $amenName): ?>
                                <option value="<?= htmlspecialchars($id); ?>" <?= ($currentAmenId == $id) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($amenName); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <?php if ($index > 0): ?>
                        <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- ACTION BUTTONS -->
        <!-- ============================================ -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white py-3 -mx-6 px-6">
            <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
               Cancel
            </a>
            <button type="submit" name="edit_boarding"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Update
            </button>
        </div>
    </form>
  </div>
</div>

<!-- ============================================ -->
<!-- JAVASCRIPT -->
<!-- ============================================ -->
<script>
// ============================================
// TINYMCE INIT
// ============================================
tinymce.init({
    selector: '#myEditor',
    height: 400,
    menubar: 'file edit view insert format tools table help',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'visualchars', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount',
        'emoticons', 'template', 'codesample', 'directionality', 'nonbreaking',
        'pagebreak', 'quickbars', 'save'
    ],
    toolbar1: 'undo redo | save | newdocument | bold italic underline strikethrough | ' +
              'forecolor backcolor | alignleft aligncenter alignright alignjustify | ' +
              'bullist numlist | outdent indent',
    toolbar2: 'formatselect fontselect fontsizeselect | ' +
              'link image media | table | codesample code | ' +
              'charmap emoticons | insertdatetime | ' +
              'searchreplace | visualblocks visualchars | ' +
              'ltr rtl | pagebreak nonbreaking | ' +
              'fullscreen preview | removeformat | help',

    font_formats:
        'Arial=arial,helvetica,sans-serif;' +
        'Courier New=courier new,courier;' +
        'Georgia=georgia,palatino;' +
        'Helvetica=helvetica;' +
        'Times New Roman=times new roman,times;' +
        'Verdana=verdana,geneva;',

    fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote; Code=pre',

    image_advtab: true,
    image_caption: true,
    automatic_uploads: true,
    file_picker_types: 'image media',
    images_upload_url: '../upload_image.php',
    images_upload_handler: function (blobInfo, success, failure) {
        var formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        fetch('../upload_image.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(result => result.location ? success(result.location) : failure('Upload failed'))
            .catch(error => failure('Upload error: ' + error));
    },

    table_default_attributes: { border: '1' },
    table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
    link_default_target: '_blank',
    link_context_toolbar: true,
    paste_data_images: true,
    browser_spellcheck: true,
    wordcount_countcharacters: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_restore_when_empty: true,
    resize: 'both',
    statusbar: true,

    content_style: `
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; padding: 10px; }
        h1, h2, h3 { color: #1a1a2e; margin-top: 1em; }
        table { border-collapse: collapse; width: 100%; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background-color: #f2f2f2; }
        img { max-width: 100%; height: auto; }
        blockquote { border-left: 4px solid #10b981; margin: 0; padding-left: 16px; color: #555; }
    `,

    setup: function (editor) {
        // ✅ Sync on change
        editor.on('change keyup', function () {
            editor.save();
        });
    },

    // ✅ Pre-fill existing content from database
    init_instance_callback: function (editor) {
        const content = <?php echo json_encode($other_info); ?>;
        if (content) {
            editor.setContent(content);
        }
    }
});

// ============================================
// DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', function () {

    // ✅ TinyMCE sync before submit
    const form = document.getElementById('mainForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }

    // ============================================
    //  COVER PHOTO LIVE PREVIEW
    // ============================================
    const coverInput       = document.getElementById('cover');
    const previewContainer = document.getElementById('cover-preview-container');
    const previewImg       = document.getElementById('cover-preview-img');
    const previewName      = document.getElementById('cover-preview-name');
    const previewSize      = document.getElementById('cover-preview-size');
    const coverLabel       = document.getElementById('cover-label');
    const coverPlaceholder = document.getElementById('cover-placeholder');

    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                // Update preview elements
                previewImg.src            = ev.target.result;
                previewName.textContent   = file.name;
                previewSize.textContent   = (file.size / 1024).toFixed(1) + ' KB';

                // Show preview
                previewContainer.classList.remove('hidden');
                coverLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');

                // Update dropzone state
                coverPlaceholder.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 mb-1">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <p class="text-xs text-emerald-600 font-semibold">Photo selected!</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Click to change</p>
                `;
            };
            reader.readAsDataURL(file);
        });
    }

    // ============================================
    //  DECK STATUS: Update on deck change
    // ============================================
    const bedsContainer = document.getElementById('beds-container');

    function updateDeckStatusUI(bedItem, numDecks) {
        const bedIndex = bedItem.getAttribute('data-bed-index');
        const wrapper  = bedItem.querySelector('.deck-status-wrapper');

        wrapper.innerHTML = '<p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>';

        for (let d = 0; d < numDecks; d++) {
            const deckLabel = numDecks > 1 ? `Deck ${d + 1}` : 'Status';
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2';
            row.innerHTML = `
                <label class="text-xs text-slate-500 w-16 shrink-0">${deckLabel}</label>
                <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none focus:ring-0"
                        name="status[${bedIndex}][]" required>
                    <option value="Available" selected>Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Out of Order">Out of Order</option>
                </select>
            `;
            wrapper.appendChild(row);
        }
    }

    if (bedsContainer) {
        // Change deck count → update status rows
        bedsContainer.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('num-deck-select')) {
                const bedItem  = e.target.closest('.bed-item');
                const numDecks = parseInt(e.target.value) || 1;
                updateDeckStatusUI(bedItem, numDecks);
            }
        });

        // Remove bed
        bedsContainer.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-bed-btn');
            if (removeBtn) {
                removeBtn.closest('.bed-item').remove();
                // Renumber beds
                bedsContainer.querySelectorAll('.bed-item').forEach((item, i) => {
                    const label = `Bed ${i + 1}`;
                    item.querySelector('.bed-title').textContent     = label;
                    item.querySelector('.bed-input-name').value      = label;
                    item.setAttribute('data-bed-index', i);
                });
            }
        });
    }

    // ============================================
    // ✅ ADD BED
    // ============================================
    const addBedBtn = document.getElementById('addBedBtn');

    if (addBedBtn && bedsContainer) {
        addBedBtn.addEventListener('click', function () {
            const bedItems = bedsContainer.querySelectorAll('.bed-item');
            const newIndex = bedItems.length;
            const bedLabel = `Bed ${newIndex + 1}`;

            const newBedDiv = document.createElement('div');
            newBedDiv.className = 'bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative';
            newBedDiv.setAttribute('data-bed-index', newIndex);

            newBedDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-700 bed-title">${bedLabel}</span>
                    <button type="button" class="remove-bed-btn text-rose-400 hover:text-rose-600 text-xs font-medium transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                        <input type="text" name="bednum[]"
                               class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none bed-input-name"
                               value="${bedLabel}" readonly>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Number of Decks</label>
                        <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 num-deck-select"
                                name="num_deck[]" required>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-[11px] font-medium text-slate-500">Bed Image</label>
                    <input type="hidden" name="old_image[]" value="">
                    <input type="file"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer bg-white border border-slate-200 rounded-lg p-1"
                           name="image[]" accept="image/jpeg,image/jpg,image/png" required>
                </div>

                <div class="deck-status-wrapper space-y-2">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>
                    <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2">
                        <label class="text-xs text-slate-500 w-16 shrink-0">Status</label>
                        <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none"
                                name="status[${newIndex}][]" required>
                            <option value="Available" selected>Available</option>
                            <option value="Occupied">Occupied</option>
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