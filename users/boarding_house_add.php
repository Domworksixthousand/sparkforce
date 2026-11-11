<?php
include 'my_property.php';
if(isset($_GET['property_id'])){
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    header("location:index.php");
    exit;
}

// CURRENT PROPERTY LOCATION (landlord table, shared by all rooms of this property)
$loc_lat = $loc_lng = $loc_prov = $loc_mun = $loc_brgy = '';
$lq = $conn->prepare("SELECT latitude, longitude, province, municipality, barangay FROM landlord WHERE landlord_id = ?");
$lq->bind_param("s", $landlord_id);
$lq->execute();
if ($loc_row = $lq->get_result()->fetch_assoc()) {
    $loc_lat  = $loc_row['latitude']     ?? '';
    $loc_lng  = $loc_row['longitude']    ?? '';
    $loc_prov = $loc_row['province']     ?? '';
    $loc_mun  = $loc_row['municipality'] ?? '';
    $loc_brgy = $loc_row['barangay']     ?? '';
}
// If the form was sent back with an error, keep the newly pinned location
if (!empty($_SESSION['latitud'])) {
    $loc_lat  = $_SESSION['latitud'];
    $loc_lng  = $_SESSION['longitude']     ?? $loc_lng;
    $loc_prov = $_SESSION['province1']     ?? $loc_prov;
    $loc_mun  = $_SESSION['municipality1'] ?? $loc_mun;
    $loc_brgy = $_SESSION['barangay1']     ?? $loc_brgy;
}
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
          <h2 class="text-lg font-bold text-slate-800">Room Configuration</h2>
          <p class="text-xs text-slate-500">Manage room details, pricing, advanced editor tools, beds, and amenities.</p>
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

    <!-- Modal Form Body -->
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto space-y-8" id="mainForm">
        <input type="hidden" name="landlord_id" value="<?php echo htmlspecialchars($landlord_id); ?>">

        <!-- ROOM INFORMATION -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Room Information</h3>
            </div>  
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                               value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" 
                               placeholder="Enter Name / Number" 
                               required />
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Price *</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 pointer-events-none font-semibold text-sm">₱</span>
                        <input type="text" 
                               class="w-full pl-8 pr-3 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all numbers_only" 
                               name="price" 
                               value="<?php echo htmlspecialchars($_SESSION['price'] ?? ''); ?>" 
                               placeholder="Enter Price / Month" 
                               required />
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Rate Basis *</label>
                    <div class="relative flex items-center">
                        <input type="text" 
                               class="w-full px-3.5 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" 
                               name="board_rate" 
                               value="<?php echo htmlspecialchars($_SESSION['board_rate'] ?? ''); ?>" 
                               placeholder="e.g. Per Month / Per Night" 
                               required />
                    </div>
                </div>
            </div>
            <div class="space-y-1.5 pt-2" id="cover-section">
                <label class="block text-xs font-semibold text-slate-600">Cover Photo *</label>
                
                <!-- Retained session cover -->
                <?php if (!empty($_SESSION['fileName'])): ?>
                    <input type="hidden" name="old_cover" value="<?php echo htmlspecialchars($_SESSION['fileName']); ?>">
                    <div class="flex items-center justify-between bg-emerald-50/60 border border-emerald-100 p-2.5 rounded-xl text-xs mb-2">
                        <div class="flex items-center gap-3">
                            <img src="../assets/uploads/<?php echo htmlspecialchars($_SESSION['fileName']); ?>" 
                                 class="w-10 h-10 object-cover rounded-lg border border-white shadow-xs" 
                                 alt="Preview">
                            <span class="text-emerald-900">Previously selected: 
                                <strong class="underline font-medium"><?php echo htmlspecialchars($_SESSION['fileName']); ?></strong>
                            </span>
                        </div>
                        <span class="px-2 py-1 bg-emerald-600 text-white rounded-md text-[10px] font-bold uppercase tracking-wider">Retained</span>
                    </div>
                <?php endif; ?>

                <!-- File upload dropzone -->
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
                           <?php echo !empty($_SESSION['fileName']) ? '' : 'required'; ?> />
                </label>

                <!-- Preview container (shown after file selected) -->
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
            <div class="space-y-1.5 pt-2">
                <label class="block text-xs font-semibold text-slate-600">Other Informations *</label>
                <div class="border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500/20 focus-within:border-emerald-500 transition-all">
                    <textarea 
                        id="myEditor" 
                        name="other_info"><?php echo $_SESSION['other_info'] ?? ''; ?></textarea>
                </div>
            </div>
        </div>

        <!-- PROPERTY LOCATION -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Property Location</h3>
                <button type="button" onclick="bhOpenMap()"
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/></svg>
                    Locate
                </button>
            </div>
            <p class="text-[11px] text-slate-400">Pin the property on the map. This location applies to the whole property (all rooms).</p>

            <input type="hidden" id="lat" name="latitude" value="<?php echo htmlspecialchars($loc_lat); ?>">
            <input type="hidden" id="lng" name="longitude" value="<?php echo htmlspecialchars($loc_lng); ?>">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Province</label>
                    <input type="text" id="province1" name="province1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($loc_prov); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Municipality</label>
                    <input type="text" id="municipality1" name="municipality1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($loc_mun); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-600">Barangay</label>
                    <input type="text" id="barangay1" name="barangay1" readonly placeholder="Pin on map"
                           value="<?php echo htmlspecialchars($loc_brgy); ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-800" />
                </div>
            </div>
        </div>

        <!-- BEDS -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Beds Information</h3>
                <button type="button" id="addBedBtn" 
                        class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-lg transition-colors flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Bed
                </button>
            </div>

            <div id="beds" class="space-y-4">
                <?php
                $beds = $_SESSION['beds'] ?? [[
                    'bed_number' => 'Bed 1',
                    'num_deck'   => '',
                    'bed_image'  => ''
                ]];

                foreach ($beds as $bed):
                ?>
                <div class="bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative group">
  
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                            <input type="text" name="bednum[]" 
                                   class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none" 
                                   value="<?php echo htmlspecialchars($bed['bed_number']); ?>" readonly>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Number of Deck</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" 
                                    name="num_deck[]" required>
                                <option value="" disabled selected>Select Number of Deck</option>
                                <option value="1" <?= ($bed['num_deck'] ?? '') == 1 ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= ($bed['num_deck'] ?? '') == 2 ? 'selected' : '' ?>>2</option>
                                <option value="3" <?= ($bed['num_deck'] ?? '') == 3 ? 'selected' : '' ?>>3</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Bed Image</label>
                        <?php if (!empty($bed['bed_image'])): ?>
                            <input type="hidden" name="old_image[]" value="<?php echo htmlspecialchars($bed['bed_image']); ?>">
                            <div class="flex items-center justify-between bg-emerald-50/50 border border-emerald-100 p-2 rounded-lg text-xs mb-2">
                                <span class="text-emerald-900 truncate">Previously: <strong><?php echo htmlspecialchars($bed['bed_image']); ?></strong></span>
                                <span class="text-[10px] bg-emerald-600 text-white px-1.5 py-0.5 rounded shrink-0">Retained</span>
                            </div>
                        <?php endif; ?>
                        <input type="file" 
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer bg-white border border-slate-200 rounded-lg p-1" 
                               name="image[]" 
                               accept="image/jpeg,image/jpg" 
                               <?php echo empty($bed['bed_image']) ? 'required' : ''; ?>>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- AMENITIES -->
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
            $get_amen = $conn->prepare("SELECT * FROM amenities WHERE user_id=? AND active=?");
            $get_amen->bind_param("ss", $user_id_login, $active);
            $get_amen->execute();
            $result = $get_amen->get_result();

            $amenities = [];
            while ($row = $result->fetch_assoc()) {
                $amenities[] = $row;
            }

            // local variable lang, huwag i-overwrite ang session
            $selectedList = !empty($_SESSION['amenity']) ? $_SESSION['amenity'] : [""];
            ?>

            <!-- ROWS -->
            <div id="amenities-container" class="space-y-2">
                <?php foreach ($selectedList as $selectedAmen) { ?>
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            name="amenity[]">
                        <option value="" disabled <?php echo empty($selectedAmen) ? 'selected' : ''; ?>>Select Amenity</option>
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

            <!-- TEMPLATE -->
            <template id="amen-template">
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                            name="amenity[]">
                        <option value="" disabled selected>Select Amenity</option>
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

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3 sticky bottom-0 bg-white py-3 -mx-6 px-6">
            <a href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>" 
               class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
               Cancel
            </a>
            <button type="submit" name="save_boarding" 
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Save
            </button>
        </div>
    </form>
  </div>
</div>

<!-- MAP MODAL -->
<dialog id="bh_map_modal" class="modal">
  <div class="modal-box w-11/12 max-w-4xl p-5 bg-white">
    <div class="flex items-center justify-between mb-2">
      <div>
        <h3 class="font-bold text-slate-800">Pin Property Location</h3>
        <p class="text-xs text-slate-500">Search a place, or click the map to select a point. You can also drag the pin.</p>
      </div>
      <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="document.getElementById('bh_map_modal').close()">✕</button>
    </div>

    <div id="bh_map_wrap" class="bh-map">
      <div id="bh_map" style="height:440px;width:100%;" class="rounded-xl border border-slate-200"></div>
    </div>

    <!-- Selected location (updates on every pick) -->
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
      <button type="button" class="btn btn-success btn-sm text-white" onclick="document.getElementById('bh_map_modal').close()">Done</button>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop"><button>close</button></form>
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
        <?php if (!empty($_SESSION['other_info'])): ?>
        editor.setContent(`<?php echo addslashes($_SESSION['other_info']); ?>`);
        <?php endif; ?>
    }
});

