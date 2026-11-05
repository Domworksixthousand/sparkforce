<?php 
include '../config.php';

$rent_id = $_GET['id'] ?? '';

$name = $price = $image_cover = $other_info = $landlord_id = $user_id = $type = $rate = "";
$condo_units = [];
$availableCondos = 0;
$condo_id = "";
$apartment_images = [];
$saved_amenities = [];

// 1. RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, landlord_id, user_id, type, rate
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
    $landlord_id = $rent_row['landlord_id'];
    $user_id     = $rent_row['user_id'];
    $type        = $rent_row['type'];
    $rate        = $rent_row['rate'];
}

// 2. CONDO UNITS
$get_condo = $conn->prepare("SELECT * FROM `condo` WHERE `rent_id` = ?");
$get_condo->bind_param("s", $rent_id);
$get_condo->execute();
$result_execute = $get_condo->get_result();

while ($row_apar = $result_execute->fetch_assoc()) {
    $condo_units[] = $row_apar;
    $condo_id = $row_apar['condo_id'] ?? '';
    if (isset($row_apar['status']) && strtolower($row_apar['status']) === 'available') {
        $availableCondos++;
    }
}
$totalCondos = count($condo_units);

// 3. GALLERY
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();
while ($row_gallery = $result_gallery->fetch_assoc()) {
    $apartment_images[] = $row_gallery['image'];
}

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

function apartmentStatusBadge($status) {
    switch ($status) {
        case 'Available': return 'badge-success text-white';
        case 'Occupied':
        case 'Not Available': return 'badge-error text-white';
        case 'Out of Order': return 'badge-warning text-white';
        default: return 'badge-neutral';
    }
}

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

// 5. LANDLORD DETAILS
$province = $municipality = $barangay = $longitude = $latitude = $property_name = "";
$get_landlord_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
$get_landlord_info->bind_param("s", $landlord_id);
$get_landlord_info->execute();
$result_landlord = $get_landlord_info->get_result();
if ($row_landlord = $result_landlord->fetch_assoc()) {
    $province      = $row_landlord['province'];
    $municipality  = $row_landlord['municipality'];
    $barangay      = $row_landlord['barangay'];
    $longitude     = $row_landlord['longitude'];
    $latitude      = $row_landlord['latitude'];
    $property_name = $row_landlord['property_name'];
}

// 6. ACCOUNT DETAILS
$contact_number = $email = "";
$account = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
$account->bind_param("s", $user_id);
$account->execute();
$result_account = $account->get_result();
if ($row_account = $result_account->fetch_assoc()) {
    $contact_number = $row_account['contact_number'];
    $email          = $row_account['email'];
}

// ============================================================
// 7. RATINGS & REVIEWS
// ============================================================
$get_avg = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM `reviews` WHERE `landlord_id` = ?");
$get_avg->bind_param("s", $landlord_id);
$get_avg->execute();
$avg_row      = $get_avg->get_result()->fetch_assoc();
$avg_rating   = (float)($avg_row['avg_rating'] ?? 0);
$review_count = (int)($avg_row['review_count'] ?? 0);

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

// Can this user write a review? (not the owner, and hasn't reviewed yet)
$is_owner     = ($user_id_login === $user_id);
$has_reviewed = false;
if (!empty($user_id_login)) {
    $chk = $conn->prepare("SELECT 1 FROM `reviews` WHERE `landlord_id` = ? AND `user_id` = ?");
    $chk->bind_param("ss", $landlord_id, $user_id_login);
    $chk->execute();
    $has_reviewed = $chk->get_result()->num_rows > 0;
}

// 8. FAVORITES / INTERESTED
$favorites = $conn->prepare("SELECT * FROM `favorites` WHERE `rent_id` = ?");
$favorites->bind_param("s", $rent_id);
$favorites->execute();
$result_fav = $favorites->get_result();
$switch = ($result_fav->num_rows > 0) ? "on" : "off";
$is_fav = ($switch == 'on');
$hidden = $is_owner ? 'hidden' : '';

// 9. AUTO-SAVE VIEWED
if ($rent_id && $user_id_login) {
    $check = $conn->prepare(
        "SELECT COUNT(*) AS already_viewed
         FROM `rent_views`
         WHERE `rent_id` = ? AND `user_id` = ? AND `date_viewed` = ?"
    );
    $check->bind_param("sss", $rent_id, $user_id_login, $datetoday);
    $check->execute();
    $already_viewed = $check->get_result()->fetch_assoc()['already_viewed'] ?? 0;

    if ($already_viewed == 0) {
        $insert_viewed = $conn->prepare(
            "INSERT INTO `rent_views` (`landlord_id`, `user_id`, `date_viewed`, `time_viewed`, `rent_id`)
             VALUES (?, ?, ?, ?, ?)"
        );
        $insert_viewed->bind_param("sssss", $landlord_id, $user_id_login, $datetoday, $timetoday1, $rent_id);
        $insert_viewed->execute();
    }
}

