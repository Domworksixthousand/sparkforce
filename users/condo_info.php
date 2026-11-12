<?php
include 'my_property.php';

if (isset($_GET['id']) && isset($_GET['property_id'])) {
    $rent_id     = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    echo "<script>location.href='index.php';</script>";
    exit;
}

$json_flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

// 1. RENTSPACE DETAILS (including saved location)
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, rate,
           longitude, latitude, barangay, province, municipality
    FROM rentspace
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name         = "";
$price        = 0;
$image_cover  = "";
$other_info   = "";
$rate         = "";
$longitude    = "";
$latitude     = "";
$barangay     = "";
$municipality = "";
$province     = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name         = $rent_row['name'] ?? '';
    $price        = $rent_row['price'] ?? 0;
    $image_cover  = $rent_row['image_cover'] ?? '';
    $other_info   = $rent_row['other_info'] ?? '';
    $rate         = $rent_row['rate'] ?? '';
    $longitude    = $rent_row['longitude'] ?? '';
    $latitude     = $rent_row['latitude'] ?? '';
    $barangay     = $rent_row['barangay'] ?? '';
    $municipality = $rent_row['municipality'] ?? '';
    $province     = $rent_row['province'] ?? '';
}
$get_rent->close();

// LOCATION VALUES USED BY THE LOCATION TAB
$hasLocation = (
    is_numeric($latitude) && is_numeric($longitude) &&
    abs((float)$latitude) <= 90 && abs((float)$longitude) <= 180 &&
    !((float)$latitude == 0.0 && (float)$longitude == 0.0)
);
$map_lat  = $hasLocation ? (float)$latitude  : null;
$map_lng  = $hasLocation ? (float)$longitude : null;
$loc_brgy = (string)$barangay;
$loc_mun  = (string)$municipality;
$loc_prov = (string)$province;

// 2. CONDO UNITS & STATUS COUNTS
$condo_units     = [];
$availableCondos = 0;

$get_condo = $conn->prepare("SELECT * FROM `condo` WHERE `rent_id` = ?");
$get_condo->bind_param("s", $rent_id);
$get_condo->execute();
$result_execute = $get_condo->get_result();

while ($row_condo = $result_execute->fetch_assoc()) {
    $condo_units[] = $row_condo;

    if (strtolower($row_condo['status'] ?? '') === 'available') {
        $availableCondos++;
    }
}
$get_condo->close();

$totalCondos = count($condo_units);

// 3. GALLERY
$apartment_images = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

while ($row_gallery = $result_gallery->fetch_assoc()) {
    $apartment_images[] = $row_gallery['image'];
}
$get_gallery->close();

// 4. AMENITIES
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

