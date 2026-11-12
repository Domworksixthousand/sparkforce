<?php
include 'my_property.php';
if(isset($_GET['property_id']) && $_GET['id']){
    $landlord_id = $_GET['property_id'] ?? '';
    $rent_id = $_GET['id'] ?? '';
}else{
    header("location:index.php");
    exit;
}

//RENTSPACE DETAILS (including saved location)
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, rate,
           latitude, longitude, barangay, municipality, province
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
$rate = "";
$latitude = "";
$longitude = "";
$barangay = "";
$municipality = "";
$province = "";

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

// CONDO DETAILS
$get_apartment = $conn->prepare("SELECT * FROM `condo` WHERE `rent_id` = ?");
$get_apartment->bind_param("s", $rent_id);
$get_apartment->execute();
$result_execute = $get_apartment->get_result();
if($result_execute->num_rows > 0){
    while($row_apar = mysqli_fetch_assoc($result_execute)){
        $square_area    = $row_apar['square_area'];
        $bedroom_type   = $row_apar['bedroom_type'];
        $bathrooms      = $row_apar['bathrooms'];
        $flooring       = $row_apar['flooring'];
        $status         = $row_apar['status'];
        $cond_condition = $row_apar['cond_condition'];
    }
}

// CONDO IMAGES
$condo_images = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();
if($result_gallery->num_rows > 0){
    while($row_gallery = mysqli_fetch_assoc($result_gallery)){
        $condo_images[] = $row_gallery['image'];
    }
}

// AMENITIES (already saved for this rentspace)
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

$selected_amen_ids = empty($saved_amenities) ? [""] : array_column($saved_amenities, 'amen_id');
?>

<script src="https://cdn.tiny.cloud/1/ssew95wvyspsqckuynqjy38ov5ktrks0qbp94mxchgh1ucty/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />
<style>
    /* Tailwind preflight (img{max-width:100%;height:auto}) breaks Leaflet tiles and markers. Undo it for the map only. */
    .bh-map .leaflet-container img,
    .bh-map img.leaflet-tile,
    .bh-map img.leaflet-marker-icon,
    .bh-map img.leaflet-marker-shadow {
        max-width: none !important;
        max-height: none !important;
    }
    .bh-map .leaflet-top, .bh-map .leaflet-bottom { z-index: 400; }
    #bh_map_modal::backdrop { background: rgba(15, 23, 42, .6); }
</style>