// ============================================
// MAP (self-contained)
// ============================================
const BH_DEFAULT_CENTER = [12.7036, 124.0390]; // Irosin, Sorsogon

// Fallback: Philippine ZIP -> municipality (used when OSM has no city/town)
const BH_ZIP_TO_MUN = {
    "4707": "Irosin", "4700": "Sorsogon City", "4701": "Bacon", "4702": "Casiguran",
    "4703": "Castilla", "4704": "Donsol", "4705": "Gubat", "4706": "Juban",
    "4708": "Magallanes", "4709": "Matnog", "4710": "Pilar", "4711": "Prieto Diaz",
    "4712": "Santa Magdalena", "4713": "Bulusan", "4714": "Barcelona", "4715": "Bulan"
};

let bhMap = null;
let bhMarker = null;
let bhReqId = 0; // ignores old address lookups when the user picks again quickly

function bhEl(id) { return document.getElementById(id); }

// Update the "Selected location" panel inside the modal
function bhRenderSel(v) {
    bhEl('bh_sel_brgy').textContent  = v.barangay     || '—';
    bhEl('bh_sel_mun').textContent   = v.municipality || '—';
    bhEl('bh_sel_prov').textContent  = v.province     || '—';
    bhEl('bh_sel_coord').textContent = (v.lat != null && v.lng != null)
        ? v.lat.toFixed(6) + ', ' + v.lng.toFixed(6) : '—';
    bhEl('bh_sel_status').textContent = v.status || '';
}

