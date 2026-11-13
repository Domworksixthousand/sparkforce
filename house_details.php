<?php
include 'config.php';

$rent_id = $_GET['id'] ?? null;

$name = $price = $image_cover = $other_info = $landlord_id = $user_id = $type = $rate = "";
$longitude = $latitude = $province = $municipality = $barangay = "";
$condo_units = [];
$availableCondos = 0;
$apartment_images = [];
$saved_amenities = [];
$house_id = $area = $house_type = $bedroom = $bathrooms = $flooring = $parking = "";

if ($rent_id) {
    // 1. RENTSPACE DETAILS (includes saved location)
    $get_rent = $conn->prepare("
        SELECT name, price, image_cover, other_info, landlord_id, user_id, type, rate,
               longitude, latitude, province, barangay, municipality
        FROM rentspace
        WHERE rent_id = ?
    ");
    $get_rent->bind_param("s", $rent_id);
    $get_rent->execute();
    $rent_res = $get_rent->get_result();

    if ($rent_row = $rent_res->fetch_assoc()) {
        $name         = $rent_row['name'] ?? '';
        $price        = $rent_row['price'] ?? '';
        $image_cover  = $rent_row['image_cover'] ?? '';
        $other_info   = $rent_row['other_info'] ?? '';
        $landlord_id  = $rent_row['landlord_id'] ?? '';
        $user_id      = $rent_row['user_id'] ?? '';
        $type         = $rent_row['type'] ?? '';
        $rate         = $rent_row['rate'] ?? '';
        $longitude    = $rent_row['longitude'] ?? '';
        $latitude     = $rent_row['latitude'] ?? '';
        $province     = $rent_row['province'] ?? '';
        $municipality = $rent_row['municipality'] ?? '';
        $barangay     = $rent_row['barangay'] ?? '';
    }
    $get_rent->close();

    // 2. HOUSE DETAILS
    $get_house = $conn->prepare("SELECT * FROM `house` WHERE `rent_id` = ?");
    $get_house->bind_param("s", $rent_id);
    $get_house->execute();
    $result_execute_house = $get_house->get_result();

    if ($result_execute_house->num_rows > 0) {
        if ($row_h = $result_execute_house->fetch_assoc()) {
            $house_id   = $row_h['house_id'] ?? '';
            $area       = $row_h['area'] ?? '';
            $house_type = $row_h['type'] ?? '';
            $bedroom    = $row_h['bedroom'] ?? '';
            $bathrooms  = $row_h['bathrooms'] ?? '';
            $flooring   = $row_h['flooring'] ?? '';
            $parking    = $row_h['parking'] ?? '';
        }
    }
    $get_house->close();

    // 3. GALLERY
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
    while ($row = $amen_res->fetch_assoc()) {
        $saved_amenities[] = [
            "amen_id"      => $row['amen_id'],
            "amenity"      => $row['amenity'],
            "rent_amen_id" => $row['rent_amen_id']
        ];
    }
    $get_amen->close();
}

// MAP COORDINATES (valid only if numeric, in range, and not 0,0)
$lat_val = (is_numeric($latitude)  && $latitude  !== '') ? (float)$latitude  : null;
$lng_val = (is_numeric($longitude) && $longitude !== '') ? (float)$longitude : null;
$has_coords = (
    $lat_val !== null && $lng_val !== null
    && abs($lat_val) <= 90 && abs($lng_val) <= 180
    && !($lat_val == 0.0 && $lng_val == 0.0)
);

$address_text = implode(', ', array_filter([$barangay, $municipality, $province]));

// Font Awesome stars (full / half / empty)
function renderFaStars($avg) {
    $html = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($avg >= $i) {
            $html .= '<i class="fa-solid fa-star"></i>';
        } elseif ($avg >= $i - 0.5) {
            $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
        } else {
            $html .= '<i class="fa-regular fa-star"></i>';
        }
    }
    return $html;
}

