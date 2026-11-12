<?php 

include '../config.php'; 
if(isset($_GET['id'])){
    $rent_id = $_GET['id'];
}

// RENTSPACE DETAILS (includes saved location)
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, landlord_id, user_id, type,
           longitude, latitude, province, barangay, municipality
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
$landlord_id = "";
$user_id = "";
$type = "";
$longitude = "";
$latitude = "";
$province = "";
$municipality = "";
$barangay = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name         = $rent_row['name'] ?? '';
    $price        = $rent_row['price'] ?? '';
    $image_cover  = $rent_row['image_cover'] ?? '';
    $other_info   = $rent_row['other_info'] ?? '';
    $landlord_id  = $rent_row['landlord_id'] ?? '';
    $user_id      = $rent_row['user_id'] ?? '';
    $type         = $rent_row['type'] ?? '';
    $longitude    = $rent_row['longitude'] ?? '';
    $latitude     = $rent_row['latitude'] ?? '';
    $province     = $rent_row['province'] ?? '';
    $municipality = $rent_row['municipality'] ?? '';
    $barangay     = $rent_row['barangay'] ?? '';
    $rate     = $rent_row['rate'] ?? '';
}
$get_rent->close();

// MAP COORDINATES (valid only if numeric, in range, and not 0,0)
$lat_val = (is_numeric($latitude) && $latitude !== '') ? (float)$latitude : null;
$lng_val = (is_numeric($longitude) && $longitude !== '') ? (float)$longitude : null;
$has_coords = (
    $lat_val !== null && $lng_val !== null
    && abs($lat_val) <= 90 && abs($lng_val) <= 180
    && !($lat_val == 0.0 && $lng_val == 0.0)
);

