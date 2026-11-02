<?php 
include 'config.php';

$rent_id = $_GET['id'] ?? '';

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, landlord_id, user_id, type, rate
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name = $price = $image_cover = $other_info = $landlord_id = $user_id = $type = $rate = "";

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

// APARTMENT UNITS
$apartments = [];
$get_apartment = $conn->prepare("SELECT * FROM `apartment` WHERE `rent_id` = ?");
$get_apartment->bind_param("s", $rent_id);
$get_apartment->execute();
$result_execute = $get_apartment->get_result();
while ($row_apar = $result_execute->fetch_assoc()) {
    $apartments[] = $row_apar;
}

$totalApartments      = count($apartments);
$availableApartments  = count(array_filter($apartments, fn($item) => $item['status'] === 'Available'));

// GALLERY
$apartment_images = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();
while ($row_gallery = $result_gallery->fetch_assoc()) {
    $apartment_images[] = $row_gallery['image'];
}

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

function apartmentStatusBadge($status) {
    switch ($status) {
        case 'Available': return 'badge-success text-white';
        case 'Occupied':
        case 'Not Available': return 'badge-error text-white';
        case 'Out of Order': return 'badge-warning text-white';
        default: return 'badge-neutral';
    }
}

// LANDLORD DETAILS
$get_landlord_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
$get_landlord_info->bind_param("s", $landlord_id);
$get_landlord_info->execute();
$result_landlord = $get_landlord_info->get_result();

$province = $municipality = $barangay = $longitude = $latitude = $property_name = "";
if ($row_landlord = $result_landlord->fetch_assoc()) {
    $province      = $row_landlord['province'];
    $municipality  = $row_landlord['municipality'];
    $barangay      = $row_landlord['barangay'];
    $longitude     = $row_landlord['longitude'];
    $latitude      = $row_landlord['latitude'];
    $property_name = $row_landlord['property_name'];
}

// ACCOUNT DETAILS
$account = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
$account->bind_param("s", $user_id);
$account->execute();
$result_account = $account->get_result();

$contact_number = $email = "";
if ($row_account = $result_account->fetch_assoc()) {
    $contact_number = $row_account['contact_number'];
    $email          = $row_account['email'];
}

// ============================================================
// RATINGS & REVIEWS
// ============================================================
$get_avg = $conn->prepare("SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM `reviews` WHERE `landlord_id` = ?");
$get_avg->bind_param("s", $landlord_id);
$get_avg->execute();
$avg_row      = $get_avg->get_result()->fetch_assoc();
$avg_rating   = (float)($avg_row['avg_rating'] ?? 0);
$review_count = (int)($avg_row['review_count'] ?? 0);

// star distribution (5 -> 1)
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

// Can the current visitor review? (logged in, and not the owner)
$current_user = $user_id_login ?? ($_SESSION['user_login'] ?? '');
$can_review   = !empty($current_user) && $current_user !== $user_id;

$has_reviewed = false;
if (!empty($current_user)) {
    $chk = $conn->prepare("SELECT 1 FROM `reviews` WHERE `landlord_id` = ? AND `user_id` = ?");
    $chk->bind_param("ss", $landlord_id, $current_user);
    $chk->execute();
    $has_reviewed = $chk->get_result()->num_rows > 0;
}

