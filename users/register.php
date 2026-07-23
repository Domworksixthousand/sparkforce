

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
                              <option value="Storage Unit">Storage Unit</option>
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
                  <div class="mb-8 p-4 bg-base-200/50 border border-base-300 rounded-xl">
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms_agree" 
                            name="terms_agree" 
                            class="checkbox checkbox-primary me-3 mt-1 size-5 shrink-0" 
                            <?= (isset($_SESSION['terms_agree1']) && $_SESSION['terms_agree1'] == 1) ? 'checked' : ''; ?>
                            required 
                        />
                        <label for="terms_agree" class="text-sm text-gray-600 leading-relaxed cursor-pointer select-none">
                            I certify that all details and identity verification documents provided are accurate. 
                            I explicitly agree to the 
                            <span class="text-primary font-semibold underline cursor-pointer" onclick="landlord_terms_modal.showModal()">Terms and Conditions for Landlords</span>. *
                        </label>
                    </div>
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

  <!--tearms and regulations -->
  

<dialog id="landlord_terms_modal" class="modal modal-bottom sm:modal-middle">

  <div class="modal-box max-w-2xl bg-base-100 p-6 rounded-xl border border-base-300">
  <form method="dialog" class="mb-10">
    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
  </form>
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-base-300 pb-3 mb-4">
      <h3 class="text-xl font-black text-success tracking-wide">RENTSPACE</h3>
      <span class="badge badge-outline font-semibold">Landlord Terms</span>
    </div>

    <h2 class="text-lg font-extrabold text-base-content mb-2">Terms and Conditions for Landlords</h2>
    <p class="text-xs text-base-content/60 mb-4">Please read carefully before activating your Landlord Centre account.</p>

    <!-- Scrollable Terms Container -->
    <div class="max-h-72 overflow-y-auto pr-2 space-y-4 text-sm text-base-content/80 bg-base-200 p-4 rounded-lg border border-base-300">
      
      <div>
        <h4 class="font-bold text-base-content">1. Account Eligibility & Verification</h4>
        <p class="text-xs mt-1">To list properties on RentSpace, you must be at least 18 years old and provide a valid government ID upon request. You are fully responsible for all listings and actions managed under your account.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">2. Property Listings & Accuracy</h4>
        <p class="text-xs mt-1">All uploaded descriptions, room rates, and real-time imagery must be accurate. Uploading misleading content or wrong availability data will result in immediate room layout suspension.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">3. Booking & Conflict Prevention</h4>
        <p class="text-xs mt-1">You must process schedules diligently to avoid booking overlaps. Rejecting confirmed reservations repeatedly without valid reasons may penalize your listing visibility in our system finder.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">4. Conduct & Tenant Safety</h4>
        <p class="text-xs mt-1">You must strictly maintain clean, safe, and livable lodging standards following local building laws. RentSpace maintains strict rules against illegal profiling and any form of tenant discrimination.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">5. Platform Policies & Direct Deals</h4>
        <p class="text-xs mt-1">RentSpace reserves the right to enforce transaction processing rules. Closing agreements offline deliberately to circumvent platform logic after discovering tenants via RentSpace is strictly prohibited.</p>
      </div>

    </div>


  </div>
</dialog>




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
  <script src="./../assets/scripts/map.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>