<!-- Professional Modal Wrapper & Backdrop -->
<div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto animate-fade-in">

  <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all">

    <!-- Modal Header -->
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70 sticky top-0 z-10 backdrop-blur-md">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/>
            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-slate-800">Edit Condominium</h2>
          <p class="text-xs text-slate-500">Update condo unit details, pricing, photos, unit specs, and amenities.</p>
        </div>
      </div>

      <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
         class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-600 flex items-center justify-center transition-colors">
        ✕
      </a>
    </div>

    <!-- Modal Form Body -->
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-8" id="mainForm">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($rent_id); ?>">

        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Condominium Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Condominium Name / Number *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/>
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/>
                            </svg>
                        </span>
                        <input type="text"
                               class="autoInput w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="condo_name"
                               value="<?php echo htmlspecialchars($name ?? ''); ?>"
                               placeholder="Enter Name / Number"
                               required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Price *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none font-semibold text-sm">₱</span>
                        <input type="text"
                               class="numbers_only w-full pl-8 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="condo_price"
                               value="<?php echo htmlspecialchars($price ?? ''); ?>"
                               placeholder="Enter Price /Month"
                               required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Rate (Per Month/Night/Week/Hour) *</label>
                    <div class="relative flex items-center">
                        <input type="text"
                               class="autoInput w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="condo_rate"
                               value="<?php echo htmlspecialchars($rate ?? ''); ?>"
                               placeholder="Enter Rate"
                               required />
                    </div>
                </div>
            </div>

            <!-- Cover Photo -->
            <div class="space-y-1.5 pt-2" id="cover-section">
                <label class="block text-xs font-semibold text-slate-600">Cover Photo *</label>

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
                           name="apartment_cover"
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

            <!-- Other Information -->
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-semibold text-slate-600">Other Informations *</label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                    <textarea
                        id="myEditor"
                        name="apartment_other_info"><?php echo htmlspecialchars($other_info ?? ''); ?></textarea>
                </div>
            </div>
        </div>

        <!-- SECTION 2: CONDO LOCATION -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Condominium Location</h3>
                <button type="button" onclick="bhOpenMap()"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors">
                    Locate
                </button>
            </div>
            <p class="text-[11px] text-slate-400">Click Locate to search a place, click the map, or drag the pin. The address is taken from the pinned point.</p>

            <input type="hidden" id="lat" name="latitude" value="<?php echo htmlspecialchars($cur_lat); ?>">
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
                    : 'No location saved yet. Click Locate to pin this condominium.'; ?>
            </p>
        </div>

        <!-- SECTION 3: UNITS SPECIFICATION -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Units Specification</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Square Area *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                        </span>
                        <input type="text"
                               class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"
                               name="square_area"
                               value="<?php echo htmlspecialchars($square_area ?? ''); ?>"
                               placeholder="Enter Unit Square Area"
                               required />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Type *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="type" required>
                            <option value="" disabled <?php echo empty($bedroom_type) ? 'selected' : ''; ?>>Select Bedroom</option>
                            <option value="Studio" <?php echo (($bedroom_type ?? '') == 'Studio') ? 'selected' : ''; ?>>Studio</option>
                            <option value="1 Bed Room" <?php echo (($bedroom_type ?? '') == '1 Bed Room') ? 'selected' : ''; ?>>1 Bed Room</option>
                            <option value="2 Bed Room" <?php echo (($bedroom_type ?? '') == '2 Bed Room') ? 'selected' : ''; ?>>2 Bed Room</option>
                            <option value="3 Bed Room" <?php echo (($bedroom_type ?? '') == '3 Bed Room') ? 'selected' : ''; ?>>3 Bed Room</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Bathrooms *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m4 4 2.5 2.5"/><path d="M13.5 6.5a4.95 4.95 0 0 0-7 7"/><path d="M15 5 5 15"/><path d="M14 17v.01"/><path d="M10 16v.01"/><path d="M13 13v.01"/><path d="M16 10v.01"/><path d="M11 20v.01"/><path d="M17 14v.01"/><path d="M20 11v.01"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="bathrooms" required>
                            <option value="" disabled <?php echo empty($bathrooms) ? 'selected' : ''; ?>>Select Bathroom</option>
                            <option value="1 Bathroom" <?php echo (($bathrooms ?? '') == '1 Bathroom') ? 'selected' : ''; ?>>1 Bathroom</option>
                            <option value="2 Bathroom" <?php echo (($bathrooms ?? '') == '2 Bathroom') ? 'selected' : ''; ?>>2 Bathroom</option>
                            <option value="3 Bathroom" <?php echo (($bathrooms ?? '') == '3 Bathroom') ? 'selected' : ''; ?>>3 Bathroom</option>
                            <option value="4+ Bathroom" <?php echo (($bathrooms ?? '') == '4+ Bathroom') ? 'selected' : ''; ?>>4+ Bathroom</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Condition *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 21v-1"/><path d="M10 4V3"/><path d="M10 9a3 3 0 0 0 0 6"/><path d="m14 20 1.25-2.5L18 18"/><path d="m14 4 1.25 2.5L18 6"/><path d="m17 21-3-6 1.5-3H22"/><path d="m17 3-3 6 1.5 3"/><path d="M2 12h1"/><path d="m20 10-1.5 2 1.5 2"/><path d="m3.64 18.36.7-.7"/><path d="m4.34 6.34-.7-.7"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="condition" required>
                            <option value="" disabled <?php echo empty($cond_condition) ? 'selected' : ''; ?>>Select Condition</option>
                            <option value="Bare" <?php echo (($cond_condition ?? '') == 'Bare') ? 'selected' : ''; ?>>Bare</option>
                            <option value="Standard Finished" <?php echo (($cond_condition ?? '') == 'Standard Finished') ? 'selected' : ''; ?>>Standard Finished</option>
                            <option value="Furnished" <?php echo (($cond_condition ?? '') == 'Furnished') ? 'selected' : ''; ?>>Furnished</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Flooring *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M12 9v6"/><path d="M16 15v6"/><path d="M16 3v6"/><path d="M3 15h18"/><path d="M3 9h18"/><path d="M8 15v6"/><path d="M8 3v6"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="flooring" required>
                            <option value="" disabled <?php echo empty($flooring) ? 'selected' : ''; ?>>Select Flooring</option>
                            <option value="Tiles" <?php echo (($flooring ?? '') == 'Tiles') ? 'selected' : ''; ?>>Tiles</option>
                            <option value="Vinyl" <?php echo (($flooring ?? '') == 'Vinyl') ? 'selected' : ''; ?>>Vinyl</option>
                            <option value="Wood" <?php echo (($flooring ?? '') == 'Wood') ? 'selected' : ''; ?>>Wood</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Status *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </span>
                        <select class="w-full pl-10 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" name="status" required>
                            <option value="" disabled <?php echo empty($status) ? 'selected' : ''; ?>>Select Status</option>
                            <option value="Occupied" <?php echo (($status ?? '') == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                            <option value="Available" <?php echo (($status ?? '') == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Out of Order" <?php echo (($status ?? '') == 'Out of Order') ? 'selected' : ''; ?>>Out of Order</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Multiple Photos -->
            <div class="space-y-1.5 pt-2" id="gallery-section">
                <label class="block text-xs font-semibold text-slate-600">Multiple Photos (Upload 3 to 10 Photos) *</label>

                <?php if (!empty($condo_images)): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-2">
                        <?php foreach ($condo_images as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>"
                                     class="w-full h-16 object-cover rounded-lg border border-emerald-100">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex items-center gap-2 bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider shrink-0">Retained</span>
                        <span class="text-emerald-900"><?php echo count($condo_images); ?> photo(s) retained. Upload new photos below to replace them.</span>
                    </div>
                <?php endif; ?>

                <label id="gallery-label" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-200 border-dashed rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-50 hover:border-emerald-400 transition-all">
                    <div class="flex flex-col items-center justify-center pt-4 pb-4" id="gallery-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 mb-1">
                            <path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/>
                        </svg>
                        <p class="text-xs text-slate-500"><span class="font-semibold text-emerald-600">Click to upload</span> or drag and drop</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Select 3–10 photos (JPEG, PNG, JPG)</p>
                    </div>
                    <input type="file"
                           class="hidden"
                           id="gallery"
                           name="gallery[]"
                           accept="image/jpeg, image/png, image/jpg"
                           multiple
                           <?php echo !empty($condo_images) ? '' : 'required'; ?> />
                </label>

                <div id="gallery-preview-container" class="hidden mt-3 grid grid-cols-4 sm:grid-cols-5 gap-2"></div>
                <p class="text-[10px] text-slate-400 mt-1">Please select 3–10 photos.</p>
            </div>
        </div>

        <!-- SECTION 4: ROOM AMENITIES -->
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
            if (!empty($user_id_login)) {
                $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
                $get_amen->bind_param("ss", $user_id_login, $active);
            } else {
                $get_amen = $conn->prepare("SELECT * FROM amenities WHERE active=?");
                $get_amen->bind_param("s", $active);
            }
            $get_amen->execute();
            $result = $get_amen->get_result();

            $amenities = [];
            $amenity_ids_in_list = [];
            while ($row = $result->fetch_assoc()) {
                $amenities[] = $row;
                $amenity_ids_in_list[] = (string)$row['amen_id'];
            }

            // Make sure the room's already-saved amenities are always in the dropdown,
            // even if they are inactive / created by another user. Otherwise the select
            // shows "Select Amenity", submits empty, and the saved amenity gets dropped.
            foreach ($saved_amenities as $sv) {
                if (!in_array((string)$sv['amen_id'], $amenity_ids_in_list, true)) {
                    $amenities[] = ['amen_id' => $sv['amen_id'], 'amenity' => $sv['amenity']];
                    $amenity_ids_in_list[] = (string)$sv['amen_id'];
                }
            }

            // DIRECT mula sa DB; blangkong row lang kung wala pang saved
            $rows = !empty($saved_amenities)
                ? $saved_amenities
                : [['amen_id' => '', 'rent_amen_id' => '']];
            ?>

            <div id="amenities-container" class="space-y-2">
                <?php foreach ($rows as $saved) {
                    $selectedAmen = $saved['amen_id'];
                ?>
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <input type="hidden" name="rentspace_amenities_id[]" value="<?php echo htmlspecialchars($saved['rent_amen_id']); ?>">

                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            name="amenity[]">
                        <option value="" <?php echo $selectedAmen === '' ? 'selected' : ''; ?>>Select Amenity</option>
                        <?php foreach ($amenities as $amen) { ?>
                            <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>"
                                <?php echo ((string)$selectedAmen === (string)$amen['amen_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($amen['amenity']); ?>
                            </option>
                        <?php } ?>
                    </select>

                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <?php } ?>
            </div>

            <template id="amen-template">
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <input type="hidden" name="rentspace_amenities_id[]" value="">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            name="amenity[]">
                        <option value="" selected>Select Amenity</option>
                        <?php foreach ($amenities as $amen) { ?>
                            <option value="<?php echo htmlspecialchars($amen['amen_id']); ?>">
                                <?php echo htmlspecialchars($amen['amenity']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Submit -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white py-3 -mx-6 px-6">
            <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
               Cancel
            </a>
            <button type="submit" name="edit_condo"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save Changes
            </button>
        </div>
    </form>
  </div>
</div>

<!-- MAP MODAL -->
<dialog id="bh_map_modal" class="m-auto w-11/12 max-w-4xl p-0 rounded-2xl bg-white shadow-2xl">
  <div class="p-5 bg-white rounded-2xl">
    <div class="flex items-center justify-between mb-2">
      <div>
        <h3 class="font-bold text-slate-800">Pin Condominium Location</h3>
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
tinymce.init({
    selector: '#myEditor',
    height: 500,
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
        'Arial Black=arial black,avant garde;' +
        'Courier New=courier new,courier;' +
        'Georgia=georgia,palatino;' +
        'Helvetica=helvetica;' +
        'Impact=impact,chicago;' +
        'Tahoma=tahoma,arial,helvetica,sans-serif;' +
        'Times New Roman=times new roman,times;' +
        'Trebuchet MS=trebuchet ms,geneva;' +
        'Verdana=verdana,geneva;',

    fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt 72pt',
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Blockquote=blockquote; Code=pre',

    image_advtab: true,
    image_caption: true,
    image_title: true,
    automatic_uploads: true,
    file_picker_types: 'image media',
    images_upload_url: '../upload_image.php',
    images_upload_handler: function (blobInfo, success, failure) {
        var formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        fetch('../upload_image.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(result => result.location ? success(result.location) : failure('Upload failed: ' + result.error))
            .catch(error => failure('Upload error: ' + error));
    },

    table_default_attributes: { border: '1' },
    table_default_styles: { 'border-collapse': 'collapse', 'width': '100%' },
    table_responsive_width: true,
    table_column_resizing: 'resizetable',
    media_live_embeds: true,
    link_default_target: '_blank',
    link_assume_external_targets: true,
    link_context_toolbar: true,

    codesample_languages: [
        { text: 'HTML/XML', value: 'markup' },
        { text: 'JavaScript', value: 'javascript' },
        { text: 'CSS', value: 'css' },
        { text: 'PHP', value: 'php' },
        { text: 'Python', value: 'python' },
        { text: 'SQL', value: 'sql' },
    ],

    templates: [
        { title: 'Property Description', description: 'Template for property description', content: '<h2>Property Overview</h2><p>Enter property details here...</p><h3>Amenities</h3><ul><li>Amenity 1</li><li>Amenity 2</li></ul>' },
        { title: 'House Rules', description: 'Template for house rules', content: '<h2>House Rules</h2><ol><li>Rule 1</li><li>Rule 2</li><li>Rule 3</li></ol>' },
        { title: 'Room Details', description: 'Template for room details', content: '<h2>Room Details</h2><table border="1" style="width:100%;border-collapse:collapse;"><tr><th>Feature</th><th>Details</th></tr><tr><td>Size</td><td>Enter size</td></tr></table>' }
    ],

    quickbars_insert_toolbar: 'quickimage quicktable | hr pagebreak',
    quickbars_selection_toolbar: 'bold italic underline | formatselect | link blockquote',

    content_style: `
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #333; padding: 10px; }
        h1, h2, h3, h4, h5, h6 { color: #1a1a2e; margin-top: 1em; }
        table { border-collapse: collapse; width: 100%; }
        table td, table th { border: 1px solid #ddd; padding: 8px; }
        table th { background-color: #f2f2f2; font-weight: bold; }
        img { max-width: 100%; height: auto; }
        pre { background: #f4f4f4; border: 1px solid #ddd; padding: 10px; border-radius: 4px; }
        blockquote { border-left: 4px solid #10b981; margin: 0; padding-left: 16px; color: #555; }
    `,

    paste_data_images: true,
    paste_as_text: false,
    smart_paste: true,
    browser_spellcheck: true,
    wordcount_countcharacters: true,
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_restore_when_empty: true,
    autosave_retention: '2m',
    nonbreaking_force_tab: true,
    resize: 'both',
    statusbar: true,
    elementpath: true,

    setup: function (editor) {
        editor.on('change keyup', function () {
            editor.save();
        });
    },

    init_instance_callback: function (editor) {
        const content = <?php echo json_encode($other_info ?? ''); ?>;
        if (content) {
            editor.setContent(content);
        }
    }
});

// ============================================
// MAP (same behavior as the apartment pages)
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
    bhEl('loc_status').textContent = 'New location selected. Click Save Changes to save.';

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
                ? 'Location set. Click Done, then Save Changes to save.'
                : 'No address found for this point. Coordinates are still saved.'
        });
        bhPlaceMarker(lat, lng,
            info.barangay || 'Selected point',
            [info.municipality, info.province].filter(Boolean).join(', ') || 'No address found');
    }).catch(function (err) {
        if (reqId !== bhReqId) return;
        console.warn('Reverse geocode failed:', err);
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

    const mapDialog = document.getElementById('bh_map_modal');
    if (mapDialog) {
        mapDialog.addEventListener('click', function (e) {
            if (e.target === mapDialog) mapDialog.close();
        });
    }

    const form = document.getElementById('mainForm');
    if (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }

    // COVER PHOTO LIVE PREVIEW
    const coverInput = document.getElementById('cover');
    const coverPreviewContainer = document.getElementById('cover-preview-container');
    const coverPreviewImg = document.getElementById('cover-preview-img');
    const coverPreviewName = document.getElementById('cover-preview-name');
    const coverPreviewSize = document.getElementById('cover-preview-size');
    const coverLabel = document.getElementById('cover-label');

    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                coverPreviewImg.src = ev.target.result;
                coverPreviewName.textContent = file.name;
                coverPreviewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                coverPreviewContainer.classList.remove('hidden');
                coverLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');

                document.getElementById('cover-placeholder').innerHTML = `
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

    // GALLERY PHOTOS LIVE PREVIEW
    const galleryInput = document.getElementById('gallery');
    const galleryPreviewContainer = document.getElementById('gallery-preview-container');
    const galleryLabel = document.getElementById('gallery-label');

    if (galleryInput) {
        galleryInput.addEventListener('change', function (e) {
            const files = Array.from(e.target.files);
            if (!files.length) return;

            galleryPreviewContainer.innerHTML = '';
            galleryPreviewContainer.classList.remove('hidden');
            galleryLabel.classList.add('border-emerald-400', 'bg-emerald-50/30');

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    const wrap = document.createElement('div');
                    wrap.className = 'relative';
                    wrap.innerHTML = `<img src="${ev.target.result}" class="w-full h-16 object-cover rounded-lg border border-emerald-200">`;
                    galleryPreviewContainer.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });

            document.getElementById('gallery-placeholder').innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 mb-1">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <p class="text-xs text-emerald-600 font-semibold">${files.length} photo(s) selected!</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Click to change</p>
            `;
        });
    }
});
</script>