// BOARDING HOUSES / BEDS
$stat = "Out of Order";
$get_bh = $conn->prepare("
    SELECT boarding_id, status, num_decks, image, bed_number 
    FROM boarding_house 
    WHERE rent_id = ? AND status != ? ORDER BY bed_number
");
$get_bh->bind_param("ss", $rent_id, $stat);
$get_bh->execute();
$bh_res = $get_bh->get_result();

$boarding_houses = [];
while ($row = $bh_res->fetch_assoc()) {
    $boarding_houses[$row['boarding_id']] = $row;
}
$get_bh->close();

// AMENITIES
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

// Helper: Badge color per bed status
function bedStatusBadge($status) {
    switch ($status) {
        case 'Available':    return 'badge-success text-white';
        case 'Occupied':     return 'badge-error text-white';
        case 'Reserved':     return 'badge-primary text-white';
        case 'Out of Order': return 'badge-ghost';
        default:             return 'badge-ghost';
    }
}

// Helper: Font Awesome stars (full / half / empty)
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

// LANDLORD DETAILS
$get_landlord_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
$get_landlord_info->bind_param("s", $landlord_id);
$get_landlord_info->execute();
$result_landlord = $get_landlord_info->get_result();

$property_name = "";
if ($row_landlord = $result_landlord->fetch_assoc()) {
    $property_name = $row_landlord['property_name'] ?? '';
}
$get_landlord_info->close();

// ACCOUNT DETAILS
$account = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
$account->bind_param("s", $user_id);
$account->execute();
$result_account = $account->get_result();

$contact_number = "";
$email = "";
if ($row_account = $result_account->fetch_assoc()) {
    $contact_number = $row_account['contact_number'] ?? '';
    $email          = $row_account['email'] ?? '';
}
$account->close();

// ============================================================
// RATINGS & REVIEWS
// ============================================================
$get_avg = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM `reviews` WHERE `landlord_id` = ?");
$get_avg->bind_param("s", $landlord_id);
$get_avg->execute();
$avg_row      = $get_avg->get_result()->fetch_assoc();
$avg_rating   = (float)($avg_row['avg_rating'] ?? 0);
$review_count = (int)($avg_row['review_count'] ?? 0);
$get_avg->close();

// star distribution
$star_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
$get_dist = $conn->prepare("SELECT rating, COUNT(*) AS c FROM `reviews` WHERE `landlord_id` = ? GROUP BY rating");
$get_dist->bind_param("s", $landlord_id);
$get_dist->execute();
$dist_res = $get_dist->get_result();
while ($d = $dist_res->fetch_assoc()) {
    $r = (int)$d['rating'];
    if (isset($star_counts[$r])) $star_counts[$r] = (int)$d['c'];
}
$get_dist->close();

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

$reviews = [];
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

// Can this user write a review? (not the owner, and hasn't reviewed yet)
$is_owner     = ($user_id_login === $user_id);
$has_reviewed = false;
if (!empty($user_id_login)) {
    $chk = $conn->prepare("SELECT 1 FROM `reviews` WHERE `landlord_id` = ? AND `user_id` = ?");
    $chk->bind_param("ss", $landlord_id, $user_id_login);
    $chk->execute();
    $has_reviewed = $chk->get_result()->num_rows > 0;
    $chk->close();
}

// favorites
$favorites = $conn->prepare("SELECT * FROM `favorites` WHERE `rent_id` = ?");
$favorites->bind_param("s", $rent_id);
$favorites->execute();
$result_fav = $favorites->get_result();
$switch = ($result_fav->num_rows > 0) ? "on" : "off";
$favorites->close();

$hidden        = $is_owner ? 'hidden' : '';
$totalBeds     = count($boarding_houses);
$availableBeds = count(array_filter($boarding_houses, fn($b) => $b['status'] === 'Available'));

// auto save viewed
$check = $conn->prepare(
    "SELECT COUNT(*) AS already_viewed
     FROM `rent_views`
     WHERE `rent_id` = ?
       AND `user_id` = ?
       AND `date_viewed` = ?"
);
$check->bind_param("sss", $rent_id, $user_id_login, $datetoday);
$check->execute();
$already_viewed = $check->get_result()->fetch_assoc()['already_viewed'] ?? 0;
$check->close();

if ($already_viewed == 0) {
    $insert_viewed = $conn->prepare(
        "INSERT INTO `rent_views` (`landlord_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`)
         VALUES (?, ?, ?, ?, ?)"
    );
    $insert_viewed->bind_param("sssss", $landlord_id, $user_id_login, $datetoday, $timetoday1, $rent_id);
    $insert_viewed->execute();
    $insert_viewed->close();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title><?= htmlspecialchars($property_name . " - " . $name); ?></title>
    <link rel="shortcut icon" href="../assets/images/logo-icon.png" type="image/x-icon"> 
    
    <!-- Styles -->
    <link rel="stylesheet" href="../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <script src="../assets/scripts/tailwind.js"></script>
    <script src="../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/jquery.js"></script>
    <link rel="stylesheet" href="../assets/styles/coolalert.css">   
    <script src="../assets/scripts/cool_alert.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* clickable star input (row-reverse trick) */
        .star-input { display:inline-flex; flex-direction:row-reverse; gap:4px; }
        .star-input input { display:none; }
        .star-input label { font-size:1.75rem; cursor:pointer; color:#d1d5db; transition:color .15s; }
        .star-input label:hover,
        .star-input label:hover ~ label,
        .star-input input:checked ~ label { color:#facc15; }

        /* Leaflet tiles/markers: undo Tailwind preflight img{max-width:100%} */
        #map img.leaflet-tile,
        #map img.leaflet-marker-icon,
        #map img.leaflet-marker-shadow {
            max-width: none !important;
            max-height: none !important;
        }
        #map .leaflet-container { width: 100%; height: 100%; }
    </style>
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">

<?php include '../alerts.php'; ?>

<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />

  <!-- MAIN CONTENT AREA -->
  <div class="drawer-content flex flex-col">
    
    <!-- Top Mobile Navbar -->
    <header class="flex items-center py-3 px-2 bg-base-100 shadow-sm lg:hidden sticky top-0 z-30">
      <div class="flex-none">
        <label for="my-drawer-3" class="btn btn-square btn-ghost">
          <i class="fa-solid fa-bars text-xl"></i>
        </label>
      </div>
      <div class="flex-1">
        <span class="font-bold text-lg truncate"><?= htmlspecialchars($property_name); ?></span>
      </div>
    </header>

    <main class="p-4 md:p-8 max-w-7xl mx-auto w-full space-y-6">
        <div class="flex justify-end items-end">
            <a href="properties.php"><img src="../assets/images/back.png" loading="lazy"></a>
        </div>
      <!-- HERO BANNER / IMAGE GALLERY -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        
        <!-- Image Container -->
        <div class="lg:col-span-2 relative h-64 sm:h-80 md:h-[400px] w-full rounded-xl overflow-hidden bg-base-300 group">
          <img src="../assets/uploads/<?= htmlspecialchars($image_cover); ?>" 
               alt="<?= htmlspecialchars($name); ?>" 
               loading="lazy"
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
               onerror="this.src='assets/images/under_constractions.jpg';" />
          <div class="absolute top-4 left-4">
            <span class="badge badge-success text-white badge-lg gap-2 shadow-md">
              <i class="fa-solid fa-house"></i> Boarding House / Bed Space
            </span>
          </div>
          <a href="report.php?user_id=<?php echo urlencode($user_id); ?>&location_back=apartment_details.php&id=<?php echo urlencode($rent_id); ?>&report_type=Post" class="<?php echo $hidden; ?> tooltip tooltip-left absolute bottom-4 right-27 lg:top-4 h-fit text-blue-900 bg-green-300 hover:bg-blue-200 active:bg-red-400 rounded-lg p-1.5 flex items-center justify-center transition-all cursor-pointer border-none outline-none" data-tip="Report">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-warning-icon lucide-message-circle-warning"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
            </a>
          <a href="chat_portal.php?id=<?php echo urlencode($user_id); ?>" class="<?php echo $hidden; ?> tooltip tooltip-left absolute bottom-4 right-15 lg:top-4 h-fit text-blue-900 bg-green-300 hover:bg-blue-200 active:bg-red-400 rounded-lg p-1.5 flex items-center justify-center transition-all cursor-pointer border-none outline-none" data-tip="Message Landlord">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-text-icon lucide-message-square-text"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="M7 11h10"/><path d="M7 15h6"/><path d="M7 7h8"/></svg>
          </a>
          <form action="../functions.php" method="POST" class="tooltip tooltip-left absolute bottom-4 right-4 h-fit lg:top-4 " data-tip="<?php echo ($switch == 'on') ? 'Remove from Favorites' : 'Add to my Favorites'; ?>">

              <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
              <input type="hidden" name="locate" value="boarding_details.php">
              <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">
              <input type="hidden" name="current_status" value="<?php echo $switch; ?>">

              <button type="submit" name="add_favorite_btn" value="1" class="bg-green-300 hover:bg-red-200 active:bg-red-400 rounded-lg p-1 flex items-center justify-center transition-all cursor-pointer border-none outline-none">

                  <svg xmlns="http://www.w3.org/2000/svg" 
                      width="28" 
                      height="28" 
                      viewBox="0 0 24 24" 
                      fill="<?php echo ($switch == 'on') ? '#dc2626' : 'none'; ?>" 
                      stroke="<?php echo ($switch == 'on') ? '#dc2626' : 'currentColor'; ?>" 
                      stroke-width="2" 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      class="<?php echo ($switch == 'on') ? 'text-red-600' : 'text-red-800 hover:fill-red-600 hover:text-red-600'; ?> transition-colors">
                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/>
                  </svg>

              </button>
          </form>
        </div>

        <!-- Quick Summary & Pricing Side Panel -->
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

            <p class="text-sm text-base-content/70 mt-2 flex items-center gap-1">
              <i class="fa-solid fa-location-dot text-error"></i> 
              <?= htmlspecialchars(implode(', ', array_filter([$barangay, $municipality, $province]))); ?>
            </p>

            <div class="divider my-3"></div>

            <!-- Price Display -->
            <div class="bg-primary/10 p-4 rounded-xl border border-primary/20">
              <span class="text-xs font-semibold text-success uppercase tracking-wider">Monthly Rent</span>
              <div class="text-3xl font-black text-success mt-1">
                ₱<?= number_format((float)$price, 2); ?> <span class="text-sm font-normal text-base-content/70">/ month</span>
              </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 gap-3 mt-4">
              <div class="stat bg-base-200/60 p-3 rounded-xl">
                <div class="stat-title text-xs">Total Beds</div>
                <div class="stat-value text-xl"><?= $totalBeds; ?></div>
              </div>
              <div class="stat bg-base-200/60 p-3 rounded-xl">
                <div class="stat-title text-xs">Available</div>
                <div class="stat-value text-xl text-success"><?= $availableBeds; ?></div>
              </div>
            </div>
          </div>

          <!-- Landlord Quick Contact Card -->
          <div class="p-4 bg-base-200/50 rounded-xl border border-base-300 space-y-2">
            <p class="text-xs font-bold text-base-content/60 uppercase">Property Contact</p>
            <p class="text-sm font-semibold flex items-center gap-2">
              <i class="fa-solid fa-phone text-success"></i> <?= htmlspecialchars($contact_number ?: 'N/A'); ?>
            </p>
            <p class="text-sm font-semibold flex items-center gap-2 truncate">
              <i class="fa-solid fa-envelope text-success"></i> <?= htmlspecialchars($email ?: 'N/A'); ?>
            </p>
          </div>
        </div>

      </div>

      <!-- DETAILS & MAP SECTION -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2-Cols: Info, Amenities, Beds, & Reviews -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Description / Info -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Space
            </h2>
            <div class="text-base-content/80 text-sm leading-relaxed">
              <?= $other_info ? $other_info : 'No additional details provided.'; ?>
            </div>
          </div>

          <!-- Amenities List -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <i class="fa-solid fa-list-check text-success"></i> Amenities & Facilities
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
              <p class="text-sm text-base-content/50 italic">No amenities listed for this room.</p>
            <?php endif; ?>
          </div>

          <!-- Boarding House / Beds List -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold flex items-center gap-2">
                <i class="fa-solid fa-bed text-success"></i> Available Beds / Units
              </h2>
              <span class="text-xs text-base-content/60"><?= count($boarding_houses); ?> </span>
            </div>

            <?php if (!empty($boarding_houses)): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ">
                <?php foreach ($boarding_houses as $bh): ?>
                  <button type="button" 
                          class="bed-card border border-base-300 cursor-pointer rounded-xl p-4 flex gap-4 items-center bg-base-100 hover:border-primary hover:shadow-md transition-all text-left w-full"
                          data-img="../assets/uploads/<?= htmlspecialchars($bh['image']); ?>"
                          data-bed="<?= htmlspecialchars($bh['bed_number']); ?>"
                          data-status="<?= htmlspecialchars($bh['status']); ?>"
                          data-decks="<?= htmlspecialchars($bh['num_decks']); ?>"
                          data-id="<?= htmlspecialchars($bh['boarding_id']); ?>"
                          data-landlord-id="<?= htmlspecialchars($landlord_id); ?>"
                          onclick="openBedModal(this)">
                    <!-- Bed Image -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-base-200 flex-shrink-0">
                      <img src="../assets/uploads/<?= htmlspecialchars($bh['image']); ?>" 
                           alt="Bed <?= htmlspecialchars($bh['bed_number']); ?>" 
                           loading="lazy"
                           class="w-full h-full object-cover"
                           onerror="this.src='assets/images/under_constractions.jpg';" />
                    </div>

                    <!-- Bed Info -->
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between">
                        <h3 class="font-bold text-base truncate">Bed #<?= htmlspecialchars($bh['bed_number']); ?></h3>
                        <span class="badge badge-sm <?= bedStatusBadge($bh['status']); ?>">
                          <?= htmlspecialchars($bh['status']); ?>
                        </span>
                      </div>
                      <p class="text-xs text-base-content/70 mt-1">
                        Decks: <span class="font-semibold text-base-content"><?= htmlspecialchars($bh['num_decks']); ?></span>
                      </p>

                    </div>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-8 text-base-content/50">
                <i class="fa-solid fa-bed text-3xl mb-2 block"></i>
                No active beds or units found for this room space.
              </div>
            <?php endif; ?>
          </div>

          <!-- RATINGS & REVIEWS -->
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

            <!-- Write a review -->
            <?php if ($is_owner): ?>
              <div class="alert bg-base-200 text-sm">
                <i class="fa-solid fa-circle-info text-success"></i>
                <span>You can't review your own property.</span>
              </div>
            <?php elseif ($has_reviewed): ?>
              <div class="alert alert-success text-white text-sm">
                <i class="fa-solid fa-check"></i>
                <span>You already reviewed this property. Thank you!</span>
              </div>
            <?php else: ?>
              <form action="../functions.php" method="POST" class="border border-base-300 rounded-xl p-4 space-y-4 bg-base-100">
                <input type="hidden" name="landlord_id" value="<?= htmlspecialchars($landlord_id); ?>">
                <input type="hidden" name="rent_id" value="<?= htmlspecialchars($rent_id); ?>">
                <input type="hidden" name="redirect" value="users/boarding_details.php">

                <h3 class="font-bold text-base">Write a review</h3>

                <div>
                  <label class="block text-xs font-semibold text-base-content/70 mb-1">Your rating <span class="text-error">*</span></label>
                  <div class="star-input">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 stars"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star"><i class="fa-solid fa-star"></i></label>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-base-content/70 mb-1">Comment <span class="text-error">*</span></label>
                  <textarea name="comment" rows="4" maxlength="1000" required
                            class="textarea textarea-bordered w-full"
                            placeholder="Share your experience with this property..."></textarea>
                </div>

                <div class="flex justify-end">
                  <button type="submit" name="submit_review" class="btn btn-success text-white gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Submit Review
                  </button>
                </div>
              </form>
            <?php endif; ?>

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
                  <p class="text-sm">No comments yet. Be the first to review!</p>
                </div>
              <?php endif; ?>
            </div>

          </div>

        </div>

        <!-- Right 1-Col: Map View -->
        <div class="space-y-6">
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 sticky top-6">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-success"></i> Location Map
            </h2>
            <p class="text-xs text-base-content/60 mb-3">
              <?= htmlspecialchars(implode(', ', array_filter([$barangay, $municipality, $province]))); ?>
            </p>
            
            <!-- Map container: explicit height so Leaflet has room to render -->
            <div id="map" class="w-full rounded-xl border border-base-300 bg-base-200 relative z-0"
                 style="height:288px; min-height:288px;"></div>
          </div>
        </div>

      </div>

    </main>
  </div>

  <!-- SIDEBAR NAVIGATION -->
  <div class="drawer-side z-40">
  <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
  <aside class="bg-base-100 min-h-full w-80 p-5 border-r border-base-300 flex flex-col justify-between">
    
    <div>
      <!-- Property Brand Header -->
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-base-200">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
          <img src="../assets/images/logo-icon.png" class="w-6 h-6 object-contain" loading="lazy" alt="Logo" onerror="this.src='assets/images/under_constractions.png';">
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

      <!-- Navigation Menu -->
      <ul class="menu p-0 w-full gap-1">
        
        <!-- Category Title -->
        <li class="menu-title px-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
          Other Rooms Space
        </li>

        <?php
        // Guard against undefined $type
        $room_type = $type ?? '';

        $get_rentspace = $conn->prepare("
          SELECT rent_id, name, price, image_cover 
          FROM `rentspace` 
          WHERE `landlord_id` = ? AND `type` = ? ORDER BY price ASC
        ");
        $get_rentspace->bind_param("ss", $landlord_id, $room_type);
        $get_rentspace->execute();
        $result_rentspace = $get_rentspace->get_result();

        if ($result_rentspace->num_rows > 0) {
            while ($data_get = $result_rentspace->fetch_assoc()) {
                $other_id    = $data_get['rent_id'];
                $other_name  = $data_get['name'] ?? 'Unnamed Room';
                $other_price = $data_get['price'] ?? 0;
                $other_img   = $data_get['image_cover'] ?? '';

                // Check if this room is the currently selected one
                $isActive = ($other_id == $rent_id) ? 'active bg-emerald-500 text-success-content font-semibold' : 'hover:bg-base-200 text-base-content';
                $color = ($other_id == $rent_id) ? 'text-white' : 'text-black';
                ?>
                
                <li>
                  <a href="?id=<?= urlencode($other_id); ?>" 
                     class="flex items-center gap-3 p-2.5 rounded-xl transition-all <?= $isActive; ?>">
                    
                    <!-- Room Thumbnail -->
                    <div class="w-11 h-11 rounded-lg overflow-hidden bg-base-300 flex-shrink-0 border border-base-200">
                      <img src="../assets/uploads/<?= htmlspecialchars($other_img); ?>" 
                           alt="<?= htmlspecialchars($other_name); ?>" 
                           class="w-full h-full object-cover"
                           loading="lazy"
                           onerror="this.src='assets/images/under_constractions.jpg';" />
                    </div>

                    <!-- Room Info -->
                    <div class="flex-1 min-w-0">
                      <p class="text-sm truncate <?php echo $color; ?> leading-tight">
                        <?= htmlspecialchars($other_name); ?>
                      </p>
                      <p class="text-xs opacity-75 mt-0.5 <?php echo $color; ?>">
                        ₱<?= number_format((float)$other_price, 2); ?> <span class="text-[10px] opacity-70">/ mo</span>
                      </p>
                    </div>

                    <!-- Active Indicator Icon -->
                    <?php if ($other_id == $rent_id): ?>
                      <i class="fa-solid fa-chevron-right text-xs <?php echo $color; ?>"></i>
                    <?php endif; ?>
                  </a>
                </li>

                <?php
            }
        } else {
            ?>
            <li class="p-4 text-center text-xs text-base-content/50 italic bg-base-200/50 rounded-xl mt-2">
              <i class="fa-solid fa-door-closed text-lg mb-1 block"></i>
              No other rooms found for this property.
            </li>
            <?php
        }
        $get_rentspace->close();
        ?>
      </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="border-t border-base-200 pt-4 mt-6">
      <div class="flex items-center justify-between text-xs text-base-content/60 px-1">
        <span>© <?= date('Y'); ?> RentSpace</span>
        <span class="badge badge-ghost badge-xs">v1.0</span>
      </div>
    </div>

  </aside>
</div>
</div>

<!-- Bed Details Modal -->
<dialog id="bed_modal" class="modal">
  <div class="modal-box max-w-2xl p-0 overflow-hidden">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
    </form>
      <div class="w-full h-64 sm:h-96 bg-base-200">
        <img id="bed_modal_img" src="" alt="Bed" class="w-full h-full object-cover" onerror="this.src='../assets/images/under_constractions.jpg';" />
        <form action="" method="" class="absolute top-0 p-3">
          <button  type="button" id="reserve_bed_btn"
        class="  w-full cursor-pointer tooltip tooltip-right"
        data-boarding-id="" data-landlord-id="" data-tip="Interested">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" class="text-white bg-error p-1 rounded-[10px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-heart preview-icon"><path d="M8.62 13.8A2.25 2.25 0 1 1 12 10.836a2.25 2.25 0 1 1 3.38 2.966l-2.626 2.856a.998.998 0 0 1-1.507 0z"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          </button>
        </form>
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-bold" id="bed_modal_title">Bed #</h3>
          <span id="bed_modal_status" class="badge badge-lg"></span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="p-4 bg-base-200/60 rounded-xl">
            <p class="text-xs text-base-content/60 uppercase tracking-wider">Decks</p>
            <p class="text-lg font-semibold mt-1" id="bed_modal_decks">-</p>
          </div>
          <div class="p-4 bg-base-200/60 rounded-xl">
            <p class="text-xs text-base-content/60 uppercase tracking-wider">Property</p>
            <p class="text-lg font-semibold mt-1 truncate"><?= htmlspecialchars($property_name); ?></p>
          </div>

        </div>
      </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>

<!-- ============================================================
     BED MODAL + RESERVE
     ============================================================ -->
<script>
function openBedModal(btn) {
  const reserveBtn = document.getElementById('reserve_bed_btn');
  reserveBtn.setAttribute('data-boarding-id', btn.getAttribute('data-id'));
  reserveBtn.setAttribute('data-landlord-id', btn.getAttribute('data-landlord-id'));

  document.getElementById('bed_modal_img').src = btn.getAttribute('data-img');
  document.getElementById('bed_modal_title').textContent = 'Bed #' + btn.getAttribute('data-bed');
  document.getElementById('bed_modal_decks').textContent = btn.getAttribute('data-decks');

  const status = btn.getAttribute('data-status');
  const statusEl = document.getElementById('bed_modal_status');
  statusEl.textContent = status;
  statusEl.className = 'badge badge-lg';
  if (status === 'Available') statusEl.classList.add('badge-success', 'text-white');
  else if (status === 'Occupied') statusEl.classList.add('badge-error', 'text-white');
  else if (status === 'Reserved') statusEl.classList.add('badge-primary', 'text-white');
  else statusEl.classList.add('badge-ghost');

  // Only Available beds can be reserved
  const canReserve = (status === 'Available');
  reserveBtn.setAttribute('aria-disabled', canReserve ? 'false' : 'true');
  reserveBtn.classList.toggle('opacity-40', !canReserve);
  reserveBtn.classList.toggle('cursor-not-allowed', !canReserve);
  reserveBtn.classList.toggle('cursor-pointer', canReserve);
  reserveBtn.setAttribute('data-tip', canReserve ? 'Interested' : 'This bed is already ' + status.toLowerCase());

  document.getElementById('bed_modal').showModal();
}

// Fallback if CoolAlert fails to load
function showAlert(ok, message) {
  const lib = window.CoolAlert || window.coolAlert || window.Coolalert || null;

  if (!lib || typeof lib.show !== 'function') {
    console.warn('CoolAlert not loaded. Check path: ../assets/scripts/coolalert.js');
    alert((ok ? 'SUCCESS: ' : 'ERROR: ') + message);
    return;
  }

  lib.show({
    toast: true,
    icon: ok ? "success" : "error",
    title: ok ? "SUCCESS" : "SOMETHING WRONG",
    text: String(message),
    position: "top-right",
    timer: 8000,
    timerProgressBar: true,
    customClass: { container: "z-[9999]" }
  });
}

$('#reserve_bed_btn').off('click').on('click', function () {
  if (this.getAttribute('aria-disabled') === 'true') return;

  $.ajax({
    url: '../functions.php',
    method: 'POST',
    dataType: 'json',
    data: {
      action: 'reserve_bed',
      boarding_id: this.getAttribute('data-boarding-id'),
      landlord_id: this.getAttribute('data-landlord-id')
    },
    success: function (res) {
      console.log('reserve_bed response:', res);
      document.getElementById('bed_modal').close();
      showAlert(res.ok, res.message);
    },
    error: function (xhr) {
      console.log('reserve_bed error:', xhr.responseText);
      document.getElementById('bed_modal').close();
      showAlert(false, 'Server error: ' + (xhr.responseText || 'Request failed').substring(0, 200));
    }
  });
});
</script>

<!-- ============================================================
     LEAFLET MAP
     ============================================================ -->
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

  const lat = <?= $has_coords ? json_encode($lat_val) : 'null'; ?>;
  const lng = <?= $has_coords ? json_encode($lng_val) : 'null'; ?>;
  const propertyName = <?= json_encode($property_name); ?>;
  const addressText  = <?= json_encode(implode(', ', array_filter([$barangay, $municipality, $province]))); ?>;

  if (lat === null || lng === null) {
    showMapMessage('No map location was set for this property.');
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
  addr.textContent = addressText;
  popup.append(title, addr);

  L.marker([lat, lng]).addTo(map).bindPopup(popup).openPopup();

  setTimeout(function () { map.invalidateSize(); }, 300);
  window.addEventListener('load', function () { map.invalidateSize(); });
  window.addEventListener('resize', function () { map.invalidateSize(); });
});
</script>

</body>
</html>