
<?php 
include '../config.php';

if(isset($_GET['id'])){
    $rent_id = $_GET['id'];
}

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, landlord_id, user_id, type
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name = $price = $image_cover = $other_info = $landlord_id = $user_id = $type = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
    $landlord_id = $rent_row['landlord_id'];
    $user_id     = $rent_row['user_id'];
    $type        = $rent_row['type'];

    
}

// APARTMENT UNITS
$apartments = [];
$get_apartment = $conn->prepare("SELECT * FROM `apartment` WHERE `rent_id` = ?");
$get_apartment->bind_param("s", $rent_id);
$get_apartment->execute();
$result_execute = $get_apartment->get_result();

if ($result_execute->num_rows > 0) {
    while ($row_apar = $result_execute->fetch_assoc()) {
        $apartments[] = $row_apar;
    }
}

$totalApartments      = count($apartments);
$availableApartments  = count(array_filter($apartments, fn($item) => $item['status'] === 'Available'));
$occupiedApartments   = count(array_filter($apartments, fn($item) => $item['status'] === 'Not Available' || $item['status'] === 'Occupied'));
$outOfOrderApartments = count(array_filter($apartments, fn($item) => $item['status'] === 'Out of Order'));

// APARTMENT IMAGES / GALLERY
$apartment_images = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