if(isset($_GET['location_back'])) {
    $location_back = $_GET['location_back'];
} else {
    $location_back = 'properties.php';
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title><?= htmlspecialchars(($property_name ? $property_name . ' - ' : '') . $name); ?></title>
    <link rel="shortcut icon" href="../assets/images/logo-icon.png" type="image/x-icon"> 
    
    <link rel="stylesheet" href="../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <script src="../assets/scripts/tailwind.js"></script>
    <script src="../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">

<?php include '../alerts.php'; ?>

<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
  
  <div class="drawer-content flex flex-col">
    
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
        <a href="<?php echo htmlspecialchars($location_back); ?>"><img src="../assets/images/back.png" alt="Back"></a>
      </div>

      <!-- HERO -->
      <div class="bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <div class="lg:col-span-2">
            <div class="relative h-64 sm:h-80 md:h-[360px] w-full rounded-xl overflow-hidden bg-base-300 group shadow-inner">
              <img src="../assets/uploads/<?= htmlspecialchars($image_cover); ?>" 
                   alt="<?= htmlspecialchars($name); ?>" 
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                   onerror="this.src='../assets/images/placeholder.jpg';" />
              
              <div class="absolute top-4 left-4">
                <span class="badge badge-success text-white badge-lg gap-2 shadow-md">
                  <i class="fa-solid fa-building"></i> <?= htmlspecialchars(ucfirst($type ?: 'Condo')); ?>
                </span>
              </div>

              <div class="absolute top-4 right-4 z-10 flex items-center gap-2">

                <!-- Report -->
                <div class="<?= $hidden; ?>">
                  <div class="tooltip tooltip-left" data-tip="Report">
                    <a href="report.php?user_id=<?= urlencode($user_id); ?>&location_back=condo_details.php&id=<?= urlencode($rent_id); ?>&report_type=Post"
                      aria-label="Report this post"
                      class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm text-emerald-700 shadow-md hover:bg-emerald-600 hover:text-white active:scale-95 transition-all">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                    </a>
                  </div>
                </div>

                <!-- Message landlord -->
                <div class="<?= $hidden; ?>">
                  <div class="tooltip tooltip-left" data-tip="Message Landlord">
                    <a href="chat_portal.php?id=<?= urlencode($user_id); ?>"
                      aria-label="Message landlord"
                      class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm text-emerald-700 shadow-md hover:bg-emerald-600 hover:text-white active:scale-95 transition-all">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="M7 11h10"/><path d="M7 15h6"/><path d="M7 7h8"/></svg>
                    </a>
                  </div>
                </div>

                <form action="../functions.php" method="POST" class="flex items-center gap-2">
                  <input type="hidden" name="type" value="<?= htmlspecialchars($type); ?>">
                  <input type="hidden" name="locate" value="condo_details.php">
                  <input type="hidden" name="rent_id" value="<?= htmlspecialchars($rent_id); ?>">
                  <input type="hidden" name="current_status" value="<?= htmlspecialchars($switch); ?>">
                  <input type="hidden" name="condo_id" value="<?= htmlspecialchars($condo_id); ?>">

                  <!-- Heart -->
                  <div class="tooltip tooltip-left" data-tip="Interested">
                    <button type="submit" name="add_favorite_btn" value="1"
                            aria-label="Interested"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm shadow-md hover:bg-red-50 active:scale-95 transition-all cursor-pointer border-none outline-none <?= $is_fav ? 'text-red-600' : 'text-slate-600 hover:text-red-600'; ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                          fill="<?= $is_fav ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/>
                      </svg>
                    </button>
                  </div>

                  <!-- House-heart -->
                  <div class="tooltip tooltip-left" data-tip="<?= $is_fav ? 'Remove from Interested' : 'Add to Interested'; ?>">
                    <button type="submit" name="add_interested_condo" value="1"
                            aria-label="<?= $is_fav ? 'Remove from Interested' : 'Add to Interested'; ?>"
                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/90 backdrop-blur-sm shadow-md hover:bg-red-50 active:scale-95 transition-all cursor-pointer border-none outline-none <?= $is_fav ? 'text-red-600' : 'text-slate-600 hover:text-red-600'; ?>">
                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.62 13.8A2.25 2.25 0 1 1 12 10.836a2.25 2.25 0 1 1 3.38 2.966l-2.626 2.856a.998.998 0 0 1-1.507 0z"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                    </button>
                  </div>
                </form>

              </div>
            </div>
          </div>

          <!-- Summary -->
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

              <p class="text-sm text-base-content/70 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot text-error"></i> 
                <?= htmlspecialchars(trim("$barangay, $municipality, $province", ", ")); ?>
              </p>

              <div class="divider my-3"></div>

              <div class="bg-primary/10 p-4 rounded-xl border border-primary/20">
                <span class="text-xs font-semibold text-success uppercase tracking-wider">Rental Rent</span>
                <div class="text-3xl font-black text-success mt-1">
                  ₱<?= number_format((float) $price, 2); ?> <span class="text-sm font-normal text-base-content/70">/ <?= htmlspecialchars($rate); ?></span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3 mt-4">
                <div class="stat bg-base-200/60 p-3 rounded-xl">
                  <div class="stat-title text-xs">Total Units</div>
                  <div class="stat-value text-xl"><?= $totalCondos; ?></div>
                </div>
                <div class="stat bg-base-200/60 p-3 rounded-xl">
                  <div class="stat-title text-xs">Available</div>
                  <div class="stat-value text-xl text-success"><?= $availableCondos; ?></div>
                </div>
              </div>
            </div>

            <div class="p-4 bg-base-200/50 rounded-xl border border-base-300 space-y-2">
              <p class="text-xs font-bold text-base-content/60 uppercase tracking-wider">Property Contact</p>
              <p class="text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-phone text-success"></i> <?= htmlspecialchars($contact_number ?: 'N/A'); ?>
              </p>
              <p class="text-sm font-semibold flex items-center gap-2 truncate">
                <i class="fa-solid fa-envelope text-success"></i> <?= htmlspecialchars($email ?: 'N/A'); ?>
              </p>
            </div>

            <!-- Prominent Interest Button -->
            <form action="../functions.php" method="POST" class="w-full">
              <input type="hidden" name="type" value="<?= htmlspecialchars($type); ?>">
              <input type="hidden" name="locate" value="condo_details.php">
              <input type="hidden" name="rent_id" value="<?= htmlspecialchars($rent_id); ?>">
              <input type="hidden" name="current_status" value="<?= htmlspecialchars($switch); ?>">
              <input type="hidden" name="condo_id" value="<?= htmlspecialchars($condo_id); ?>">
             <!-- <button type="submit" name="add_interested_condo" value="1"
                      class="btn w-full btn-<?= $is_fav ? 'error' : 'success' ?> text-white py-3 flex items-center justify-center gap-2 font-semibold"
                      <?= $is_owner ? 'disabled' : ''; ?>>
                <i class="fa-solid fa-heart"></i>
                <?= $is_fav ? 'Remove from Interested' : 'Mark as Interested'; ?>
              </button>-->
            </form>
          </div>

        </div>
      </div>

      <!-- DETAILS, UNITS & MAP -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Property
            </h2>
            <p class="text-base-content/80 text-sm leading-relaxed whitespace-pre-line">
              <?= $other_info ? $other_info : 'No description available for this space.'; ?>
            </p>
          </div>

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

          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold flex items-center gap-2">
                <i class="fa-solid fa-door-open text-success"></i> Property Units
              </h2>
              <span class="text-xs text-base-content/60"><?= $totalCondos; ?> Total Units</span>
            </div>

            <?php if (!empty($condo_units)): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($condo_units as $unit): ?>
                  <?php 
                    $u_status        = $unit['status'] ?? 'Available';
                    $u_num           = $unit['unit_no'] ?? $unit['condo_no'] ?? $unit['condo_id'] ?? 'Unit';
                    $u_price         = $unit['price'] ?? $price;
                    $square_area     = $unit['square_area'] ?? null;
                    $bedroom_type    = $unit['bedroom_type'] ?? null;
                    $bathrooms       = $unit['bathrooms'] ?? null;
                    $cond_condition  = $unit['cond_condition'] ?? null;
                    $flooring        = $unit['flooring'] ?? null;
                  ?>
                  <div class="border border-base-300 rounded-xl p-4 flex flex-col justify-between bg-base-100 hover:border-primary transition-all shadow-2xs gap-3">

                    <div class="flex items-center justify-between gap-2">
                      <h3 class="font-bold text-base truncate">Unit #<?= htmlspecialchars($u_num); ?></h3>
                      <span class="badge badge-sm <?= apartmentStatusBadge($u_status); ?>">
                        <?= htmlspecialchars($u_status); ?>
                      </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-base-content/80 bg-base-200/50 p-2.5 rounded-lg border border-base-200">
                      <?php if (!empty($square_area)): ?>
                        <div class="flex items-center gap-1.5" title="Square Area">
                          <i class="fa-solid fa-ruler-combined text-success text-[11px]"></i>
                          <span><?= htmlspecialchars($square_area); ?> sqm</span>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($bedroom_type)): ?>
                        <div class="flex items-center gap-1.5" title="Bedroom Type">
                          <i class="fa-solid fa-bed text-success text-[11px]"></i>
                          <span><?= htmlspecialchars($bedroom_type); ?></span>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($bathrooms)): ?>
                        <div class="flex items-center gap-1.5" title="Bathrooms">
                          <i class="fa-solid fa-bath text-success text-[11px]"></i>
                          <span><?= htmlspecialchars($bathrooms); ?> Bath<?= $bathrooms > 1 ? 's' : ''; ?></span>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($cond_condition)): ?>
                        <div class="flex items-center gap-1.5" title="Condition">
                          <i class="fa-solid fa-sparkles text-success text-[11px]"></i>
                          <span><?= htmlspecialchars($cond_condition); ?></span>
                        </div>
                      <?php endif; ?>

                      <?php if (!empty($flooring)): ?>
                        <div class="flex items-center gap-1.5 col-span-2" title="Flooring">
                          <i class="fa-solid fa-layer-group text-success text-[11px]"></i>
                          <span><?= htmlspecialchars($flooring); ?> Flooring</span>
                        </div>
                      <?php endif; ?>
                    </div>

                    <div class="pt-1 border-t border-base-200 flex items-center justify-between">
                      <span class="text-xs text-base-content/60">Rent:</span>
                      <p class="text-xs font-semibold text-success">
                        ₱<?= number_format((float)$u_price, 2); ?> <span class="font-normal text-base-content/60">/ mo</span>
                      </p>
                    </div>

                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-8 text-base-content/50">
                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2 block"></i>
                No units listed under this space yet.
              </div>
            <?php endif; ?>
          </div>

        </div>

        <div class="space-y-6">
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 sticky top-6">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-success"></i> Location Map
            </h2>
            <p class="text-xs text-base-content/60 mb-3">
              <?= htmlspecialchars(trim("$barangay, $municipality, $province", ", ")); ?>
            </p>
            
            <div id="map" class="w-full h-72 rounded-xl border border-base-300 z-10"></div>
          </div>
        </div>

      </div>

      <!-- ============================================ -->
      <!-- RATINGS & REVIEWS -->
      <!-- ============================================ -->
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
              <p class="text-sm">No comments yet. Be the first to review!</p>
            </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- GALLERY -->
      <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
        <div class="flex justify-between mb-6 pb-3 border-b border-base-200 flex-col lg:flex-row">
          <div class="mb-3">
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
          <div class="columns-2 sm:columns-3 md:columns-4 gap-4 space-y-4">
            <?php foreach ($apartment_images as $img): ?>
              <div class="break-inside-avoid rounded-xl overflow-hidden border border-base-200 shadow-2xs hover:shadow-md transition-all group bg-base-200 relative">
                <a href="../assets/uploads/<?= htmlspecialchars($img); ?>" target="_blank" class="block">
                  <img src="../assets/uploads/<?= htmlspecialchars($img); ?>" 
                       alt="Gallery Image" 
                       class="w-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
                       loading="lazy"
                       onerror="this.src='../assets/images/placeholder.jpg';" />
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

  <!-- SIDEBAR -->
  <div class="drawer-side z-40">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside class="bg-base-100 min-h-full w-80 p-5 border-r border-base-300 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-base-200">
          <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
            <img src="../assets/images/logo-icon.png" class="w-6 h-6 object-contain" alt="Logo" onerror="this.src='../assets/images/placeholder.jpg';">
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
          <li class="menu-title px-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
            Other Listings
          </li>

          <?php
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
                        <img src="../assets/uploads/<?= htmlspecialchars($other_img); ?>" 
                             alt="<?= htmlspecialchars($other_name); ?>" 
                             class="w-full h-full object-cover"
                             loading="lazy"
                             onerror="this.src='../assets/images/placeholder.jpg';" />
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

<script src="../assets/scripts/jquery.js"></script>
<script src="../assets/scripts/index.js"></script>

<script>
  $(document).ready(function() {
    var lat = <?= is_numeric($latitude) ? $latitude : '13.7565'; ?>;
    var lng = <?= is_numeric($longitude) ? $longitude : '121.0583'; ?>;
    var propertyName = <?= json_encode($property_name); ?>;
    var barangayName = <?= json_encode($barangay); ?>;

    var map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var popup = document.createElement('div');
    popup.innerHTML = '<b></b><br><span></span>';
    popup.querySelector('b').textContent = propertyName;
    popup.querySelector('span').textContent = barangayName;

    L.marker([lat, lng]).addTo(map).bindPopup(popup).openPopup();
  });
</script>

</body>
</html>