// Popup content built with textContent (no HTML injection)
function bhPopupEl(title, sub) {
    const d = document.createElement('div');
    const b = document.createElement('b');
    b.textContent = title;
    const s = document.createElement('div');
    s.textContent = sub;
    d.append(b, s);
    return d;
}

// Show / move the marker at the selected point
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

// Reverse geocode (OpenStreetMap Nominatim)
async function bhReverse(lat, lng) {
    const url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&addressdetails=1&zoom=18'
              + '&lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lng);
    const r = await fetch(url, { headers: { 'Accept-Language': 'en' } });
    if (!r.ok) throw new Error('HTTP ' + r.status);
    const j = await r.json();
    const a = j.address || {};

    let municipality = a.city || a.town || a.municipality || a.county || a.district || '';
    if (!municipality && a.postcode && BH_ZIP_TO_MUN[a.postcode]) {
        municipality = BH_ZIP_TO_MUN[a.postcode];
    }
    if (!municipality && j.display_name) {
        const parts = j.display_name.split(',').map(s => s.trim());
        if (parts.length >= 3) municipality = parts[1];
    }

    return {
        barangay:     a.neighbourhood || a.suburb || a.village || a.quarter || a.hamlet || '',
        municipality: municipality,
        province:     a.province || a.state || a.region || ''
    };
}

