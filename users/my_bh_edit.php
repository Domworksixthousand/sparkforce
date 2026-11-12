<?php
include 'my_property.php';
if (isset($_GET['id']) && isset($_GET['property_id'])) {
    $rent_id     = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    echo "<script>location.href='index.php';</script>";
    exit;
}

// RENTSPACE DETAILS (including saved location)
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, rate,
           latitude, longitude, barangay, municipality, province
    FROM rentspace
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name = $price = $image_cover = $other_info = $rate = "";
$latitude = $longitude = $barangay = $municipality = $province = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name         = $rent_row['name'] ?? '';
    $price        = $rent_row['price'] ?? '';
    $image_cover  = $rent_row['image_cover'] ?? '';
    $other_info   = $rent_row['other_info'] ?? '';
    $rate         = $rent_row['rate'] ?? '';
    $latitude     = $rent_row['latitude'] ?? '';
    $longitude    = $rent_row['longitude'] ?? '';
    $barangay     = $rent_row['barangay'] ?? '';
    $municipality = $rent_row['municipality'] ?? '';
    $province     = $rent_row['province'] ?? '';
} else {
    // Room does not exist: do not render an empty edit form
    $get_rent->close();
    echo "<script>location.href='my_property.php?property_id=" . urlencode($landlord_id) . "';</script>";
    exit;
}
$get_rent->close();

// SAVED LOCATION: valid only if both coordinates exist and are not 0,0
$hasCoords = (
    $latitude !== '' && $latitude !== null &&
    $longitude !== '' && $longitude !== null &&
    !((float)$latitude == 0.0 && (float)$longitude == 0.0)
);

$cur_lat  = $hasCoords ? number_format((float)$latitude, 7, '.', '') : '';
$cur_lng  = $hasCoords ? number_format((float)$longitude, 7, '.', '') : '';
$cur_prov = (string)$province;
$cur_mun  = (string)$municipality;
$cur_brgy = (string)$barangay;