// 5. LANDLORD DETAILS (property name only; location comes from rentspace)
$property_name = "";
if ($landlord_id) {
    $get_landlord_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
    $get_landlord_info->bind_param("s", $landlord_id);
    $get_landlord_info->execute();
    $result_landlord = $get_landlord_info->get_result();
    if ($result_landlord->num_rows > 0 && $row_landlord = $result_landlord->fetch_assoc()) {
        $property_name = $row_landlord['property_name'] ?? '';
    }
    $get_landlord_info->close();
}

// 6. ACCOUNT DETAILS
$contact_number = $email = "";
if ($user_id) {
    $account = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
    $account->bind_param("s", $user_id);
    $account->execute();
    $result_account = $account->get_result();
    if ($result_account->num_rows > 0 && $row_account = $result_account->fetch_assoc()) {
        $contact_number = $row_account['contact_number'] ?? '';
        $email          = $row_account['email'] ?? '';
    }
    $account->close();
}

// 6.5 RATINGS & COMMENTS (display only, from database)
$avg_rating   = 0;
$review_count = 0;
$star_counts  = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$reviews      = [];

if ($landlord_id) {
    $get_avg = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM `reviews` WHERE `landlord_id` = ?");
    $get_avg->bind_param("s", $landlord_id);
    $get_avg->execute();
    $avg_row      = $get_avg->get_result()->fetch_assoc();
    $avg_rating   = (float)($avg_row['avg_rating'] ?? 0);
    $review_count = (int)($avg_row['review_count'] ?? 0);
    $get_avg->close();

    // star distribution
    $get_dist = $conn->prepare("SELECT rating, COUNT(*) AS c FROM `reviews` WHERE `landlord_id` = ? GROUP BY rating");
    $get_dist->bind_param("s", $landlord_id);
    $get_dist->execute();
    $dist_res = $get_dist->get_result();
    while ($d = $dist_res->fetch_assoc()) {
        $r = (int)$d['rating'];
        if (isset($star_counts[$r])) $star_counts[$r] = (int)$d['c'];
    }
    $get_dist->close();

    // latest reviews
    $get_reviews = $conn->prepare("
        SELECT acc.firstname, acc.middlename, acc.lastname, acc.suffix, acc.username,
               rv.rating, rv.comment, rv.date_sent, rv.time_sent, rv.review_id
        FROM `reviews` AS rv
        LEFT JOIN `accounts` AS acc ON acc.user_id = rv.user_id
        WHERE rv.landlord_id = ?
        ORDER BY rv.review_id DESC
        LIMIT 20
    ");
    $get_reviews->bind_param("s", $landlord_id);
    $get_reviews->execute();
    $reviews_res = $get_reviews->get_result();

    while ($rv = $reviews_res->fetch_assoc()) {
        $reviewer = trim(implode(' ', array_filter([
            $rv['lastname']   ?? '',
            $rv['firstname']  ?? '',
            $rv['middlename'] ?? '',
            $rv['suffix']     ?? '',
        ])));
        if ($reviewer === '') {
            $reviewer = $rv['username'] ?? 'Anonymous';
        }

        $parts    = preg_split('/\s+/', trim($reviewer));
        $initials = strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));

        $ts   = strtotime($rv['date_sent'] ?? '');
        $date = $ts ? date('M j, Y', $ts) : ($rv['date_sent'] ?? '');

        $reviews[] = [
            'name'     => $reviewer,
            'initials' => $initials,
            'date'     => $date,
            'rating'   => (int)$rv['rating'],
            'comment'  => $rv['comment'] ?? '',
        ];
    }
    $get_reviews->close();
}

$is_fav = false;
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title><?= htmlspecialchars(($property_name ? $property_name . ' - ' : '') . $name); ?></title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon">

    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        /* Leaflet tiles/markers: undo Tailwind preflight img{max-width:100%} */
        #map img.leaflet-tile,
        #map img.leaflet-marker-icon,
        #map img.leaflet-marker-shadow {
            max-width: none !important;
            max-height: none !important;
        }
    </style>
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">