// Called on every map click, search result, or marker drag
function bhPick(lat, lng) {
    bhEl('lat').value = lat.toFixed(7);
    bhEl('lng').value = lng.toFixed(7);

    const reqId = ++bhReqId;
    bhPlaceMarker(lat, lng, 'Selected point', 'Looking up address...');
    bhRenderSel({ lat: lat, lng: lng, status: 'Looking up address...' });

    bhReverse(lat, lng).then(function (info) {
        if (reqId !== bhReqId) return; // a newer pick already happened

        bhEl('barangay1').value     = info.barangay;
        bhEl('municipality1').value = info.municipality;
        bhEl('province1').value     = info.province;

        const hasAddr = info.barangay || info.municipality || info.province;
        const msg = hasAddr
            ? 'Location set. Click Done to close.'
            : 'No address found for this point. Coordinates are still saved.';

        bhRenderSel({
            lat: lat, lng: lng,
            barangay: info.barangay, municipality: info.municipality, province: info.province,
            status: msg
        });
        bhPlaceMarker(lat, lng,
            info.barangay || 'Selected point',
            [info.municipality, info.province].filter(Boolean).join(', ') || 'No address found');
    }).catch(function (err) {
        if (reqId !== bhReqId) return;
        console.warn('Reverse geocode failed:', err);
        bhEl('barangay1').value = '';
        bhEl('municipality1').value = '';
        bhEl('province1').value = '';
        bhRenderSel({ lat: lat, lng: lng, status: 'Address lookup failed. Coordinates are still saved.' });
    });
}

function bhOpenMap() {
    if (typeof L === 'undefined') {
        alert('Map failed to load. Check your internet connection and refresh the page.');
        return;
    }
    bhEl('bh_map_modal').showModal();
    // Create/resize only AFTER the dialog is visible
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

        // Click anywhere on the map to select a point
        bhMap.on('click', function (e) {
            bhPick(e.latlng.lat, e.latlng.lng);
        });

        // Search box (leaflet-geosearch), biased to the Philippines
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

    // Show the saved point (edit / re-open) without changing the saved address
    if (hasSaved) {
        const brgy = bhEl('barangay1').value, mun = bhEl('municipality1').value, prov = bhEl('province1').value;
        bhPlaceMarker(savedLat, savedLng, brgy || 'Saved location',
            [mun, prov].filter(Boolean).join(', ') || 'Saved coordinates');
        bhRenderSel({ lat: savedLat, lng: savedLng, barangay: brgy, municipality: mun, province: prov,
                      status: 'Saved location. Click the map to change it.' });
    } else {
        bhRenderSel({ status: 'No location selected yet. Click the map to select.' });
    }

    // Make sure Leaflet measures the visible container
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

    // TinyMCE sync before form submit
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
    const previewContainer = document.getElementById('cover-preview-container');
    const previewImg = document.getElementById('cover-preview-img');
    const previewName = document.getElementById('cover-preview-name');
    const previewSize = document.getElementById('cover-preview-size');
    const coverLabel = document.getElementById('cover-label');

    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                previewImg.src = ev.target.result;
                previewName.textContent = file.name;
                previewSize.textContent = (file.size / 1024).toFixed(1) + ' KB';

                previewContainer.classList.remove('hidden');
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
});
</script>