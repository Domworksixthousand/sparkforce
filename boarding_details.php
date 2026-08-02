<?php 

include 'config.php'; 
if(isset($_GET['id'])){
    $rent_id = $_GET['id'];
}

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, landlord_id, user_id,type
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

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
    $landlord_id = $rent_row['landlord_id'];
    $user_id     = $rent_row['user_id'];
    $type     = $rent_row['type'];
}

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

// Helper: Badge color per bed status
function bedStatusBadge($status) {
    switch ($status) {
        case 'Available':    return 'badge-success text-white';
        case 'Occupied':     return 'badge-error text-white';
        case 'Out of Order': return 'badge-ghost';
        default:             return 'badge-ghost';
    }
}

// LANDLORD DETAILS
$get_landlord_info = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
$get_landlord_info->bind_param("s", $landlord_id);
$get_landlord_info->execute();
$result_landlord = $get_landlord_info->get_result();

$province = $municipality = $barangay = $longitude = $latitude = $property_name = "";
if($result_landlord->num_rows > 0){
    if($row_landlord = mysqli_fetch_assoc($result_landlord)){
        $province      = $row_landlord['province'];
        $municipality  = $row_landlord['municipality'];
        $barangay      = $row_landlord['barangay'];
        $longitude     = $row_landlord['longitude'];
        $latitude      = $row_landlord['latitude'];
        $property_name = $row_landlord['property_name'];
    }
}

// ACCOUNT DETAILS
$account = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
$account->bind_param("s", $user_id);
$account->execute();
$result_account = $account->get_result();

$contact_number = $email = "";
if($result_account->num_rows > 0){
    if($row_account = mysqli_fetch_assoc($result_account)){
        $contact_number = $row_account['contact_number'];
        $email          = $row_account['email'];
    }
}

$totalBeds     = count($boarding_houses);
$availableBeds = count(array_filter($boarding_houses, fn($b) => $b['status'] === 'Available'));
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property_name . " - " . $name); ?></title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <!-- Leaflet Map Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">

<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
  
  <!-- MAIN CONTENT AREA -->
  <div class="drawer-content flex flex-col">
    
    <!-- Top Mobile Navbar -->
    <header class="flex items-center py-3 px-2 bg-base-100 shadow-sm  lg:hidden sticky top-0 z-30">
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
            <a href="index.php"><img src="assets/images/back.png"></a>
        </div>
      <!-- HERO BANNER / IMAGE GALLERY -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        
        <!-- Image Container -->
        <div class="lg:col-span-2 relative h-64 sm:h-80 md:h-[400px] w-full rounded-xl overflow-hidden bg-base-300 group">
          <img src="assets/uploads/<?= htmlspecialchars($image_cover); ?>" 
               alt="<?= htmlspecialchars($name); ?>" 
               class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
               onerror="this.src='assets/images/placeholder.jpg';" />
          <div class="absolute top-4 left-4">
            <span class="badge badge-success text-white badge-lg gap-2 shadow-md">
              <i class="fa-solid fa-house"></i> Boarding House / Bed Space
            </span>
          </div>
        </div>

        <!-- Quick Summary & Pricing Side Panel -->
        <div class="flex flex-col justify-between space-y-4">
          <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-base-content">
              <?= htmlspecialchars($name); ?>
            </h1>
            <p class="text-sm text-base-content/70 mt-1 flex items-center gap-1">
              <i class="fa-solid fa-location-dot text-error"></i> 
              <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
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

        <!-- Left 2-Cols: Info, Amenities, & Beds -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Description / Info -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Space
            </h2>
            <p class="text-base-content/80 text-sm leading-relaxed whitespace-pre-line">
              <?= htmlspecialchars($other_info ?: 'No additional details provided.'); ?>
            </p>
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
              <span class="text-xs text-base-content/60"><?= count($boarding_houses); ?> Units Listed</span>
            </div>

            <?php if (!empty($boarding_houses)): ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($boarding_houses as $bh): ?>
                  <div class="border border-base-300 rounded-xl p-4 flex gap-4 items-center bg-base-100 hover:border-primary transition-all">
                    <!-- Bed Image -->
                    <div class="w-20 h-20 rounded-lg overflow-hidden bg-base-200 flex-shrink-0">
                      <img src="assets/uploads/<?= htmlspecialchars($bh['image']); ?>" 
                           alt="Bed <?= $bh['bed_number']; ?>" 
                           class="w-full h-full object-cover"
                           onerror="this.src='assets/images/placeholder-bed.jpg';" />
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

                      <!-- Action Button 
                      <div class="mt-3">
                        <?php //if ($bh['status'] === 'Available'): ?>
                          <button class="btn btn-primary btn-xs w-full" onclick="openRoomModal('<?= $bh['boarding_id']; ?>')">
                            Book / Reserve
                          </button>
                        <?php //else: ?>
                          <button class="btn btn-disabled btn-xs w-full" disabled>
                            Unavailable
                          </button>
                        <?php //endif; ?>
                      </div>-->
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="text-center py-8 text-base-content/50">
                <i class="fa-solid fa-bed text-3xl mb-2 block"></i>
                No active beds or units found for this room space.
              </div>
            <?php endif; ?>
          </div>

        </div>

        <!-- Right 1-Col: Map View -->
        <div class="space-y-6">
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 sticky top-6">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-success"></i> Location Map
            </h2>
            <p class="text-xs text-base-content/60 mb-3">
              <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
            </p>
            
            <!-- Map Wrapper -->
            <div id="map" class="w-full h-72 rounded-xl border border-base-300 z-10"></div>
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
          <img src="assets/images/logo-icon.png" class="w-6 h-6 object-contain" alt="Logo" onerror="this.src='assets/images/placeholder.png';">
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
          WHERE `landlord_id` = ? AND `type` = ?
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
                      <img src="assets/uploads/<?= htmlspecialchars($other_img); ?>" 
                           alt="<?= htmlspecialchars($other_name); ?>" 
                           class="w-full h-full object-cover"
                           onerror="this.src='assets/images/placeholder.jpg';" />
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

<!-- SCRIPTS -->
<script src="assets/scripts/jquery.js"></script>
<script src="assets/scripts/index.js"></script>

<!-- Leaflet Map Initialization -->
<script>
  $(document).ready(function() {
    var lat = <?= !empty($latitude) ? $latitude : '13.7565'; ?>;
    var lng = <?= !empty($longitude) ? $longitude : '121.0583'; ?>;
    var propertyName = <?= json_encode($property_name); ?>;

    var map = L.map('map').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
      .bindPopup('<b>' + propertyName + '</b><br><?= htmlspecialchars($barangay); ?>')
      .openPopup();
  });
</script>

</body>
</html>