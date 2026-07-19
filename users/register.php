

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
    <title>Registration</title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <!--leaflet-- link-->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> 
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">

  <?php 
      include '../alerts.php'; 
  ?>




  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white"> Property Registration</div>
      </nav>
      <div class="p-0 lg:p-6">
        <!--main content-->
        <main>
            <section class="my-container">
              <form method="POST" action="../functions.php" class="py-[50px] shadow-xl p-[30px] lg:p-[50px] rounded-[20px] border border-gray-100" enctype="multipart/form-data">
              <input type="hidden" id="lat" name="latitude" value="<?php echo $_SESSION['latitud'] ?? ';' ?>" readonly required placeholder="Loading...">
              <input type="hidden" id="lng" value="<?php echo $_SESSION['longitude'] ?? ';' ?>" name="longitude" readonly required placeholder="Loading...">
                <h2 class="font-bold text-lg text-[#0fab9e] mb-5">REGISTRATION FORM</h2>
                  <p class="mb-3  ">Property Ownership & Media</p>
                  <div class="flex flex-col lg:flex-row gap-3 mb-5">
                    <!-- Proof of Ownership -->
                    <span class="w-[100%]">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-semibold">Proof of Ownership Document *</p>
                            <?php if (isset($_SESSION['owner_docs']) && is_array($_SESSION['owner_docs']) && !empty($_SESSION['owner_docs'])): ?>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-medium border border-green-200">
                                    ✓ Already Uploaded (<?php echo count($_SESSION['owner_docs']); ?> file/s)
                                </span>
                            <?php endif; ?>
                        </div>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-[100%]" id="owner_docs" name="owner_docs[]" multiple accept="image/jpeg, image/jpg" <?php echo isset($_SESSION['owner_docs']) ? '' : 'required'; ?> />
                        </label>
                        <p class="text-sm text-red-500">(Upload your Land Title, Deed of Absolute Sale, or notarized Management Authorization.)</p>
                    </span>

                    <!-- Photo Gallery -->
                    <span class="w-[100%]">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-semibold">Property Photo Gallery *</p>
                            <?php if (isset($_SESSION['photo_gallery']) && is_array($_SESSION['photo_gallery']) && !empty($_SESSION['photo_gallery'])): ?>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-medium border border-green-200">
                                    ✓ Already Uploaded (<?php echo count($_SESSION['photo_gallery']); ?> photo/s)
                                </span>
                            <?php endif; ?>
                        </div>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-[100%]" id="photo_gallery" name="photo_gallery[]" multiple accept="image/jpeg, image/jpg" <?php echo isset($_SESSION['photo_gallery']) ? '' : 'required'; ?> />
                        </label>
                        <p class="text-sm text-red-500">(Upload 3 to 10 high-quality photos showing the exterior and interior spaces.)</p>
                    </span>
                </div>
                  <p class="mb-3  ">Property Location</p>  
                  <div class="flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Province *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class=" text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="province" 
                                name="province"  
                                readonly   
                                value="<?php echo $_SESSION['province'] ?? '' ?>"                
                            />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Municipality *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class=" text-gray-500"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="municipality" 
                                name="municipality"  
                                readonly   
                              value="<?php echo $_SESSION['municipality'] ?? '' ?>"
                            />
                        </label>
                    </span>
                     <span class="w-[100%]">
                        <p class="mb-2 text-sm">Barangay *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="barangay" 
                                name="barangay"  
                                readonly   
                                  value="<?php echo $_SESSION['barangay'] ?? '  ' ?>"                 
                            />
                        </label>
                    </span>
                  </div>
                  <div class="flex justify-end mb-5">
                    <button type="button" class="btn btn-success text-white flex" onclick="my_modal_maps.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned-icon lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                    Locate
                  </button>
                  </div>
                   <p class="mb-3  ">Property Information</p>  
                  <div class="flex flex-col lg:flex-row gap-3 mb-10">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Type of Property *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-land-plot-icon lucide-land-plot"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                            <select class="select cursor-pointer w-[100%]" name="property_type">
                              <option value="<?php echo $_SESSION['property_type'] ?? 'Select Property type' ?>"><?php echo $_SESSION['property_type'] ?? 'Select Property type' ?></option>
                              <option value="Boarding House / Bedspace">Boarding House / Bedspace</option>
                              <option value="Apartment">Apartment</option>
                              <option value="Condominium (Condo)">Condominium (Condo)</option>
                              <option value="Townhouse / Rowhouse">Townhouse / Rowhouse</option>
                              <option value="Single-Family House">Single-Family House</option>
                              <option value="Studio Unit">Studio Unit</option>
                              <option value="Commercial House / Shophouse">Commercial House / Shophouse</option>
                              <option value="Retail Space">Retail Space</option>
                              <option value="Office Space">Office Space</option>
                              <option value="Coworking Space / Hot Desk">Coworking Space / Hot Desk</option>
                              <option value="Warehouse / Industrial Space">Warehouse / Industrial Space</option>
                              <option value="Event Space / Function Hall">Event Space / Function Hall</option>
                              <option value="Cloud Kitchen / Commissary">Cloud Kitchen / Commissary</option>
                              <option value="BStorage Unit">Storage Unit</option>
                              <option value="Vacation Rental (e.g., Airbnb / Transient House)">Vacation Rental (e.g., Airbnb / Transient House)</option>
                            </select>
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Property Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-land-plot-icon lucide-land-plot"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                            <input 
                                type="text" 
                                class="input grow w-[100%]" 
                                id="property_name" 
                                name="property_name"  
                                value="<?php echo $_SESSION['property_name'] ?? ''; ?>"   
                                placeholder="Enter Property Name"                  
                            />
                        </label>
                    </span>
                  </div>
                  <div class="text-center md:text-end">
                    <button type="submit" name="landlord_registration" class="btn btn-success text-white w-[100%] lg:w-fit">Submit</button>
                  </div>
              </form>
            </section>
        </main>

      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>



  <!--modal map-->
  <dialog id="my_modal_maps" class="modal">
    <div class="modal-box w-11/12 max-w-4xl">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>
         <div id="map" class="h-[500px] mt-5"></div>
    </div>
  </dialog>

  <!--leaflet script-->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>
  <script src="./../assets/scripts/index.js"></script>