if ($result_gallery->num_rows > 0) {
    while ($row_gallery = $result_gallery->fetch_assoc()) {
        $apartment_images[] = $row_gallery['image'];
    }
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

// Helper function for status badges
function apartmentStatusBadge($status) {
    switch ($status) {
        case 'Available':
            return 'badge-success text-white';
        case 'Occupied':
        case 'Not Available':
            return 'badge-error text-white';
        case 'Out of Order':
            return 'badge-warning text-white';
        default:
            return 'badge-neutral';
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

//favorites
$favorites = $conn->prepare("SELECT * FROM `favorites` WHERE `rent_id` = ?");
$favorites->bind_param("s", $rent_id);
$favorites->execute();
$result_fav = $favorites->get_result();
if($result_fav->num_rows>0){
  $switch = "on";
}else{
  $switch = "off";
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property_name . '-' . $name; ?></title>
     <link rel="shortcut icon" href="../assets/images/logo-icon.png" type="image/x-icon"> 
    <!-- CSS & Frameworks -->
    <link rel="stylesheet" href="../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <script src="../assets/scripts/tailwind.js"></script>
    <script src="../assets/scripts/daisy_ui.js"></script>

    <!-- FontAwesome & Leaflet Map -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-base-200 min-h-screen text-base-content antialiased">


<div class="drawer lg:drawer-open">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
  
  <!-- MAIN CONTENT CONTAINER -->
  <div class="drawer-content flex flex-col">
    
    <!-- Mobile Top Header -->
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
        <a href="properties.php"><img src="../assets/images/back.png"></a>
    </div>
      <!-- HERO BANNER / COVER SECTION -->
      <div class="bg-base-100 rounded-2xl p-4 md:p-6 shadow-sm border border-base-300">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- Cover Image -->
          <div class="lg:col-span-2">
            <div class="relative h-64 sm:h-80 md:h-[360px] w-full rounded-xl overflow-hidden bg-base-300 group shadow-inner">
              <img src="../assets/uploads/<?= htmlspecialchars($image_cover); ?>" 
                   alt="<?= htmlspecialchars($name); ?>" 
                   class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                   onerror="this.src='../assets/images/placeholder.jpg';" />
              <div class="absolute top-4 left-4">
                <span class="badge badge-success text-white badge-lg gap-2 shadow-md">
                  <i class="fa-solid fa-building"></i> Apartment
                </span>
              </div>
              <form action="../functions.php" method="POST" class="tooltip tooltip-left absolute top-4 right-4" data-tip="<?php echo ($switch == 'on') ? 'Remove from Favorites' : 'Add to my Favorites'; ?>">

              <input type="hidden" name="type" value="<?php echo $type; ?>">
              <input type="hidden" name="locate" value="boarding_details.php">
              <input type="hidden" name="rent_id" value="<?php echo $rent_id; ?>">
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
          </div>

          <!-- Quick Summary Side Panel -->
          <div class="flex flex-col justify-between space-y-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-extrabold text-base-content">
                <?= htmlspecialchars($name); ?>
              </h1>
              <p class="text-sm text-base-content/70 mt-1 flex items-center gap-1.5">
                <i class="fa-solid fa-location-dot text-error"></i> 
                <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
              </p>

              <div class="divider my-3"></div>

              <!-- Price Box -->
              <div class="bg-primary/10 p-4 rounded-xl border border-primary/20">
                <span class="text-xs font-semibold text-success uppercase tracking-wider">Monthly Rent</span>
                <div class="text-3xl font-black text-success mt-1">
                  ₱<?= number_format((float)$price, 2); ?> <span class="text-sm font-normal text-base-content/70">/ month</span>
                </div>
              </div>

              <!-- Apartment Units Overview Stats -->
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

            <!-- Landlord Contact Box -->
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

      <!-- DETAILS, UNITS & MAP SECTION -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2-Cols: Info, Amenities, & Apartment Units -->
        <div class="lg:col-span-2 space-y-6">

          <!-- About Apartment -->
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-circle-info text-success"></i> About This Apartment
            </h2>
            <p class="text-base-content/80 text-sm leading-relaxed whitespace-pre-line">
              <?= htmlspecialchars($other_info ?: 'No description available for this apartment space.'); ?>
            </p>
          </div>

          <!-- Amenities List -->
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

          <!-- Apartment Units List -->
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
                    $apar_num    = $apar['apartment_no'] ?? $apar['apartment_id'] ?? 'Unit';
                    $apar_img    = $apar['image'] ?? '';
                    $apar_price  = $apar['price'] ?? $price;
                  ?>
                  <div class="border border-base-300 rounded-xl p-4 flex gap-4 items-center bg-base-100 hover:border-primary transition-all shadow-2xs">


                    <!-- Unit Info -->
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between gap-1">
                        <h3 class="font-bold text-base truncate">Unit #<?= htmlspecialchars($apar_num); ?></h3>
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

        <!-- Right 1-Col: Leaflet Map -->
        <div class="space-y-6">
          <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300 sticky top-6">
            <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
              <i class="fa-solid fa-map-location-dot text-success"></i> Location Map
            </h2>
            <p class="text-xs text-base-content/60 mb-3">
              <?= htmlspecialchars("$barangay, $municipality, $province"); ?>
            </p>
            
            <!-- Map Element -->
            <div id="map" class="w-full h-72 rounded-xl border border-base-300 z-10"></div>
          </div>
        </div>

      </div>

      <!-- MASONRY GALLERY SECTION (LOWER PART) -->
      <div class="bg-base-100 p-6 rounded-2xl shadow-sm border border-base-300">
        <div class="flex  justify-between mb-6 pb-3 border-b border-base-200 flex-col lg:flex-row ">
          <div class="mb-3 ">
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
          <!-- CSS Masonry Grid Layout -->
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
                <!-- Hover Overlay -->
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

  <!-- SIDEBAR (OTHER ROOMS / APARTMENTS) -->
  <div class="drawer-side z-40">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
    <aside class="bg-base-100 min-h-full w-80 p-5 border-r border-base-300 flex flex-col justify-between">
      <div>
        <!-- Brand Header -->
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-base-200">
          <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0">
            <img src="../assets/images/logo-icon.png" class="w-6 h-6 object-contain" alt="Logo" onerror="this.src='assets/images/placeholder.png';">
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

        <!-- Sidebar Navigation List -->
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

                  $isActive = ($other_id == $rent_id) ? 'active bg-success text-white  font-semibold' : 'hover:bg-base-200 text-base-content';
                  ?>
                  <li>
                    <a href="?id=<?= urlencode($other_id); ?>" 
                       class="flex items-center gap-3 p-2.5 rounded-xl transition-all <?= $isActive; ?>">
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

      <!-- Footer -->
      <div class="border-t border-base-200 pt-4 mt-6">
        <div class="flex items-center justify-between text-xs text-base-content/60 px-1">
          <span>© <?= date('Y'); ?> RentSpace</span>
          <span class="badge badge-ghost badge-xs">v1.0</span>
        </div>
      </div>
    </aside>
  </div>
</div>

<!-- Scripts -->
<script src="../assets/scripts/jquery.js"></script>
<script src="../assets/scripts/index.js"></script>

<!-- Leaflet Map Setup -->
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

  function openApartmentModal(apartmentId) {
    console.log("Selected Apartment ID:", apartmentId);
  }
</script>

</body>
</html>