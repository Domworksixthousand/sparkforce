<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-base-100 min-h-screen flex flex-col">

  <?php include '../alerts.php'; ?>

  <div class="drawer lg:drawer-open flex-1">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col h-screen overflow-hidden">
      
      <!-- NAVBAR -->
      <nav class="navbar w-full bg-[#0fab9e] px-4 shrink-0">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Properties</div>
      </nav>

      <!-- MAIN CONTENT WRAPPER -->
      <main class="flex-1 flex flex-col overflow-hidden">

        <!-- UPPER HEADER BUTTONS (MOBILE & TABLET ONLY) -->
        <div class="flex justify-end p-4 gap-2 lg:hidden bg-white border-b border-slate-200">
            <button type="button" id="openMobileMapBtn" class="btn bg-[#009966] hover:bg-[#007a52] text-white btn-sm rounded-lg flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                View Map
            </button>
            <button type="button" id="openMobileFilterBtn" class="btn bg-[#009966] hover:bg-[#007a52] text-white btn-sm rounded-lg flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sliders-horizontal"><path d="M10 5H3"/><path d="M12 19H3"/><path d="M14 3v4"/><path d="M16 17v4"/><path d="M21 12h-9"/><path d="M21 19h-5"/><path d="M21 5h-7"/><path d="M8 10v4"/><path d="M8 12H3"/></svg>
                Filter
            </button>
        </div>
        
        <!-- HEADER SEARCH & FILTER BAR (DESKTOP ONLY) -->
        <header class="bg-white border-b border-slate-200 z-30 shadow-sm shrink-0 hidden lg:block">
            <form action="" method="GET" id="headerFilterForm" class="max-w-7xl mx-auto px-4 py-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-center">
                    
                    <div class="relative">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Location / Keyword</label>
                        <div class="relative">
                            <input type="text" name="location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>" class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500 focus:bg-white transition-all pl-8" placeholder="Enter Location, Barangay, or Name">
                            <svg class="w-4 h-4 absolute left-2.5 top-2.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Property Type -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Property Type</label>
                        <select name="property_type" class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500 focus:bg-white transition-all cursor-pointer">
                            <option value="">All Property Types</option>
                            <?php 
                              $types = ["Boarding House / Bedspace", "Apartment", "Condominium", "House", "Commercial Space", "Office Space", "Warehouse / Storage", "Event Space", "Transient House", "Parking Space", "Vacant Lot"];
                              $selected_type = $_GET['property_type'] ?? '';
                              foreach($types as $t) {
                                  $sel = ($selected_type === $t) ? 'selected' : '';
                                  echo "<option value=\"$t\" $sel>$t</option>";
                              }
                            ?>
                        </select>
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Min Price</label>
                        <input type="text" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>" class="numbers_only w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500 focus:bg-white transition-all" placeholder="₱ Min Price">
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Max Price</label>
                        <input type="text" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>" class="numbers_only w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500 focus:bg-white transition-all" placeholder="₱ Max Price">
                    </div>

                    <!-- Submit Action -->
                    <div class="flex items-end h-full pt-4 sm:pt-0">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-lg shadow-md transition-all flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search Spaces
                        </button>
                    </div>

                </div>
            </form>
        </header>

        <!-- SPLIT VIEW: LISTINGS + MAP -->
        <div class="flex-1 flex overflow-hidden relative">
            
            <!-- LEFT SIDE: SCROLLABLE LISTINGS -->
            <section class="w-full lg:w-[58%] xl:w-[50%] h-full overflow-y-auto listing-scroll p-4 lg:p-6 bg-slate-50">
                
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-200">
                    <div>
                        <h1 class="text-lg font-bold text-slate-800">Available Rental Spaces</h1>
                        <p class="text-xs text-slate-500">Explore properties matching your search</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="rentalCardsContainer">
                    <?php
                      
                        $loc_filter  = isset($_GET['location']) ? trim($_GET['location']) : '';
                        $type_filter = isset($_GET['property_type']) ? trim($_GET['property_type']) : '';
                        $min_p       = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
                        $max_p       = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? floatval($_GET['max_price']) : 0;

                        $query = "
                            SELECT r.*, l.province, l.municipality, l.barangay, l.property_name, l.latitude, l.longitude
                            FROM rentspace r
                            LEFT JOIN landlord l ON l.landlord_id = r.landlord_id
                            WHERE 1=1
                        ";

                        $params = [];
                        $types_str = "";

                        if(!empty($loc_filter)){
                            $query .= " AND (l.property_name LIKE ? OR l.barangay LIKE ? OR l.municipality LIKE ? OR l.province LIKE ? OR r.name LIKE ?)";
                            $searchTerm = "%" . $loc_filter . "%";
                            array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                            $types_str .= "sssss";
                        }

                        if(!empty($type_filter)){
                            $query .= " AND r.type = ?";
                            array_push($params, $type_filter);
                            $types_str .= "s";
                        }

                        if($min_p > 0){
                            $query .= " AND r.price >= ?";
                            array_push($params, $min_p);
                            $types_str .= "d";
                        }

                        if($max_p > 0){
                            $query .= " AND r.price <= ?";
                            array_push($params, $max_p);
                            $types_str .= "d";
                        }

                        $get_rental = $conn->prepare($query);
                        if (!empty($params)) {
                            $get_rental->bind_param($types_str, ...$params);
                        }
                        $get_rental->execute();
                        $result_rental = $get_rental->get_result();

                        $map_locations = [];

                        if ($result_rental->num_rows > 0) {
                            while ($row_rentals = $result_rental->fetch_assoc()) {
                                $name          = htmlspecialchars($row_rentals['name'] ?? '');
                                $rent_id       = htmlspecialchars($row_rentals['rent_id'] ?? '');
                                $type          = htmlspecialchars($row_rentals['type'] ?? '');
                                $image         = htmlspecialchars($row_rentals['image_cover'] ?? '');
                                $property_name = htmlspecialchars($row_rentals['property_name'] ?? 'Property Location');
                                $price         = $row_rentals['price'] ?? 0;
                                
                                $latitude      = !empty($row_rentals['latitude']) ? floatval($row_rentals['latitude']) : 12.703015;
                                $longitude     = !empty($row_rentals['longitude']) ? floatval($row_rentals['longitude']) : 124.037141;

                                $location = trim(
                                    ($row_rentals['barangay'] ?? '') . ', ' .
                                    ($row_rentals['municipality'] ?? '') . ', ' .
                                    ($row_rentals['province'] ?? ''),
                                    ', '
                                );
                                $location = htmlspecialchars($location);

                                $image_url = !empty($image)
                                    ? '../assets/uploads/' . $image
                                    : '../assets/images/background_cover.png';

                                $extention = in_array($type, ["Event Space", "Transient House", "Parking Space", "Vacant Lot"]) ? "Hour" : "Month";
                                $locate = ($type === "Boarding House / Bedspace") ? "boarding_details.php" : "apartment_details.php";

                                $map_locations[] = [
                                    'id'           => $rent_id,
                                    'title'        => $property_name,
                                    'name'         => $name,
                                    'price'        => number_format((float)$price, 2),
                                    'ext'          => $extention,
                                    'type'         => $type,
                                    'url'          => $locate . '?id=' . $rent_id,
                                    'image'        => $image_url,
                                    'lat'          => $latitude,
                                    'lng'          => $longitude,
                                    'barangay'     => htmlspecialchars($row_rentals['barangay'] ?? ''),
                                    'municipality' => htmlspecialchars($row_rentals['municipality'] ?? ''),
                                    'province'     => htmlspecialchars($row_rentals['province'] ?? '')
                                ];

                           

                               
                                if($type == "Boarding House / Bedspace"){
                                    $locate = "boarding_details.php";
                                }elseif($type == "Apartment"){
                                     $locate = "apartment_details.php";
                                }elseif($type == "Condominium"){
                                     $locate = "condo_details.php";
                                }elseif($type == "House"){
                                     $locate = "house_details.php";
                                }elseif($type == "Commercial Space"){
                                     $locate = "cs_details.php";
                                }

                                echo '
                                <div class="rental-card group bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col hover:-translate-y-1 cursor-pointer" data-id="' . $rent_id . '">
                                    <div class="relative h-44 overflow-hidden bg-slate-100">
                                        <img src="' . $image_url . '" alt="' . $property_name . '" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                        <span class="absolute top-2.5 left-2.5 text-[10px] font-bold tracking-wider uppercase bg-white/90 backdrop-blur-md text-emerald-800 px-2 py-0.5 rounded shadow-sm">
                                            ' . $type . '
                                        </span>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <a href="'.$locate.'?id=' . $rent_id .'" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg transition-colors">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                    <div class="p-3.5 flex flex-col flex-1">
                                        <h3 class="text-sm font-bold text-slate-900 truncate mb-0.5">' . $property_name . '</h3>
                                        <p class="text-xs text-slate-500 truncate mb-2">' . $name . '</p>
                                        <div class="flex items-center gap-1 text-[11px] text-slate-500 mb-3 truncate">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="truncate">' . ($location !== '' ? $location : 'Location not set') . '</span>
                                        </div>
                                        <div class="mt-auto pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                            <div>
                                                <span class="text-xs text-slate-400 block font-normal">Rent Rate</span>
                                                <p class="text-sm font-extrabold text-emerald-600">
                                                    &#8369;' . number_format((float)$price, 2) . ' 
                                                    <span class="text-[10px] text-slate-400 font-normal">/ ' . $extention . '</span>
                                                </p>
                                            </div>
                                            <a href="'.$locate.'?id=' . $rent_id .'" class="lg:hidden text-xs font-semibold text-emerald-600 hover:text-emerald-800 flex items-center gap-0.5">
                                                More
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '
                            <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-400 bg-white rounded-xl border border-slate-200">
                                <p class="text-sm font-semibold text-slate-700">No properties matched your search</p>
                                <p class="text-xs text-slate-400 mt-1">Try adjusting your location or price filters.</p>
                            </div>';
                        }
                    ?>
                </div>
            </section>

            <!-- RIGHT SIDE: STICKY LEAFLET MAP (DESKTOP ONLY) -->
            <section class="hidden lg:block lg:w-[42%] xl:w-[50%] h-full p-4 relative bg-slate-100 border-l border-slate-200">
                <div id="propertyMap" class="w-full h-full rounded-xl shadow-md z-0"></div>
            </section>

        </div>
      </main>
    </div>

    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>

  <!-- ========================================== -->
  <!-- MOBILE FILTER MODAL / DRAWER -->
  <!-- ========================================== -->
  <dialog id="mobileFilterModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-white p-6">
      <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
        <h3 class="font-bold text-base text-slate-800">Filter Properties</h3>
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost">✕</button>
        </form>
      </div>
      
      <form action="" method="GET" class="space-y-4">
        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Location / Keyword</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>" class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500" placeholder="Enter Location, Barangay, or Name">
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-600 mb-1">Property Type</label>
            <select name="property_type" class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500">
                <option value="">All Property Types</option>
                <?php 
                  foreach($types as $t) {
                      $sel = ($selected_type === $t) ? 'selected' : '';
                      echo "<option value=\"$t\" $sel>$t</option>";
                  }
                ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Min Price</label>
                <input type="text" name="min_price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>" class="numbers_only w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500" placeholder="₱ Min Price">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Max Price</label>
                <input type="text" name="max_price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>" class="numbers_only w-full bg-slate-100 border border-slate-200 rounded-lg px-3 py-2 text-xs font-medium focus:outline-none focus:border-emerald-500" placeholder="₱ Max Price">
            </div>
        </div>

        <div class="pt-4 flex items-center gap-2">
            <a href="?" class="btn btn-outline border-slate-300 btn-sm flex-1">Reset</a>
            <button type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 text-white btn-sm flex-1">Apply Filter</button>
        </div>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button>close</button>
    </form>
  </dialog>

  <!-- ========================================== -->
  <!-- MOBILE MAP MODAL -->
  <!-- ========================================== -->
  <dialog id="mobileMapModal" class="modal p-0">
    <div class="modal-box w-full max-w-none h-full max-h-none rounded-none p-0 flex flex-col bg-white">
      <div class="p-4 bg-[#0fab9e] text-white flex items-center justify-between shrink-0 shadow-md">
        <h3 class="font-bold text-sm flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
          Interactive Rental Map
        </h3>
        <form method="dialog">
          <button class="btn btn-sm btn-circle btn-ghost text-white">✕</button>
        </form>
      </div>
      <div class="flex-1 w-full h-full relative">
        <div id="mobilePropertyMap" class="w-full h-full"></div>
      </div>
    </div>
  </dialog>


  <script src="../assets/scripts/map.js"></script>
  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>

  <!-- LEAFLET MAP SCRIPT -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const properties = <?php echo json_encode($map_locations, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
      const defaultLat = 12.703015;
      const defaultLng = 124.037141;

      const customIcon = L.icon({
          iconUrl: '../assets/images/home.png',
          iconSize: [30, 30],
          iconAnchor: [15, 30],
          popupAnchor: [0, -30]
      });


      let map = null;
      const desktopMapEl = document.getElementById('propertyMap');

      if (desktopMapEl) {
          map = L.map('propertyMap').setView([defaultLat, defaultLng], 13);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; OpenStreetMap contributors'
          }).addTo(map);

          setTimeout(() => { map.invalidateSize(); }, 300);
      }

      const markers = [];

      properties.forEach(p => {
          if (!p.lat || !p.lng) return;

          const popupContent = `
              <div class="p-1 font-sans">
                  <img src="${p.image}" class="w-full h-20 object-cover rounded mb-2" loading="lazy" alt="${p.title}">
                  <h4 class="font-bold text-xs truncate">${p.title}</h4>
                  <p class="text-[11px] text-emerald-600 font-extrabold">&#8369;${p.price} / ${p.ext}</p>
                  <a href="${p.url}" class="block text-center bg-emerald-600 !text-white text-[10px] font-bold py-1 rounded mt-2">View Details</a>
              </div>
          `;

          if (map) {
              const marker = L.marker([p.lat, p.lng], { icon: customIcon })
                  .addTo(map)
                  .bindPopup(popupContent);

              marker._propertyData = p;
              markers.push(marker);
          }
      });

      if (map) {
          if (markers.length > 1) {
              const group = L.featureGroup(markers);
              map.fitBounds(group.getBounds().pad(0.2));
          } else if (markers.length === 1) {
              map.setView([properties[0].lat, properties[0].lng], 16);
              markers[0].openPopup();
          }
      }

      // Card click handling for Desktop FlyTo
      document.querySelectorAll('.rental-card').forEach(card => {
          card.addEventListener('click', (e) => {
              if (e.target.closest('a')) return;

              const cardId = card.getAttribute('data-id');
              if (map) {
                  const targetMarker = markers.find(m => m._propertyData.id == cardId);
                  if (targetMarker) {
                      map.flyTo([targetMarker._propertyData.lat, targetMarker._propertyData.lng], 16, { duration: 0.8 });
                      targetMarker.openPopup();
                  }
              }
          });
      });

      // =====================================
      // 2. MOBILE FILTER & MAP MODAL LOGIC
      // =====================================
      const openFilterBtn = document.getElementById('openMobileFilterBtn');
      const filterModal = document.getElementById('mobileFilterModal');

      const openMapBtn = document.getElementById('openMobileMapBtn');
      const mapModal = document.getElementById('mobileMapModal');

      if (openFilterBtn && filterModal) {
          openFilterBtn.addEventListener('click', () => {
              filterModal.showModal();
          });
      }

      let mobileMap = null;
      if (openMapBtn && mapModal) {
          openMapBtn.addEventListener('click', () => {
              mapModal.showModal();

              // Initialize mobile map once modal is visible
              if (!mobileMap) {
                  mobileMap = L.map('mobilePropertyMap').setView([defaultLat, defaultLng], 13);
                  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                      attribution: '&copy; OpenStreetMap contributors'
                  }).addTo(mobileMap);

                  const mobileMarkers = [];
                  properties.forEach(p => {
                      if (!p.lat || !p.lng) return;

                      const popupContent = `
                          <div class="p-1 font-sans">
                              <img src="${p.image}" class="w-full h-20 object-cover rounded mb-2" loading="lazy" alt="${p.title}">
                              <h4 class="font-bold text-xs truncate">${p.title}</h4>
                              <p class="text-[11px] text-emerald-600 font-extrabold">&#8369;${p.price} / ${p.ext}</p>
                              <a href="${p.url}" class="block text-center bg-emerald-600 !text-white text-[10px] font-bold py-1 rounded mt-2">View Details</a>
                          </div>
                      `;

                      const mMarker = L.marker([p.lat, p.lng], { icon: customIcon })
                          .addTo(mobileMap)
                          .bindPopup(popupContent);

                      mobileMarkers.push(mMarker);
                  });

                  if (mobileMarkers.length > 1) {
                      const group = L.featureGroup(mobileMarkers);
                      mobileMap.fitBounds(group.getBounds().pad(0.2));
                  } else if (mobileMarkers.length === 1) {
                      mobileMap.setView([properties[0].lat, properties[0].lng], 16);
                      mobileMarkers[0].openPopup();
                  }
              }

              // Invalidate size to ensure proper tiles rendering inside modal
              setTimeout(() => {
                  mobileMap.invalidateSize();
              }, 200);
          });
      }
  });
  </script>
</body>
</html>