<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />

  <div class="drawer-content flex flex-col">

    <header class="flex items-center py-3 px-4 bg-base-100 shadow-sm lg:hidden sticky top-0 z-30">
      <div class="flex-none">
        <label for="my-drawer-3" class="btn btn-square btn-ghost btn-sm">
          <i class="fa-solid fa-bars text-lg"></i>
        </label>
      </div>
      <div class="flex-1 ml-2">
        <span class="font-bold text-base truncate block"><?= htmlspecialchars($property_name ?: 'Property Details'); ?></span>
      </div>
      <a href="properties.php" class="btn btn-ghost btn-circle btn-sm">
        <i class="fa-solid fa-arrow-left text-base"></i>
      </a>
    </header>

    <main class="p-4 md:p-8 max-w-7xl mx-auto w-full space-y-6">

      <!-- Top Action Bar -->
      <div class="flex justify-end items-end">
        <a href="index.php"><img src="assets/images/back.png" alt="Back"></a>
      </div>

      <!-- Main Property Header Card -->
      <div class="bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Image Cover -->
          <div class="lg:col-span-2">
            <div class="relative h-64 sm:h-80 md:h-[380px] w-full rounded-xl overflow-hidden bg-base-300 shadow-inner group">
              <img src="assets/uploads/<?= htmlspecialchars($image_cover); ?>"
                   alt="<?= htmlspecialchars($name); ?>"
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                   onerror="this.src='assets/images/placeholder.jpg';" />

              <div class="absolute top-4 left-4 z-10">
                <span class="badge badge-success text-white badge-md md:badge-lg gap-2 shadow-md">
                  <i class="fa-solid fa-house text-xs"></i> <?= htmlspecialchars(ucfirst($house_type ?: $type ?: 'House')); ?>
                </span>
              </div>
            </div>
          </div>

          <!-- Quick Overview Card -->
          <div class="flex flex-col justify-between space-y-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-extrabold text-base-content">
                <?= htmlspecialchars(mb_strlen($name) > 22 ? mb_substr($name, 0, 22) . '...' : $name); ?>
              </h1>

              <!-- rating summary (jumps to reviews) -->
              <a href="#reviews" class="flex items-center gap-2 mt-1">
                <span class="flex text-warning text-sm gap-0.5"><?= renderFaStars($avg_rating); ?></span>
                <span class="text-sm font-semibold"><?= $review_count ? number_format($avg_rating, 1) : 'No ratings'; ?></span>
                <?php if ($review_count): ?>
                  <span class="text-xs text-base-content/60">(<?= $review_count; ?>)</span>
                <?php endif; ?>
              </a>

              <p class="text-xs md:text-sm text-base-content/70 mt-2 flex items-start gap-1.5">
                <i class="fa-solid fa-location-dot text-error mt-0.5"></i>
                <span><?= htmlspecialchars($address_text !== '' ? $address_text : 'Location unavailable'); ?></span>
              </p>

              <div class="divider my-4"></div>

              <div class="bg-success/10 p-4 rounded-xl border border-success/20">
                <span class="text-xs font-semibold text-success uppercase tracking-wider block">Rental Rent</span>
                <div class="text-3xl font-black text-success mt-1">
                  ₱<?= number_format((float) $price, 2); ?> <span class="text-xs font-normal text-base-content/70">/ <?= htmlspecialchars($rate); ?></span>
                </div>
              </div>

              <!-- Specs Overview Grid -->
              <div class="grid grid-cols-2 gap-2 mt-4">
                <?php if (!empty($area)): ?>
                  <div class="bg-base-200/70 p-2.5 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-ruler-combined text-success text-sm"></i>
                    <div>
                      <p class="text-[10px] text-base-content/60 uppercase font-semibold">Area</p>
                      <p class="text-xs font-bold"><?= htmlspecialchars($area); ?> sqm</p>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($bedroom)): ?>
                  <div class="bg-base-200/70 p-2.5 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-bed text-success text-sm"></i>
                    <div>
                      <p class="text-[10px] text-base-content/60 uppercase font-semibold">Bedrooms</p>
                      <p class="text-xs font-bold"><?= htmlspecialchars($bedroom); ?></p>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($bathrooms)): ?>
                  <div class="bg-base-200/70 p-2.5 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-bath text-success text-sm"></i>
                    <div>
                      <p class="text-[10px] text-base-content/60 uppercase font-semibold">Bathrooms</p>
                      <p class="text-xs font-bold"><?= htmlspecialchars($bathrooms); ?></p>
                    </div>
                  </div>
                <?php endif; ?>

                <?php if (!empty($parking)): ?>
                  <div class="bg-base-200/70 p-2.5 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-square-parking text-success text-sm"></i>
                    <div>
                      <p class="text-[10px] text-base-content/60 uppercase font-semibold">Parking</p>
                      <p class="text-xs font-bold"><?= htmlspecialchars($parking); ?></p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Contact Box -->
            <div class="p-4 bg-base-200/50 rounded-xl border border-base-300 space-y-2">
              <p class="text-[11px] font-bold text-base-content/60 uppercase tracking-wider">Property Contact</p>
              <p class="text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-phone text-success text-xs"></i> <?= htmlspecialchars($contact_number ?: 'N/A'); ?>
              </p>
              <p class="text-sm font-semibold flex items-center gap-2 truncate">
                <i class="fa-solid fa-envelope text-success text-xs"></i> <?= htmlspecialchars($email ?: 'N/A'); ?>
              </p>
            </div>
          </div>

        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

          <!-- Property Info -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Property
            </h2>
            <p class="text-base-content/80 text-sm leading-relaxed whitespace-pre-line">
              <?= $other_info ? $other_info : 'No description available for this space.'; ?>
            </p>
          </div>

          <!-- Amenities -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <i class="fa-solid fa-square-check text-success"></i> Features & Amenities
            </h2>
            <?php if (!empty($saved_amenities)): ?>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($saved_amenities as $amen): ?>
                  <span class="badge badge-lg gap-2 p-3 bg-base-200 text-base-content font-medium border-base-300">
                    <i class="fa-solid fa-check text-success text-xs"></i>
                    <?= htmlspecialchars($amen['amenity']); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <p class="text-sm text-base-content/50 italic">No specific amenities listed.</p>
            <?php endif; ?>
          </div>

          <!-- Specs Table Details -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <i class="fa-solid fa-house-chimney text-success"></i> Property Details
            </h2>

            <?php if ($house_id): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="border border-base-300 rounded-xl p-4 flex flex-col justify-between bg-base-100 gap-3">
                  <div class="flex items-center justify-between gap-2">
                    <h3 class="font-bold text-base truncate"><?= htmlspecialchars($name); ?></h3>
                    <span class="badge badge-sm badge-success text-white">
                      <?= htmlspecialchars($house_type ?: 'House'); ?>
                    </span>
                  </div>

                  <div class="grid grid-cols-2 gap-2 text-xs text-base-content/80 bg-base-200/50 p-2.5 rounded-lg border border-base-200">
                    <?php if (!empty($area)): ?>
                      <div class="flex items-center gap-1.5" title="Square Area">
                        <i class="fa-solid fa-ruler-combined text-success text-[11px]"></i>
                        <span><?= htmlspecialchars($area); ?> sqm</span>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($bedroom)): ?>
                      <div class="flex items-center gap-1.5" title="Bedrooms">
                        <i class="fa-solid fa-bed text-success text-[11px]"></i>
                        <span><?= htmlspecialchars($bedroom); ?></span>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($bathrooms)): ?>
                      <div class="flex items-center gap-1.5" title="Bathrooms">
                        <i class="fa-solid fa-bath text-success text-[11px]"></i>
                        <span><?= htmlspecialchars($bathrooms); ?></span>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($parking)): ?>
                      <div class="flex items-center gap-1.5" title="Parking">
                        <i class="fa-solid fa-square-parking text-success text-[11px]"></i>
                        <span><?= htmlspecialchars($parking); ?></span>
                      </div>
                    <?php endif; ?>

                    <?php if (!empty($flooring)): ?>
                      <div class="flex items-center gap-1.5 col-span-2" title="Flooring">
                        <i class="fa-solid fa-layer-group text-success text-[11px]"></i>
                        <span><?= htmlspecialchars($flooring); ?> Flooring</span>
                      </div>
                    <?php endif; ?>
                  </div>

                  <div class="pt-2 border-t border-base-200 flex items-center justify-between">
                    <span class="text-xs text-base-content/60">Rent Rate:</span>
                    <p class="text-xs font-semibold text-success">
                      ₱<?= number_format((float)$price, 2); ?> <span class="font-normal text-base-content/60">/ <?= htmlspecialchars($rate); ?></span>
                    </p>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="text-center py-8 text-base-content/50">
                <i class="fa-solid fa-house-circle-xmark text-3xl mb-2 block"></i>
                No house specifications available.
              </div>
            <?php endif; ?>
          </div>

        </div>

        <!-- Location Sidebar Widget -->
        <div class="space-y-6">
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 sticky top-6">
            <h2 class="text-lg font-bold mb-2 flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-success"></i> Location Map
            </h2>
            <p class="text-xs text-base-content/60 mb-3">
              <?= htmlspecialchars($address_text !== '' ? $address_text : 'No address saved'); ?>
            </p>
            <div id="map" class="w-full rounded-xl border border-base-300 bg-base-200 relative z-0"
                 style="height:288px; min-height:288px;"></div>
          </div>
        </div>

      </div>

      <!-- RATINGS & COMMENTS -->
      <div id="reviews" class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 space-y-6 scroll-mt-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-base-200">
          <div>
            <h2 class="text-lg font-bold flex items-center gap-2">
              <i class="fa-solid fa-star text-warning"></i> Ratings & Reviews
            </h2>
            <p class="text-xs text-base-content/60">See what past visitors say about this space</p>
          </div>
          <span class="badge badge-success badge-outline font-semibold">
            <?= $review_count; ?> Review<?= $review_count === 1 ? '' : 's'; ?>
          </span>
        </div>

        <!-- Summary: average + breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="flex flex-col items-center justify-center bg-base-200/60 border border-base-300 rounded-xl p-6">
            <div class="text-5xl font-black text-warning"><?= $review_count ? number_format($avg_rating, 1) : '0.0'; ?></div>
            <div class="flex text-warning text-lg gap-0.5 mt-2"><?= renderFaStars($avg_rating); ?></div>
            <p class="text-xs text-base-content/60 mt-1">
              <?= $review_count ? "Based on $review_count review" . ($review_count > 1 ? 's' : '') : 'No reviews yet'; ?>
            </p>
          </div>

          <div class="md:col-span-2 space-y-2 self-center">
            <?php foreach ($star_counts as $star => $cnt):
                $pct = $review_count ? round(($cnt / $review_count) * 100) : 0; ?>
              <div class="flex items-center gap-3 text-sm">
                <span class="w-10 flex items-center gap-1 font-semibold"><?= $star; ?> <i class="fa-solid fa-star text-warning text-xs"></i></span>
                <progress class="progress progress-warning flex-1" value="<?= $pct; ?>" max="100"></progress>
                <span class="w-8 text-right text-xs text-base-content/60"><?= $cnt; ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Reviews list -->
        <div class="space-y-4">
          <h4 class="text-xs font-bold uppercase tracking-wider text-base-content/50">Recent Comments</h4>

          <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $rv): ?>
              <div class="p-4 rounded-xl border border-base-200 bg-base-100 space-y-2 hover:border-emerald-500/30 transition-all">
                <div class="flex items-center justify-between gap-2">
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-emerald-500/10 text-emerald-700 font-bold flex items-center justify-center text-xs flex-shrink-0">
                      <?= htmlspecialchars($rv['initials']); ?>
                    </div>
                    <div class="min-w-0">
                      <h4 class="font-bold text-sm text-base-content truncate"><?= htmlspecialchars($rv['name']); ?></h4>
                      <span class="text-[11px] text-base-content/50"><?= htmlspecialchars($rv['date']); ?></span>
                    </div>
                  </div>
                  <div class="flex text-warning text-xs gap-0.5 flex-shrink-0">
                    <?= renderFaStars($rv['rating']); ?>
                  </div>
                </div>
                <p class="text-sm text-base-content/80 sm:pl-12 leading-relaxed break-words">
                  <?= nl2br(htmlspecialchars($rv['comment'])); ?>
                </p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="text-center py-8 text-base-content/50 bg-base-200/50 rounded-xl">
              <i class="fa-regular fa-comments text-3xl mb-2 block"></i>
              <p class="text-sm">No comments yet.</p>
            </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Photo Gallery Section -->
      <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
        <div class="flex justify-between items-center mb-6 pb-3 border-b border-base-200 flex-row">
          <div>
            <h2 class="text-xl font-bold flex items-center gap-2 text-base-content">
              <i class="fa-solid fa-images text-success"></i> Photo Gallery
            </h2>
            <p class="text-xs text-base-content/60 mt-0.5">Explore photos of the property and surrounding areas</p>
          </div>
          <span class="badge badge-success badge-outline font-semibold">
            <?= count($apartment_images); ?> Photos
          </span>
        </div>

        <?php if (!empty($apartment_images)): ?>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            <?php foreach ($apartment_images as $img): ?>
              <div class="rounded-xl overflow-hidden border border-base-200 hover:shadow-md transition-all group bg-base-200 relative aspect-square">
                <a href="assets/uploads/<?= htmlspecialchars($img); ?>" target="_blank" class="block w-full h-full">
                  <img src="assets/uploads/<?= htmlspecialchars($img); ?>"
                       alt="Gallery Image"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                       loading="lazy"
                       onerror="this.src='assets/images/placeholder.jpg';" />
                </a>
                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                  <i class="fa-solid fa-magnifying-glass-plus text-white text-lg"></i>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-10 text-base-content/50 bg-base-200/50 rounded-xl">
            <i class="fa-regular fa-image text-4xl mb-2 block text-base-content/30"></i>
            <p class="text-sm font-medium">No gallery images uploaded for this space.</p>
          </div>
        <?php endif; ?>
      </div>

    </main>
  </div>

  <!-- Sidebar Drawer Navigation -->
  <div class="drawer-side z-40">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside class="bg-base-100 min-h-full w-80 p-5 border-r border-base-300 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-base-200">
          <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center flex-shrink-0">
            <img src="assets/images/logo-icon.png" class="w-6 h-6 object-contain" alt="Logo" onerror="this.src='assets/images/placeholder.jpg';">
          </div>
          <div class="min-w-0 flex-1">
            <h2 class="font-bold text-base text-base-content truncate">
              <?= htmlspecialchars($property_name ?: 'RentSpace'); ?>
            </h2>
            <span class="text-xs text-base-content/60 flex items-center gap-1">
              <i class="fa-solid fa-building text-success text-[10px]"></i> Landlord Listings
            </span>
          </div>
        </div>

        <ul class="menu p-0 w-full gap-1">
          <li class="menu-title px-2 text-[11px] font-bold uppercase tracking-wider text-base-content/50">
            Other Listings
          </li>

          <?php
          if ($landlord_id) {
              $get_rentspace = $conn->prepare("
                SELECT rent_id, name, price, image_cover
                FROM `rentspace`
                WHERE `landlord_id` = ? AND `type` = ?
              ");
              $get_rentspace->bind_param("ss", $landlord_id, $type);
              $get_rentspace->execute();
              $result_other = $get_rentspace->get_result();

              if ($result_other->num_rows > 0) {
                  while ($data_get = $result_other->fetch_assoc()) {
                      $other_id    = $data_get['rent_id'];
                      $other_name  = $data_get['name'] ?? 'Unnamed Space';
                      $other_price = $data_get['price'] ?? 0;
                      $other_img   = $data_get['image_cover'] ?? '';

                      $isActive = ($other_id == $rent_id) ? 'active bg-success text-white font-semibold' : 'hover:bg-base-200 text-base-content';
                      ?>
                      <li>
                        <a href="?id=<?= urlencode($other_id); ?>" class="flex items-center gap-3 p-2.5 rounded-xl transition-all <?= $isActive; ?>">
                          <div class="w-11 h-11 rounded-lg overflow-hidden bg-base-300 flex-shrink-0 border border-base-200">
                            <img src="assets/uploads/<?= htmlspecialchars($other_img); ?>"
                                 alt="<?= htmlspecialchars($other_name); ?>"
                                 class="w-full h-full object-cover"
                                 loading="lazy"
                                 onerror="this.src='assets/images/placeholder.jpg';" />
                          </div>
                          <div class="flex-1 min-w-0">
                            <p class="text-sm truncate leading-tight"><?= htmlspecialchars($other_name); ?></p>
                            <p class="text-xs opacity-75 mt-0.5">₱<?= number_format((float)$other_price, 2); ?> <span class="text-[10px] opacity-70">/ mo</span></p>
                          </div>
                          <?php if ($other_id == $rent_id): ?>
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                          <?php endif; ?>
                        </a>
                      </li>
                      <?php
                  }
              } else {
                  echo '<li class="p-4 text-center text-xs text-base-content/50 italic bg-base-200/50 rounded-xl mt-2">No other properties found.</li>';
              }
              $get_rentspace->close();
          }
          ?>
        </ul>
      </div>

      <div class="border-t border-base-200 pt-4 mt-6">
        <div class="flex items-center justify-between text-xs text-base-content/60 px-1">
          <span>© <?= date('Y'); ?> RentSpace</span>
          <span class="badge badge-ghost badge-xs">v1.0</span>
        </div>
      </div>
    </aside>
  </div>
</div>

<script src="assets/scripts/jquery.js"></script>
<script src="assets/scripts/index.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const mapEl = document.getElementById('map');
  if (!mapEl) return;

  function showMapMessage(msg) {
    mapEl.innerHTML =
      '<div style="height:100%;display:flex;align-items:center;justify-content:center;' +
      'text-align:center;padding:16px;font-size:13px;opacity:.6;">' + msg + '</div>';
  }

  if (typeof L === 'undefined') {
    showMapMessage('Map failed to load. Please check your internet connection.');
    return;
  }

  const hasCoords = <?= $has_coords ? 'true' : 'false'; ?>;
  const lat = <?= $has_coords ? json_encode($lat_val) : 'null'; ?>;
  const lng = <?= $has_coords ? json_encode($lng_val) : 'null'; ?>;
  const propertyName = <?= json_encode($property_name); ?>;
  const addressText  = <?= json_encode($address_text); ?>;

  if (!hasCoords) {
    showMapMessage('No map location was set for this house.');
    return;
  }

  const map = L.map('map', { scrollWheelZoom: false }).setView([lat, lng], 16);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
  }).addTo(map);

  const popup = document.createElement('div');
  const title = document.createElement('strong');
  title.textContent = propertyName || 'Property';
  const addr = document.createElement('div');
  addr.textContent = addressText || 'Pinned location';
  popup.append(title, addr);

  L.marker([lat, lng]).addTo(map).bindPopup(popup).openPopup();

  setTimeout(function () { map.invalidateSize(); }, 300);
  window.addEventListener('resize', function () { map.invalidateSize(); });
});
</script>

</body>
</html>