// 5. REVIEWS / RATINGS
// LEFT JOIN accounts so a review is never hidden just because the account row can't be matched.
$get_reviews = $conn->prepare("
    SELECT r.rating, r.comment, r.date_sent, r.time_sent, a.firstname, a.lastname
    FROM `reviews` AS r
    LEFT JOIN `accounts` AS a ON a.user_id = r.user_id
    WHERE r.landlord_id = ?
    ORDER BY r.date_sent DESC, r.time_sent DESC
");
$get_reviews->bind_param("s", $landlord_id);
$get_reviews->execute();
$reviews_res = $get_reviews->get_result();

$reviews = [];
while ($row = $reviews_res->fetch_assoc()) {
    $reviews[] = $row;
}
$get_reviews->close();

// Helper function for status badges
function apartmentStatusBadge($status) {
    switch (strtolower($status ?? '')) {
        case 'available':
            return 'badge-success';
        case 'occupied':
            return 'badge-error';
        case 'out of order':
        case 'maintenance':
            return 'badge-warning';
        default:
            return 'badge-neutral';
    }
}
?>

<!-- Leaflet (map) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
    /* Tailwind preflight (img{max-width:100%}) breaks Leaflet tiles and markers. Undo it for the map only. */
    .condo-info-map .leaflet-container img,
    .condo-info-map img.leaflet-tile,
    .condo-info-map img.leaflet-marker-icon,
    .condo-info-map img.leaflet-marker-shadow {
        max-width: none !important;
        max-height: none !important;
    }
</style>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden">

    <!-- Close button -->
    <a
        href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>"
        class="btn btn-sm btn-circle bg-white/90 hover:bg-white border-none shadow-md absolute right-3 top-3 z-20">
        ✕
    </a>

    <!-- HERO -->
    <div class="relative w-full h-56 sm:h-64">
      <img
        src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>"
        alt="<?php echo htmlspecialchars($name); ?>"
        class="w-full h-full object-cover"
        onerror="this.src='../assets/uploads/default.jpg';" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
      <div class="absolute bottom-0 left-0 p-5 text-white">
        <h2 class="text-2xl font-bold drop-shadow-sm"><?php echo htmlspecialchars($name); ?></h2>
        <p class="text-lg font-semibold text-success-content">
          <span class="text-white">&#x20B1;<?php echo htmlspecialchars(number_format((float) $price, 2)); ?> <span class="text-sm font-normal opacity-80">/ <?php echo htmlspecialchars($rate); ?></span></span>
        </p>
      </div>
      <div class="absolute top-3 left-3 flex gap-2">
        <span class="badge badge-neutral bg-black/50 border-none text-white backdrop-blur-sm">
          <?php echo $totalCondos; ?> Unit<?php echo $totalCondos !== 1 ? 's' : ''; ?>
        </span>
        <span class="badge badge-success text-white border-none">
          <?php echo $availableCondos; ?> Available
        </span>
      </div>
    </div>

    <!-- TABS -->
    <div role="tablist" class="tabs tabs-bordered px-5 pt-3 bg-base-100">
      <a role="tab" class="tab tab-active room-tab" data-tab="overview">Overview</a>
      <a role="tab" class="tab room-tab" data-tab="units">Units (<?php echo $totalCondos; ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="gallery">Gallery (<?php echo count($apartment_images); ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="amenities">Amenities (<?php echo count($saved_amenities); ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="location">Location</a>
      <a role="tab" class="tab room-tab" data-tab="reviews">Reviews (<?php echo count($reviews); ?>)</a>
    </div>

    <!-- TAB CONTENT -->
    <div class="p-5 max-h-[50vh] overflow-y-auto">

      <!-- OVERVIEW -->
      <div class="tab-panel" data-panel="overview">
        <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-2">About this condo</h3>
        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
          <?php echo !empty($other_info) ? $other_info : 'No additional information provided.'; ?>
        </p>
      </div>

      <!-- CONDO UNITS -->
      <div class="tab-panel hidden" data-panel="units">
        <?php if (!empty($condo_units)): ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($condo_units as $condo): ?>
              <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
                <div class="flex items-start justify-between mb-2">
                  <div>
                    <h4 class="font-bold text-base text-gray-800">
                      <?php echo htmlspecialchars($condo['bedroom_type'] ?? 'Standard Unit'); ?>
                    </h4>
                    <p class="text-xs text-gray-500">
                      Unit ID: <?php echo htmlspecialchars($condo['condo_id'] ?? $condo['id'] ?? 'N/A'); ?>
                    </p>
                  </div>
                  <span class="badge <?php echo apartmentStatusBadge($condo['status'] ?? ''); ?> text-white">
                    <?php echo htmlspecialchars($condo['status'] ?? 'Available'); ?>
                  </span>
                </div>

                <!-- Unit Details from DB columns -->
                <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-base-200 text-xs text-gray-600">
                  <div>
                    <span class="font-semibold text-gray-700">Area:</span>
                    <?php echo htmlspecialchars($condo['square_area'] ?? 'N/A'); ?> sqm
                  </div>
                  <div>
                    <span class="font-semibold text-gray-700">Bathrooms:</span>
                    <?php echo htmlspecialchars($condo['bathrooms'] ?? 'N/A'); ?>
                  </div>
                  <div>
                    <span class="font-semibold text-gray-700">Condition:</span>
                    <?php echo htmlspecialchars($condo['cond_condition'] ?? 'N/A'); ?>
                  </div>
                  <div>
                    <span class="font-semibold text-gray-700">Flooring:</span>
                    <?php echo htmlspecialchars($condo['flooring'] ?? 'N/A'); ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No condo units registered for this property.</p>
        <?php endif; ?>
      </div>

      <!-- GALLERY -->
      <div class="tab-panel hidden" data-panel="gallery">
        <?php if (!empty($apartment_images)): ?>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php foreach ($apartment_images as $img): ?>
              <div class="h-32 bg-base-200 rounded-lg overflow-hidden border border-base-200">
                <img
                  src="../assets/uploads/<?php echo htmlspecialchars($img); ?>"
                  alt="Gallery Image"
                  class="w-full h-full object-cover">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No gallery images uploaded for this condo.</p>
        <?php endif; ?>
      </div>

      <!-- AMENITIES -->
      <div class="tab-panel hidden" data-panel="amenities">
        <?php if (!empty($saved_amenities)): ?>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($saved_amenities as $amen): ?>
              <span class="badge badge-outline badge-lg gap-2 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <?php echo htmlspecialchars($amen['amenity']); ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No amenities listed for this condo.</p>
        <?php endif; ?>
      </div>

      <!-- LOCATION -->
      <div class="tab-panel hidden" data-panel="location">
        <?php if ($hasLocation): ?>
          <div class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
              <div class="rounded-lg bg-base-200/60 border border-base-200 p-2">
                <p class="text-[10px] uppercase tracking-wider text-gray-400">Barangay</p>
                <p id="condo_addr_brgy" class="font-semibold text-gray-700 truncate"><?php echo htmlspecialchars($loc_brgy !== '' ? $loc_brgy : 'Looking up...'); ?></p>
              </div>
              <div class="rounded-lg bg-base-200/60 border border-base-200 p-2">
                <p class="text-[10px] uppercase tracking-wider text-gray-400">Municipality</p>
                <p id="condo_addr_mun" class="font-semibold text-gray-700 truncate"><?php echo htmlspecialchars($loc_mun !== '' ? $loc_mun : 'Looking up...'); ?></p>
              </div>
              <div class="rounded-lg bg-base-200/60 border border-base-200 p-2">
                <p class="text-[10px] uppercase tracking-wider text-gray-400">Province</p>
                <p id="condo_addr_prov" class="font-semibold text-gray-700 truncate"><?php echo htmlspecialchars($loc_prov !== '' ? $loc_prov : 'Looking up...'); ?></p>
              </div>
            </div>

            <p id="condo_addr_status" class="text-xs text-gray-500">
              Coordinates: <?php echo number_format($map_lat, 6); ?>, <?php echo number_format($map_lng, 6); ?>
            </p>

            <div id="condo_info_map" class="condo-info-map rounded-xl border border-base-200 w-full" style="height:320px;"></div>

            <div class="flex justify-end">
              <a target="_blank" rel="noopener"
                 href="https://www.openstreetmap.org/?mlat=<?php echo number_format($map_lat, 6, '.', ''); ?>&amp;mlon=<?php echo number_format($map_lng, 6, '.', ''); ?>#map=17/<?php echo number_format($map_lat, 6, '.', ''); ?>/<?php echo number_format($map_lng, 6, '.', ''); ?>"
                 class="btn btn-xs btn-outline">
                Open in OpenStreetMap
              </a>
            </div>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No location has been pinned for this condo yet.</p>
        <?php endif; ?>
      </div>

      <!-- REVIEWS -->
      <div class="tab-panel hidden" data-panel="reviews">
        <?php if (!empty($reviews)): ?>
          <?php foreach ($reviews as $rv): ?>
            <?php
              // Reviewer name (falls back to "Anonymous" if the account can't be found)
              $reviewer = trim(($rv['firstname'] ?? '') . ' ' . ($rv['lastname'] ?? ''));
              if ($reviewer === '') $reviewer = 'Anonymous';

              $initials = strtoupper(mb_substr($rv['firstname'] ?? '', 0, 1) . mb_substr($rv['lastname'] ?? '', 0, 1));
              if ($initials === '') $initials = 'A';
            ?>
            <div class="p-3 bg-base-200/50 rounded-xl border border-base-200 mb-3">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                  <?php echo htmlspecialchars($initials); ?>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-semibold text-gray-700"><?php echo htmlspecialchars($reviewer); ?></span>
                    <span class="text-xs text-gray-500"><?php echo htmlspecialchars(($rv['date_sent'] ?? '') . ' ' . ($rv['time_sent'] ?? '')); ?></span>
                  </div>
                  <div class="flex text-warning text-xs mb-1">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                      <span class="<?php echo $s <= (int)$rv['rating'] ? 'text-warning' : 'text-gray-300'; ?>">★</span>
                    <?php endfor; ?>
                  </div>
                  <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($rv['comment'] ?? ''); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No reviews yet.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

<script>
(function () {
    const hasLocation = <?php echo $hasLocation ? 'true' : 'false'; ?>;
    const lat = <?php echo $hasLocation ? json_encode($map_lat, $json_flags) : 'null'; ?>;
    const lng = <?php echo $hasLocation ? json_encode($map_lng, $json_flags) : 'null'; ?>;
    const fallback = {
        barangay:     <?php echo json_encode($loc_brgy, $json_flags); ?>,
        municipality: <?php echo json_encode($loc_mun, $json_flags); ?>,
        province:     <?php echo json_encode($loc_prov, $json_flags); ?>
    };

    // Same ZIP fallback used on the add pages
    const BH_ZIP_TO_MUN = {
        "4707": "Irosin", "4700": "Sorsogon City", "4701": "Bacon", "4702": "Casiguran",
        "4703": "Castilla", "4704": "Donsol", "4705": "Gubat", "4706": "Juban",
        "4708": "Magallanes", "4709": "Matnog", "4710": "Pilar", "4711": "Prieto Diaz",
        "4712": "Santa Magdalena", "4713": "Bulusan", "4714": "Barcelona", "4715": "Bulan"
    };

    let infoMap = null;
    let addressLoaded = false;

    function el(id) { return document.getElementById(id); }

    // Reverse geocode the saved coordinates (OpenStreetMap Nominatim)
    async function lookupAddress(lat, lng) {
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

    function showAddress(info, isFallback) {
        el('condo_addr_brgy').textContent = info.barangay     || '—';
        el('condo_addr_mun').textContent  = info.municipality || '—';
        el('condo_addr_prov').textContent = info.province     || '—';
        el('condo_addr_status').textContent = 'Coordinates: ' + lat.toFixed(6) + ', ' + lng.toFixed(6)
            + (isFallback ? ' (saved address)' : '');
    }

    function buildPopup(info) {
        const wrap = document.createElement('div');
        const title = document.createElement('b');
        title.textContent = info.barangay || 'Pinned location';
        const sub = document.createElement('div');
        sub.textContent = [info.municipality, info.province].filter(Boolean).join(', ') || 'No address found';
        const coords = document.createElement('div');
        coords.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
        wrap.append(title, sub, coords);
        return wrap;
    }

    async function initInfoMap() {
        if (!hasLocation || typeof L === 'undefined') return;

        if (!infoMap) {
            infoMap = L.map('condo_info_map').setView([lat, lng], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(infoMap);
        }

        setTimeout(function () { infoMap.invalidateSize(); }, 50);

        if (addressLoaded) return;
        addressLoaded = true;

        try {
            const info = await lookupAddress(lat, lng);
            const hasAny = info.barangay || info.municipality || info.province;
            const finalInfo = hasAny ? info : fallback;

            showAddress(finalInfo, !hasAny);

            infoMap.eachLayer(function (layer) {
                if (layer instanceof L.Marker) infoMap.removeLayer(layer);
            });
            L.marker([lat, lng]).addTo(infoMap).bindPopup(buildPopup(finalInfo)).openPopup();
        } catch (err) {
            console.warn('Reverse geocode failed:', err);
            showAddress(fallback, true);
            infoMap.eachLayer(function (layer) {
                if (layer instanceof L.Marker) infoMap.removeLayer(layer);
            });
            L.marker([lat, lng]).addTo(infoMap).bindPopup(buildPopup(fallback)).openPopup();
            addressLoaded = false; // allow retry next time the tab is opened
        }
    }

    // Tab switching (Overview / Units / Gallery / Amenities / Location / Reviews)
    document.querySelectorAll('.room-tab').forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            e.preventDefault();
            const target = this.dataset.tab;

            document.querySelectorAll('.room-tab').forEach(function (t) {
                t.classList.toggle('tab-active', t === tab);
            });
            document.querySelectorAll('.tab-panel').forEach(function (p) {
                p.classList.toggle('hidden', p.dataset.panel !== target);
            });

            if (target === 'location') initInfoMap();
        });
    });
})();
</script>