// BOARDING HOUSES (one row per deck)
$get_bh = $conn->prepare("
    SELECT boarding_id, status, num_decks, image, bed_number
    FROM boarding_house
    WHERE rent_id = ?
    ORDER BY bed_number ASC
");
$get_bh->bind_param("s", $rent_id);
$get_bh->execute();
$bh_res = $get_bh->get_result();

$boarding_houses = [];

while ($row = $bh_res->fetch_assoc()) {
    // Group deck rows by base bed name ("Bed 1 - Deck 2" -> "Bed 1")
    $base_bed_name = trim(preg_replace('/\s*-\s*Deck\s*\d+/i', '', (string)$row['bed_number']));

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
        // keep the first non-empty image found for this bed
        if (empty($boarding_houses[$base_bed_name]['image']) && !empty($row['image'])) {
            $boarding_houses[$base_bed_name]['image'] = $row['image'];
        }
    }
}
$get_bh->close();

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
$get_amen->close();
?>

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.tiny.cloud/1/ssew95wvyspsqckuynqjy38ov5ktrks0qbp94mxchgh1ucty/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />
<style>
    /* Tailwind preflight (img{max-width:100%}) breaks Leaflet tiles and markers. Undo it for the map only. */
    .bh-map .leaflet-container img,
    .bh-map img.leaflet-tile,
    .bh-map img.leaflet-marker-icon,
    .bh-map img.leaflet-marker-shadow {
        max-width: none !important;
        max-height: none !important;
    }
    /* Keep the geosearch bar above map tiles and readable */
    .bh-map .leaflet-top, .bh-map .leaflet-bottom { z-index: 400; }
    .bh-map .geosearch form input.glass { color: #1e293b; }
    #bh_map_modal::backdrop { background: rgba(15, 23, 42, .6); }
</style>

<!-- Modal Wrapper -->
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
          <p class="text-xs text-slate-500">Update room details, location, beds, and amenities.</p>
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
        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <!-- Client-side error (validation) -->
    <div id="clientError" class="hidden mx-6 mt-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium px-4 py-3 rounded-xl"></div>

    <!-- Form -->
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-8" id="mainForm">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">
        <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">

        <!-- SECTION 1: ROOM INFORMATION -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Room Name / Number *</label>
                    <input type="text"
                           class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                           name="name" value="<?php echo htmlspecialchars($name); ?>"
                           placeholder="Enter Name / Number" required />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Price / Month *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none font-semibold text-sm">₱</span>
                        <input type="text" inputmode="decimal"
                               class="w-full pl-8 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all numbers_only"
                               name="price" value="<?php echo htmlspecialchars($price); ?>"
                               placeholder="Enter Price / Month" required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Rate Basis *</label>
                    <input type="text"
                           class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                           name="rate" value="<?php echo htmlspecialchars($rate); ?>"
                           placeholder="e.g. Per Month / Per Night" required />
                </div>
            </div>

            <!-- COVER PHOTO -->
            <div class="space-y-1.5 pt-2" id="cover-section">
                <label class="block text-xs font-semibold text-slate-600">Cover Photo *</label>

                <?php if (!empty($image_cover)): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($image_cover); ?>">
                    <div class="flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <div class="flex items-center gap-3">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>"
                                 class="w-10 h-10 object-cover rounded-lg border border-white shadow-xs" alt="Preview">
                            <span class="text-emerald-900">Previously selected:
                                <strong class="underline font-medium"><?php echo htmlspecialchars($image_cover); ?></strong>
                            </span>
                        </div>
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider">Retained</span>
                    </div>
                <?php endif; ?>

                <label id="cover-label" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 hover:border-emerald-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-4 pb-4" id="cover-placeholder">
                        <p class="text-xs text-slate-500"><span class="font-semibold text-emerald-600">Click to upload</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, JPG accepted</p>
                    </div>
                    <input type="file" class="hidden" id="cover" name="cover"
                           accept="image/jpeg, image/png, image/jpg"
                           <?php echo !empty($image_cover) ? '' : 'required'; ?> />
                </label>

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

            <!-- TINYMCE -->
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-semibold text-slate-600">Other Informations *</label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                    <!-- Escaped so stored HTML (even a stray </textarea>) can never break the page; TinyMCE decodes it -->
                    <textarea id="myEditor" name="other_info"><?php echo htmlspecialchars((string)$other_info); ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: ROOM LOCATION -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Location</h3>
                <button type="button" onclick="bhOpenMap()"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    Locate
                </button>
            </div>
            <p class="text-[11px] text-slate-400">Click Locate to search a place, click the map, or drag the pin. The address is taken from the pinned point.</p>

            <input type="hidden" id="lat" name="latitude"  value="<?php echo htmlspecialchars($cur_lat); ?>">
            <input type="hidden" id="lng" name="longitude" value="<?php echo htmlspecialchars($cur_lng); ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Province</label>
                    <input type="text" id="province1" name="province1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($cur_prov); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Municipality</label>
                    <input type="text" id="municipality1" name="municipality1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($cur_mun); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Barangay</label>
                    <input type="text" id="barangay1" name="barangay1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($cur_brgy); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
            </div>

            <p id="loc_status" class="text-[11px] text-slate-500">
                <?php echo $hasCoords
                    ? 'Saved coordinates: ' . htmlspecialchars($cur_lat) . ', ' . htmlspecialchars($cur_lng)
                    : 'No location saved yet. Click Locate to pin this room.'; ?>
            </p>
        </div>

        <!-- SECTION 3: BEDS -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Beds Information</h3>
                <button type="button" id="addBedBtn"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                    Add Bed
                </button>
            </div>

            <div id="beds-container" class="space-y-4">
                <?php
                $bedIndex = 0;
                foreach ($boarding_houses as $bed_name => $boarding_house):
                ?>
                <div class="bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative" data-bed-index="<?php echo $bedIndex; ?>">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700 bed-title"><?php echo htmlspecialchars($bed_name); ?></span>
                        <?php if ($bedIndex > 0): ?>
                        <button type="button" class="remove-bed-btn text-rose-400 hover:text-rose-600 text-xs font-medium transition-colors">Remove</button>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                            <input type="text" name="bednum[]"
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none bed-input-name"
                                   value="<?php echo htmlspecialchars($bed_name); ?>" readonly>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Number of Decks</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 num-deck-select"
                                    name="num_deck[]" required>
                                <option value="1" <?= (int)$boarding_house['num_decks'] === 1 ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= (int)$boarding_house['num_decks'] === 2 ? 'selected' : '' ?>>2</option>
                                <option value="3" <?= (int)$boarding_house['num_decks'] >= 3 ? 'selected' : '' ?>>3</option>
                            </select>
                        </div>
                    </div>

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
                               class="w-full text-xs text-slate-500 bg-white border border-slate-200 rounded-lg p-1 cursor-pointer"
                               name="image[]" accept="image/jpeg,image/jpg,image/png">
                    </div>

                    <div class="deck-status-wrapper space-y-2">
                        <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>
                        <?php
                            $numDecks      = min(3, max(1, (int)$boarding_house['num_decks']));
                            $savedStatuses = $boarding_house['statuses'];
                            for ($d = 0; $d < $numDecks; $d++):
                                $deckLabel     = ($numDecks > 1) ? "Deck " . ($d + 1) : "Status";
                                $currentStatus = $savedStatuses[$d] ?? 'Available';
                        ?>
                        <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2">
                            <label class="text-xs text-slate-500 w-16 shrink-0"><?php echo $deckLabel; ?></label>
                            <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none focus:ring-0"
                                    name="status[<?php echo $bedIndex; ?>][]" required>
                                <option value="Available"    <?= $currentStatus == 'Available'    ? 'selected' : '' ?>>Available</option>
                                <option value="Occupied"     <?= $currentStatus == 'Occupied'     ? 'selected' : '' ?>>Occupied</option>
                                <option value="Out of Order" <?= $currentStatus == 'Out of Order' ? 'selected' : '' ?>>Out of Order</option>
                            </select>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php
                    $bedIndex++;
                endforeach;
                ?>
            </div>
        </div>

        <!-- SECTION 4: AMENITIES -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Amenities</h3>
                <button type="button" id="addamenBtn"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                    Add Amenities
                </button>
            </div>

            <?php
            $active = "yes";
            if (!empty($user_id_login)) {
                $get_amen_list = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
                $get_amen_list->bind_param("ss", $user_id_login, $active);
            } else {
                $get_amen_list = $conn->prepare("SELECT * FROM amenities WHERE active=?");
                $get_amen_list->bind_param("s", $active);
            }
            $get_amen_list->execute();
            $result = $get_amen_list->get_result();

            $amenities = [];
            $amenity_ids_in_list = [];
            while ($row = $result->fetch_assoc()) {
                $amenities[] = $row;
                $amenity_ids_in_list[] = (string)$row['amen_id'];
            }
            $get_amen_list->close();

            // Keep already-saved amenities in the dropdown even if inactive / created by another user
            foreach ($saved_amenities as $sv) {
                if (!in_array((string)$sv['amen_id'], $amenity_ids_in_list, true)) {
                    $amenities[] = ['amen_id' => $sv['amen_id'], 'amenity' => $sv['amenity']];
                    $amenity_ids_in_list[] = (string)$sv['amen_id'];
                }
            }

            $rows = !empty($saved_amenities) ? $saved_amenities : [['amen_id' => '', 'rent_amen_id' => '']];
            ?>

            <div id="amenities-container" class="space-y-2">
                <?php foreach ($rows as $saved):
                    $selectedAmen = (string)$saved['amen_id'];
                ?>
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <input type="hidden" name="rentspace_amenities_id[]" value="<?php echo htmlspecialchars($saved['rent_amen_id']); ?>">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" name="amenity[]">
                        <option value="" <?php echo $selectedAmen === '' ? 'selected' : ''; ?>>Select Amenity</option>
                        <?php foreach ($amenities as $amen): ?>
                            <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>"
                                <?php echo ($selectedAmen === (string)$amen['amen_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amen['amenity']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">✕</button>
                </div>
                <?php endforeach; ?>
            </div>

            <template id="amen-template">
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <input type="hidden" name="rentspace_amenities_id[]" value="">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" name="amenity[]">
                        <option value="" selected>Select Amenity</option>
                        <?php foreach ($amenities as $amen): ?>
                            <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>">
                                <?php echo htmlspecialchars($amen['amenity']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">✕</button>
                </div>
            </template>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 sticky -bottom-6 bg-white py-3 -mx-6 px-6 -mb-6 z-10">
            <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
               Cancel
            </a>
            <button type="submit" name="edit_boarding"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all">
                Update
            </button>
        </div>
    </form>
  </div>
</div>

<!-- MAP MODAL (pure Tailwind: no daisyUI needed; m-auto is required because preflight zeroes dialog margins) -->
<dialog id="bh_map_modal" class="m-auto w-11/12 max-w-4xl p-0 rounded-2xl bg-white shadow-2xl">
  <div class="p-5 bg-white rounded-2xl">
    <div class="flex items-center justify-between mb-2">
      <div>
        <h3 class="font-bold text-slate-800">Pin Room Location</h3>
        <p class="text-xs text-slate-500">Search a place, or click the map. You can also drag the pin.</p>
      </div>
      <button type="button"
              class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors"
              onclick="document.getElementById('bh_map_modal').close()">✕</button>
    </div>

    <div id="bh_map_wrap" class="bh-map">
      <div id="bh_map" style="height:440px;width:100%;" class="rounded-xl border border-slate-200"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-3 text-xs">
      <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
        <p class="text-[10px] uppercase tracking-wider text-slate-400">Barangay</p>
        <p id="bh_sel_brgy" class="font-semibold text-slate-700 truncate">—</p>
      </div>
      <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
        <p class="text-[10px] uppercase tracking-wider text-slate-400">Municipality</p>
        <p id="bh_sel_mun" class="font-semibold text-slate-700 truncate">—</p>
      </div>
      <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
        <p class="text-[10px] uppercase tracking-wider text-slate-400">Province</p>
        <p id="bh_sel_prov" class="font-semibold text-slate-700 truncate">—</p>
      </div>
      <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
        <p class="text-[10px] uppercase tracking-wider text-slate-400">Coordinates</p>
        <p id="bh_sel_coord" class="font-semibold text-slate-700 truncate">—</p>
      </div>
    </div>

    <div class="flex items-center justify-between mt-3 gap-3">
      <p class="text-xs text-slate-500" id="bh_sel_status">No location selected yet.</p>
      <button type="button"
              class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors"
              onclick="document.getElementById('bh_map_modal').close()">Done</button>
    </div>
  </div>
</dialog>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>

<script>
// ============================================
// TINYMCE (v6 option names and upload handler)
// ============================================
tinymce.init({
    selector: '#myEditor',
    height: 400,
    menubar: 'file edit view insert format tools table help',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'visualchars', 'code',
        'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount',
        'emoticons', 'codesample', 'directionality', 'nonbreaking',
        'pagebreak', 'quickbars'
    ],
    toolbar: [
        'undo redo | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent',
        'blocks fontfamily fontsize | link image media | table | codesample code | charmap emoticons | insertdatetime | searchreplace | visualblocks visualchars | ltr rtl | pagebreak nonbreaking | fullscreen preview | removeformat | help'
    ],
    font_family_formats:
        'Arial=arial,helvetica,sans-serif;' +
        'Courier New=courier new,courier;' +
        'Georgia=georgia,palatino;' +
        'Helvetica=helvetica;' +
        'Times New Roman=times new roman,times;' +
        'Verdana=verdana,geneva;',
    font_size_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote; Code=pre',
    image_advtab: true,
    image_caption: true,
    automatic_uploads: true,
    file_picker_types: 'image media',
    // TinyMCE 6: handler must RETURN a Promise that resolves with the image URL
    images_upload_handler: function (blobInfo) {
        return new Promise(function (resolve, reject) {
            var formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            fetch('../upload_image.php', { method: 'POST', body: formData })
                .then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function (result) {
                    if (result && result.location) resolve(result.location);
                    else reject('Upload failed');
                })
                .catch(function (error) { reject('Upload error: ' + error.message); });
        });
    },
    table_default_attributes: { border: '1' },
    table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
    link_default_target: '_blank',
    link_context_toolbar: true,
    paste_data_images: true,
    browser_spellcheck: true,
    wordcount_countcharacters: true,
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
        editor.on('change keyup', function () { editor.save(); });
    }
});

// ============================================
// LOCATION PICKER
// ============================================
const BH_DEFAULT_CENTER = [12.7036, 124.0390]; // Irosin, Sorsogon

const BH_ZIP_TO_MUN = {
    "4707": "Irosin", "4700": "Sorsogon City", "4701": "Bacon", "4702": "Casiguran",
    "4703": "Castilla", "4704": "Donsol", "4705": "Gubat", "4706": "Juban",
    "4708": "Magallanes", "4709": "Matnog", "4710": "Pilar", "4711": "Prieto Diaz",
    "4712": "Santa Magdalena", "4713": "Bulusan", "4714": "Barcelona", "4715": "Bulan"
};

let bhMap = null;
let bhMarker = null;
let bhReqId = 0;

function bhEl(id) { return document.getElementById(id); }

function bhRenderSel(v) {
    bhEl('bh_sel_brgy').textContent  = v.barangay     || '—';
    bhEl('bh_sel_mun').textContent   = v.municipality || '—';
    bhEl('bh_sel_prov').textContent  = v.province     || '—';
    bhEl('bh_sel_coord').textContent = (v.lat != null && v.lng != null)
        ? v.lat.toFixed(6) + ', ' + v.lng.toFixed(6) : '—';
    bhEl('bh_sel_status').textContent = v.status || '';
}

function bhPopupEl(title, sub) {
    const d = document.createElement('div');
    const b = document.createElement('b');
    b.textContent = title;
    const s = document.createElement('div');
    s.textContent = sub;
    d.append(b, s);
    return d;
}

function bhPlaceMarker(lat, lng, title, sub) {
    if (!bhMarker) {
        bhMarker = L.marker([lat, lng], { draggable: true }).addTo(bhMap);
        bhMarker.on('dragend', function () {
            const p = bhMarker.getLatLng();
            bhPick(p.lat, p.lng);
        });
    } else {
        bhMarker.setLatLng([lat, lng]);
    }
    bhMarker.unbindPopup().bindPopup(bhPopupEl(title, sub)).openPopup();
}

// zoom=16 gives barangay-level places. zoom=18 often returns sitio/purok (neighbourhood) instead.
async function bhReverse(lat, lng) {
    const url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&zoom=16&accept-language=en'
              + '&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng);
    const r = await fetch(url);
    if (!r.ok) throw new Error('HTTP ' + r.status);
    const j = await r.json();
    const a = j.address || {};

    let municipality = a.city || a.town || a.municipality || a.county || '';
    if (!municipality && a.postcode && BH_ZIP_TO_MUN[a.postcode]) {
        municipality = BH_ZIP_TO_MUN[a.postcode];
    }

    // Barangay-level tags first. neighbourhood/quarter (sitio/purok) last.
    const barangay = a.village || a.suburb || a.hamlet || a.neighbourhood || a.quarter || '';

    return {
        barangay:     barangay,
        municipality: municipality,
        province:     a.province || a.state || a.region || ''
    };
}

function bhPick(lat, lng) {
    bhEl('lat').value = lat.toFixed(7);
    bhEl('lng').value = lng.toFixed(7);
    bhEl('loc_status').textContent = 'New location selected. Click Update to save.';

    const reqId = ++bhReqId;
    bhPlaceMarker(lat, lng, 'Selected point', 'Looking up address...');
    bhRenderSel({ lat: lat, lng: lng, status: 'Looking up address...' });

    bhReverse(lat, lng).then(function (info) {
        if (reqId !== bhReqId) return;

        bhEl('barangay1').value     = info.barangay;
        bhEl('municipality1').value = info.municipality;
        bhEl('province1').value     = info.province;

        const hasAddr = info.barangay || info.municipality || info.province;
        bhRenderSel({
            lat: lat, lng: lng,
            barangay: info.barangay, municipality: info.municipality, province: info.province,
            status: hasAddr
                ? 'Location set. Click Done, then Update to save.'
                : 'No address found for this point. Coordinates are still saved.'
        });
        bhPlaceMarker(lat, lng,
            info.barangay || 'Selected point',
            [info.municipality, info.province].filter(Boolean).join(', ') || 'No address found');
    }).catch(function (err) {
        if (reqId !== bhReqId) return;
        console.warn('Reverse geocode failed:', err);
        // Clear the old address so a wrong barangay is never saved with the new pin
        bhEl('barangay1').value     = '';
        bhEl('municipality1').value = '';
        bhEl('province1').value     = '';
        bhRenderSel({ lat: lat, lng: lng, status: 'Address lookup failed. Pin again or try another spot.' });
    });
}

function bhOpenMap() {
    if (typeof L === 'undefined') {
        alert('Map failed to load. Check your internet connection and refresh the page.');
        return;
    }
    bhEl('bh_map_modal').showModal();
    setTimeout(bhInitMap, 60);
}

function bhInitMap() {
    const savedLat = parseFloat(bhEl('lat').value);
    const savedLng = parseFloat(bhEl('lng').value);
    const hasSaved = !isNaN(savedLat) && !isNaN(savedLng) && !(savedLat === 0 && savedLng === 0);
    const start = hasSaved ? [savedLat, savedLng] : BH_DEFAULT_CENTER;
    const zoom  = hasSaved ? 17 : 14;

    if (!bhMap) {
        bhMap = L.map('bh_map').setView(start, zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(bhMap);

        bhMap.on('click', function (e) { bhPick(e.latlng.lat, e.latlng.lng); });

        if (window.GeoSearch) {
            const provider = new GeoSearch.OpenStreetMapProvider({
                params: { countrycodes: 'ph', 'accept-language': 'en' }
            });
            bhMap.addControl(new GeoSearch.GeoSearchControl({
                provider: provider,
                style: 'bar',
                showMarker: false,
                showPopup: false,
                autoClose: true,
                keepResult: true,
                searchLabel: 'Search place or address...'
            }));

            bhMap.on('geosearch/showlocation', function (e) {
                const lat = e.location.y, lng = e.location.x;
                bhMap.setView([lat, lng], 17);
                bhPick(lat, lng);
            });
        }
    } else {
        bhMap.setView(start, zoom);
    }

    if (hasSaved) {
        const brgy = bhEl('barangay1').value, mun = bhEl('municipality1').value, prov = bhEl('province1').value;
        bhPlaceMarker(savedLat, savedLng, brgy || 'Saved location',
            [mun, prov].filter(Boolean).join(', ') || 'Saved coordinates');
        bhRenderSel({ lat: savedLat, lng: savedLng, barangay: brgy, municipality: mun, province: prov,
                      status: 'Current saved location. Click the map or search to change it.' });
    } else {
        bhRenderSel({ status: 'No location selected yet. Click the map to select.' });
    }

    setTimeout(function () { bhMap.invalidateSize(); }, 100);
    setTimeout(function () { bhMap.invalidateSize(); }, 400);
}

window.addEventListener('resize', function () {
    if (bhMap) bhMap.invalidateSize();
});

// ============================================
// DOM READY
// ============================================
document.addEventListener('DOMContentLoaded', function () {

    // Close map dialog when clicking the dark backdrop
    const mapDialog = document.getElementById('bh_map_modal');
    if (mapDialog) {
        mapDialog.addEventListener('click', function (e) {
            if (e.target === mapDialog) mapDialog.close();
        });
    }

    // PRICE: digits and one decimal point only
    document.querySelectorAll('.numbers_only').forEach(function (inp) {
        inp.addEventListener('input', function () {
            let v = inp.value.replace(/[^0-9.]/g, '');
            const firstDot = v.indexOf('.');
            if (firstDot !== -1) {
                v = v.slice(0, firstDot + 1) + v.slice(firstDot + 1).replace(/\./g, '');
            }
            if (v !== inp.value) inp.value = v;
        });
    });

    // FORM SUBMIT: sync TinyMCE + validate the editor (a hidden textarea cannot use "required")
    const form = document.getElementById('mainForm');
    const clientError = document.getElementById('clientError');
    function showError(msg) {
        clientError.textContent = msg;
        clientError.classList.remove('hidden');
        clientError.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    if (form) {
        form.addEventListener('submit', function (e) {
            clientError.classList.add('hidden');
            let text = '';
            if (typeof tinymce !== 'undefined' && tinymce.get('myEditor')) {
                tinymce.triggerSave();
                text = tinymce.get('myEditor').getContent({ format: 'text' }).trim();
                const hasMedia = /<(img|iframe|video|embed)\b/i.test(tinymce.get('myEditor').getContent());
                if (!text && !hasMedia) {
                    e.preventDefault();
                    showError('Please fill in "Other Informations".');
                    return;
                }
            }
            // Make sure a new bed always has an image chosen
            if (!document.querySelectorAll('#beds-container .bed-item').length) {
                e.preventDefault();
                showError('Please add at least one bed.');
            }
        });
    }

    // COVER PREVIEW (+ drag and drop)
    const coverInput       = document.getElementById('cover');
    const previewContainer = document.getElementById('cover-preview-container');
    const previewImg       = document.getElementById('cover-preview-img');
    const previewName      = document.getElementById('cover-preview-name');
    const previewSize      = document.getElementById('cover-preview-size');
    const coverLabel       = document.getElementById('cover-label');
    const coverPlaceholder = document.getElementById('cover-placeholder');

    function showCoverPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            previewImg.src          = ev.target.result;
            previewName.textContent = file.name;
            previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
            previewContainer.classList.remove('hidden');
            coverLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');
            coverPlaceholder.innerHTML = `
                <p class="text-xs text-emerald-600 font-semibold">Photo selected!</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Click to change</p>`;
        };
        reader.readAsDataURL(file);
    }

    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            showCoverPreview(e.target.files[0]);
        });
    }
    if (coverLabel && coverInput) {
        ['dragenter', 'dragover'].forEach(function (ev) {
            coverLabel.addEventListener(ev, function (e) {
                e.preventDefault();
                coverLabel.classList.add('border-emerald-400');
            });
        });
        coverLabel.addEventListener('dragleave', function () {
            coverLabel.classList.remove('border-emerald-400');
        });
        coverLabel.addEventListener('drop', function (e) {
            e.preventDefault();
            const f = e.dataTransfer.files && e.dataTransfer.files[0];
            if (!f || !/^image\/(jpeg|png|jpg)$/i.test(f.type)) {
                coverLabel.classList.remove('border-emerald-400');
                return;
            }
            const dt = new DataTransfer();
            dt.items.add(f);
            coverInput.files = dt.files;
            showCoverPreview(f);
        });
    }

    // BEDS: deck count, remove, add
    const bedsContainer = document.getElementById('beds-container');

    // Keep data-bed-index and the status[...] field names in sync with the bed order
    function reindexBeds() {
        bedsContainer.querySelectorAll('.bed-item').forEach(function (item, i) {
            item.setAttribute('data-bed-index', i);
            item.querySelectorAll('select[name^="status["]').forEach(function (s) {
                s.name = 'status[' + i + '][]';
            });
        });
    }

    function updateDeckStatusUI(bedItem, numDecks) {
        const bedIndex = bedItem.getAttribute('data-bed-index');
        const wrapper  = bedItem.querySelector('.deck-status-wrapper');
        // Preserve statuses already chosen; only new decks default to Available
        const previous = Array.from(wrapper.querySelectorAll('select')).map(function (s) { return s.value; });
        wrapper.innerHTML = '<p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>';
        for (let d = 0; d < numDecks; d++) {
            const deckLabel = numDecks > 1 ? `Deck ${d + 1}` : 'Status';
            const cur = previous[d] || 'Available';
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2';
            row.innerHTML = `
                <label class="text-xs text-slate-500 w-16 shrink-0">${deckLabel}</label>
                <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none focus:ring-0" name="status[${bedIndex}][]" required>
                    <option value="Available"${cur === 'Available' ? ' selected' : ''}>Available</option>
                    <option value="Occupied"${cur === 'Occupied' ? ' selected' : ''}>Occupied</option>
                    <option value="Out of Order"${cur === 'Out of Order' ? ' selected' : ''}>Out of Order</option>
                </select>`;
            wrapper.appendChild(row);
        }
    }

    if (bedsContainer) {
        bedsContainer.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('num-deck-select')) {
                const bedItem  = e.target.closest('.bed-item');
                const numDecks = parseInt(e.target.value) || 1;
                updateDeckStatusUI(bedItem, numDecks);
            }
        });

        bedsContainer.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-bed-btn');
            if (removeBtn) {
                if (!confirm('Remove this bed? It will be deleted when you click Update.')) return;
                removeBtn.closest('.bed-item').remove();
                // Existing bed names are NOT renamed (the server matches beds by name);
                // only the array indexes are repaired so statuses stay paired with the right bed.
                reindexBeds();
            }
        });
    }

    const addBedBtn = document.getElementById('addBedBtn');
    if (addBedBtn && bedsContainer) {
        addBedBtn.addEventListener('click', function () {
            const newIndex = bedsContainer.querySelectorAll('.bed-item').length;
            let max = 0;
            bedsContainer.querySelectorAll('.bed-input-name').forEach(function (inp) {
                const m = inp.value.match(/(\d+)\s*$/);
                if (m) max = Math.max(max, parseInt(m[1], 10));
            });
            const bedLabel = 'Bed ' + (Math.max(max, newIndex) + 1);

            const newBedDiv = document.createElement('div');
            newBedDiv.className = 'bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative';
            newBedDiv.setAttribute('data-bed-index', newIndex);

            newBedDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-700 bed-title">${bedLabel}</span>
                    <button type="button" class="remove-bed-btn text-rose-400 hover:text-rose-600 text-xs font-medium transition-colors">Remove</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                        <input type="text" name="bednum[]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none bed-input-name" value="${bedLabel}" readonly>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Number of Decks</label>
                        <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 num-deck-select" name="num_deck[]" required>
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-medium text-slate-500">Bed Image</label>
                    <input type="hidden" name="old_image[]" value="">
                    <input type="file" class="w-full text-xs text-slate-500 bg-white border border-slate-200 rounded-lg p-1 cursor-pointer" name="image[]" accept="image/jpeg,image/jpg,image/png" required>
                </div>
                <div class="deck-status-wrapper space-y-2">
                    <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">Status per Deck</p>
                    <div class="flex items-center gap-3 bg-white border border-slate-200 rounded-lg px-3 py-2">
                        <label class="text-xs text-slate-500 w-16 shrink-0">Status</label>
                        <select class="w-full text-xs text-slate-700 bg-transparent border-none focus:outline-none" name="status[${newIndex}][]" required>
                            <option value="Available" selected>Available</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Out of Order">Out of Order</option>
                        </select>
                    </div>
                </div>`;
            bedsContainer.appendChild(newBedDiv);
        });
    }

    // AMENITIES: add and remove rows
    const amenContainer = document.getElementById('amenities-container');
    const amenTemplate  = document.getElementById('amen-template');
    const addAmenBtn    = document.getElementById('addamenBtn');

    if (addAmenBtn && amenContainer && amenTemplate) {
        addAmenBtn.addEventListener('click', function () {
            amenContainer.appendChild(amenTemplate.content.cloneNode(true));
        });
    }
    if (amenContainer) {
        amenContainer.addEventListener('click', function (e) {
            const btn = e.target.closest('.remove-amen-btn');
            if (btn) btn.closest('.amen-item').remove();
        });
    }
});
</script>