</body>
</html>


  <script>
        // Initialize the map centered over a default location
        const map = L.map('map').setView([14.3292, 120.9367], 13); 

        // Add OpenStreetMap tile layers
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let activeMarker = null;

        // Function to update the form coordinate fields and manage the map marker
        function updateSelectedLocation(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);

            const latlng = [lat, lng];

            if (activeMarker) {
                activeMarker.setLatLng(latlng);
            } else {
                activeMarker = L.marker(latlng).addTo(map);
            }
            
            activeMarker.bindPopup(`<b>Selected Point</b><br>Lat: ${lat.toFixed(4)}<br>Lng: ${lng.toFixed(4)}`).openPopup();
            
            // Trigger address lookup (Reverse Geocoding)
            fetchAddressDetails(lat, lng);
        }

        // NEW FUNCTION: Fetches Barangay, Municipality/City, and Province using OpenStreetMap's Nominatim API
        function fetchAddressDetails(lat, lng) {
            // Set fields to a loading state while fetching
            document.getElementById('barangay').value = "Fetching...";
            document.getElementById('municipality').value = "Fetching...";
            document.getElementById('province').value = "Fetching...";

            const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.address) {
                        const addr = data.address;

                        // OpenStreetMap categorizes administrative regions dynamically. 
                        // This chain checks for the typical names used for Barangays, Cities, and Provinces.
                        const barangay = addr.neighbourhood || addr.suburb || addr.village || addr.quarter || addr.brgy || "";
                        const municipality = addr.city || addr.town || addr.municipality || "";
                        const province = addr.province || addr.state || "";

                        document.getElementById('barangay').value = barangay;
                        document.getElementById('municipality').value = municipality;
                        document.getElementById('province').value = province;
                        
                        // Update the map popup to display the address summary too
                        activeMarker.setPopupContent(`<b>${barangay || 'Selected Location'}</b><br>${municipality}, ${province}`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    document.getElementById('barangay').value = "Error loading";
                    document.getElementById('municipality').value = "Error loading";
                    document.getElementById('province').value = "Error loading";
                });
        }

        // --- MAP CLICK EVENT ---
        map.on('click', function(e) {
            updateSelectedLocation(e.latlng.lat, e.latlng.lng);
        });

        // --- SEARCH BAR SETUP ---
        const searchControl = new GeoSearch.GeoSearchControl({
            provider: new GeoSearch.OpenStreetMapProvider(),
            style: 'bar',
            showMarker: false,
            showPopup: false,
            marker: { icon: new L.Icon.Default(), draggable: false },
            maxMarkers: 1,
            retainZoomLevel: false,
            animateZoom: true,
            autoClose: true,
            searchLabel: 'Enter address or city...'
        });

        map.addControl(searchControl);

        // Listen for when a user selects a result from the search bar drop-down
        map.on('geosearch/showlocation', function(result) {
            const lat = result.location.y;
            const lng = result.location.x;
            updateSelectedLocation(lat, lng);
        });
    </script>