// star renderer
function renderStars($rating, $size = 'text-sm') {
    $html = '<span class="inline-flex items-center gap-0.5 ' . $size . '">';
    for ($i = 1; $i <= 5; $i++) {
        $cls = ($i <= round($rating)) ? 'fa-solid text-warning' : 'fa-regular text-base-content/30';
        $html .= '<i class="' . $cls . ' fa-star"></i>';
    }
    return $html . '</span>';
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title><?= htmlspecialchars($property_name . '-' . $name); ?></title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        /* clickable star rating input (right-to-left trick) */
        .star-input { display:inline-flex; flex-direction:row-reverse; gap:4px; }
        .star-input input { display:none; }
        .star-input label { font-size:1.75rem; cursor:pointer; color:#d1d5db; transition:color .15s; }
        .star-input label:hover,
        .star-input label:hover ~ label,
        .star-input input:checked ~ label { color:#facc15; }
    </style>
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">

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
        <a href="index.php"><img src="assets/images/back.png" alt="Back"></a>
      </div>

      <!-- HERO -->
      <div class="bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <div class="lg:col-span-2">
            <div class="relative h-64 sm:h-80 md:h-[360px] w-full rounded-xl overflow-hidden bg-base-300 group shadow-inner">
              <img src="assets/uploads/<?= htmlspecialchars($image_cover); ?>" 
                   alt="<?= htmlspecialchars($name); ?>" 
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                   onerror="this.src='assets/images/placeholder.jpg';" />
              <div class="absolute top-4 left-4">
                <span class="badge badge-success text-white badge-lg gap-2 shadow-md">
                  <i class="fa-solid fa-building"></i> Apartment
                </span>
              </div>
            </div>
          </div>

          <div class="flex flex-col justify-between space-y-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-extrabold text-base-content">
                <?= htmlspecialchars($name); ?>
              </h1>

              <!-- rating summary -->
              <a href="#reviews" class="flex items-center gap-2 mt-1">
                <?= renderStars($avg_rating); ?>
                <span class="text-sm font-semibold"><?= $review_count ? number_format($avg_rating, 1) : 'No ratings'; ?></span>
                <?php if ($review_count): ?>
                  <span class="text-xs text-base-content/60">(<?= $review_count; ?> review<?= $review_count > 1 ? 's' : ''; ?>)</span>
                <?php endif; ?>
              </a>

              <p class="text-sm text-base-content/70 mt-2 flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot text-error"></i> 
                <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
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
                  <div class="stat-value text-xl"><?= $totalApartments; ?></div>
                </div>
                <div class="stat bg-base-200/60 p-3 rounded-xl">
                  <div class="stat-title text-xs">Available</div>
                  <div class="stat-value text-xl text-success"><?= $availableApartments; ?></div>
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
          </div>

        </div>
      </div>

      <!-- DETAILS -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Apartment
            </h2>
            <p class="text-base-content/80 text-sm leading-relaxed whitespace-pre-line">
              <?= $other_info ? htmlspecialchars($other_info) : 'No description available for this apartment space.'; ?>
            </p>
          </div>

          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <i class="fa-solid fa-square-check text-success"></i> Apartment Features & Amenities
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
                <i class="fa-solid fa-door-open text-success"></i> Apartment Units
              </h2>
              <span class="text-xs text-base-content/60"><?= count($apartments); ?> Total Units</span>
            </div>

            <?php if (!empty($apartments)): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($apartments as $apar): ?>
                  <?php 
                    $apar_status = $apar['status'] ?? 'Available';
                    $apar_price  = $apar['price'] ?? $price;
                  ?>
                  <div class="border border-base-300 rounded-xl p-4 flex gap-4 items-center bg-base-100 hover:border-primary transition-all shadow-2xs">
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between gap-1">
                        <h3 class="font-bold text-base truncate"><?= htmlspecialchars($name); ?></h3>
                        <span class="badge badge-sm <?= apartmentStatusBadge($apar_status); ?>">
                          <?= htmlspecialchars($apar_status); ?>
                        </span>
                      </div>
                      <p class="text-xs font-semibold text-success mt-1">
                        ₱<?= number_format((float)$apar_price, 2); ?> <span class="font-normal text-base-content/60">/ mo</span>
                      </p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-8 text-base-content/50">
                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2 block"></i>
                No apartment units listed under this space yet.
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
              <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
            </p>
            <div id="map" class="w-full h-72 rounded-xl border border-base-300 z-10"></div>
          </div>
        </div>

      </div>

      <!-- ============================================ -->
      <!-- RATINGS & REVIEWS -->
      <!-- ============================================ -->
      <div id="reviews" class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 scroll-mt-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 pb-3 border-b border-base-200">
          <h2 class="text-xl font-bold flex items-center gap-2">
            <i class="fa-solid fa-star text-warning"></i> Ratings & Reviews
          </h2>
          <span class="badge badge-success badge-outline font-semibold"><?= $review_count; ?> Review<?= $review_count === 1 ? '' : 's'; ?></span>
        </div>

        <!-- summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="flex flex-col items-center justify-center bg-base-200/60 rounded-xl p-6">
            <div class="text-5xl font-black"><?= $review_count ? number_format($avg_rating, 1) : '0.0'; ?></div>
            <div class="mt-2"><?= renderStars($avg_rating, 'text-lg'); ?></div>
            <p class="text-xs text-base-content/60 mt-1">Based on <?= $review_count; ?> review<?= $review_count === 1 ? '' : 's'; ?></p>
          </div>

          <div class="md:col-span-2 space-y-2 self-center">
            <?php foreach ($star_counts as $star => $cnt): 
                $pct = $review_count ? round(($cnt / $review_count) * 100) : 0; ?>
              <div class="flex items-center gap-3 text-sm">
                <span class="w-10 flex items-center gap-1 font-semibold"><?= $star; ?> <i class="fa-solid fa-star text-warning text-xs"></i></span>
                <progress class="progress progress-warning flex-1" value="<?= $pct; ?>" max="100"></progress>
                <span class="w-10 text-right text-xs text-base-content/60"><?= $cnt; ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- write review -->
        <div class="mb-8">
          <?php if (empty($current_user)): ?>
            <div class="alert bg-base-200 text-sm">
              <i class="fa-solid fa-circle-info text-success"></i>
              <span><a href="signin.php" class="link link-success font-semibold">Sign in</a> to leave a rating and comment.</span>
            </div>
          <?php elseif (!$can_review): ?>
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
            <form action="functions.php" method="POST" class="border border-base-300 rounded-xl p-4 space-y-4 bg-base-100">
              <input type="hidden" name="landlord_id" value="<?= htmlspecialchars($landlord_id); ?>">
              <input type="hidden" name="rent_id" value="<?= htmlspecialchars($rent_id); ?>">
              <input type="hidden" name="location_back" value="apartment_details.php">

              <h3 class="font-bold text-base">Write a review</h3>

              <div>
                <label class="block text-xs font-semibold text-base-content/70 mb-1">Your rating <span class="text-error">*</span></label>
                <div class="star-input">
                  <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5"><i class="fa-solid fa-star"></i></label>
                  <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4"><i class="fa-solid fa-star"></i></label>
                  <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3"><i class="fa-solid fa-star"></i></label>
                  <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2"><i class="fa-solid fa-star"></i></label>
                  <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1"><i class="fa-solid fa-star"></i></label>
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
        </div>

        <!-- reviews list -->
        <?php if (!empty($reviews)): ?>
          <div class="space-y-4">
            <?php foreach ($reviews as $rv): ?>
              <div class="flex gap-4 p-4 border border-base-200 rounded-xl">
                <div class="avatar placeholder flex-shrink-0">
                  <div class="bg-success text-white rounded-full w-11 h-11 flex items-center justify-center">
                    <span class="text-sm font-bold"><?= htmlspecialchars($rv['initials']); ?></span>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="flex flex-wrap items-center justify-between gap-1">
                    <p class="font-semibold text-sm truncate"><?= htmlspecialchars($rv['name']); ?></p>
                    <span class="text-xs text-base-content/50"><?= htmlspecialchars($rv['date']); ?></span>
                  </div>
                  <div class="mt-0.5"><?= renderStars($rv['rating'], 'text-xs'); ?></div>
                  <p class="text-sm text-base-content/80 mt-2 whitespace-pre-line break-words"><?= htmlspecialchars($rv['comment']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-10 text-base-content/50 bg-base-200/50 rounded-xl">
            <i class="fa-regular fa-comment-dots text-4xl mb-2 block text-base-content/30"></i>
            <p class="text-sm font-medium">No reviews yet. Be the first to review!</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- GALLERY -->
      <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
        <div class="flex justify-between mb-6 pb-3 border-b border-base-200 flex-col lg:flex-row">
          <div class="mb-3 lg:mb-0">
            <h2 class="text-xl font-bold flex items-center gap-2 text-base-content">
              <i class="fa-solid fa-images text-success"></i> Photo Gallery
            </h2>
            <p class="text-xs text-base-content/60 mt-0.5">Explore photos of the apartment property and surrounding areas</p>
          </div>
          <span class="badge badge-success badge-outline font-semibold">
            <?= count($apartment_images); ?> Photos
          </span>
        </div>

        <?php if (!empty($apartment_images)): ?>
          <div class="columns-2 sm:columns-3 md:columns-4 gap-4 space-y-4 mb-5">
            <?php foreach ($apartment_images as $img): ?>
              <div class="break-inside-avoid rounded-xl overflow-hidden border border-base-200 shadow-2xs hover:shadow-md transition-all group bg-base-200 relative">
                <a href="assets/uploads/<?= htmlspecialchars($img); ?>" target="_blank" class="block">
                  <img src="assets/uploads/<?= htmlspecialchars($img); ?>" 
                       alt="Gallery Image" 
                       class="w-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer"
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

  <!-- SIDEBAR -->
  <div class="drawer-side z-40">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside class="bg-base-100 min-h-full w-80 p-5 border-r border-base-300 flex flex-col justify-between">
      <div>
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-base-200">
          <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
            <img src="assets/images/logo-icon.png" class="w-6 h-6 object-contain" alt="Logo" onerror="this.src='assets/images/placeholder.png';">
          </div>
          <div class="min-w-0 flex-1">
            <h1 class="text-2xl md:text-3xl font-extrabold text-base-content">
              <?= htmlspecialchars(mb_strlen($name) > 22 ? mb_substr($name, 0, 22) . '...' : $name); ?>
            </h1>
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
                    <a href="?id=<?= urlencode($other_id); ?>" 
                       class="flex items-center gap-3 p-2.5 rounded-xl transition-all <?= $isActive; ?>">
                      <div class="w-11 h-11 rounded-lg overflow-hidden bg-base-300 flex-shrink-0 border border-base-200">
                        <img src="assets/uploads/<?= htmlspecialchars($other_img); ?>" 
                             alt="<?= htmlspecialchars($other_name); ?>" 
                             class="w-full h-full object-cover"
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
          ?>
        </ul>
      </div>

      <div class="border-t border-base-200 pt-4 mt-6">
        <div class="flex items-center justify-between text-xs text-base-content/60 px-1">
          <span>© <?= date('Y'); ?> RentHub</span>
          <span class="badge badge-ghost badge-xs">v1.0</span>
        </div>
      </div>
    </aside>
  </div>
</div>

<script src="assets/scripts/jquery.js"></script>
<script src="assets/scripts/index.js"></script>

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