<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>RENTSPACE</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <link rel="stylesheet" href="assets/styles/splide.css">
    <script src="assets/scripts/cool_alert.js"></script>
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>

  <!---alert-->
  <?php 
      include 'alerts.php'; 
  ?>


<!--header ini-->
  <header class=" relative ">
    <nav
     class="navbar  lg:container  lg:px-[30px]   glass fixed top-0 left-1/2 -translate-x-1/2 z-50  transition-all mt-0  lg:mt-[20px] rounded-0 lg:rounded-lg">
      <div class="navbar-start">
        <div class="dropdown">
          <div tabindex="0" role="button" class="btn btn-ghost lg:hidden " aria-label="Toggle Navigation">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </div>
          <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-3 shadow-xl bg-base-100 rounded-box w-64 gap-2 border border-base-200">
            <li><a href="#properties">Properties</a></li>
            <li><a href="#aboutus">About Us</a></li>
            <li><a href="#map_rentals">Map</a></li>
            <li><a href="#works">How It Works</a></li>
            <div class="divider my-1"></div>
            <li><a href="signin.php" class="btn btn-ghost btn-sm">Sign In</a></li>
            <li><a href="signup.php" class="btn bg-[#14b8a6] btn-sm text-white">Get Started</a></li>
          </ul>
        </div>
        <a href="#" class="  flex flex-row">
          <img src="assets/images/logo-icon.png" class="w-[50px] me-2" loading="lazy">
          <div class="flex flex-col mt-2">  
            <p class="nav_li cursive-text m-0 p-0 text-sm font-bold text-white">RENTSPACE</p>
            <p class="nav_li m-0 p-0 text-sm text-white">Find Your Places</p>
          </div>
        </a>
      </div>
      <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-1 text-[15px] font-medium text-base-content/80">
          <li><a href="#properties" class="nav_li hover:text-success text-white transition-colors">Properties</a></li>
          <li><a href="#aboutus" class="nav_li hover:text-success transition-colors text-white">About Us</a></li>
          <li><a href="#map_rentals" class=" nav_li hover:text-success text-white transition-colors">Map</a></li>
          <li><a href="#works" class=" nav_li hover:text-success text-white transition-colors">How It Works</a></li>
        </ul>
      </div>
      <div class="navbar-end gap-2 ">
        <a href="signin.php" class="nav_li btn btn-ghost p-[7px] rounded-sm text-sm bg-transparent text-white hover:bg-[#14b8a6] btn-sm hidden lg:inline-flex font-semibold border-0">
          Sign In
        </a>
        <a href="signup.php" class="btn bg-[#14b8a6] border-0  shadow-md shadow-primary/20 text-white font-bold px-5 btn-sm hidden lg:inline-flex ">
          Get Started
        </a>
      </div>
    </nav>
    <div class="banner w-full m-0 p-0 relative">
        <div class="absolute inset-0 bg-black/50 z-10 pointer-events-none"></div>
        <div class="absolute inset-0 z-20 flex items-center justify-center text-white px-4">
          <div class="container max-w-3xl flex flex-col items-center text-center gap-4">
            <p class="text-[#47e5d4] p-3 rounded-full glass font-semibold tracking-wide text-sm  bg-[#163b43]">
              Find Your Perfect Stay
            </p> 
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
              Discover Your Ideal Rental Space
            </h1>
            <p class="text-gray-200 text-base md:text-lg max-w-2xl font-light">
              From cozy boarding houses to luxury apartments — browse thousands of verified rental properties tailored to your needs and budget.
            </p>
            <form method="GET" action="filter.php" class="bg-white mt-[20px] rounded-[20px] w-[100%] py-[23px] px-[18px] ">
              <div class="flex flex-col lg:flex-row mb-3">
                <div class="text-start w-[100%] lg:me-3 mb-3 lg:mb-0 ">
                    <span class="flex  flex-col lg:flex-row">
                    <input type="text" name="min" class="numbers_only input border-gray-300  mb-3 lg:mb-0 lg:me-3 w-[100%] rounded-[10px]  placeholder:text-black text-black" placeholder="Price Min">
                    <input type="text" name="max"  class="numbers_only input  border-gray-300 w-[100%] placeholder:text-black rounded-[10px] text-black" placeholder="Price Max">
                    </span>x
                </div>
                <div class="text-start w-[100%]">
                    <span>
                    <select class="select w-[100%]  text-black rounded-[10px]" name="type" >
                        <option disabled selected>Property Type</option>
                        <option value="Boarding House / Bedspace">Boarding House / Bedspace</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Condominium">Condominium (Condo)</option>
                        <option value="House">House (Single-Family, Townhouse, Duplex)</option>
                        <option value="Commercial Space">Commercial Space (Retail, Shophouse, Cloud Kitchen)</option>
                        <option value="Event Space">Event Space (Function Hall, Venue)</option>
                        <option value="Transient House">Transient House (Vacation Rental, Airbnb)</option>
                        <option value="Parking Space">Parking Space (Garage, Carport)</option>
                        <option value="Vacant Lot">Vacant Lot (Residential, Commercial, Agricultural)</option>
                    </select>
                    </span>
                </div>
              </div>
              <div>
                <button type="submit" name="" class="btn bg-[#0d9488] w-[100%] rounded-[10px] text-white">
                    <img src="assets/images/magnifier-icon.png " class="w-[20px]" loading="lazy"> 
                    Filter
                </button>
              </div>
            </form>
          </div>
        </div>
        <img src="assets/images/banner-img.jpg" class="w-full object-cover block m-0 p-0 h-[50rem] " loading="lazy">
    </div>
  </header>

  <main>
    <section class="my-container pt-[130px] pb-[50px] px-[10px] lg:px-[0px] " id="properties">
        <div class="flex justify-center items-center text-center flex-col   w-[100%] ">
          <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Property Categories</p>
          <h1 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-4">Find the Perfect Property Type</h1>
          <p>Whether you need a short-term stay or long-term rental, browse through our diverse range of verified property types.</p>
        </div>
        <div class="pt-[100px]">
        <!-- Section Header -->
        <!-- Main Carousel Container -->
        <div class="splide max-w-6xl w-full mx-auto px-10 relative group" aria-label="Accommodations Carousel">
        
        <!-- Track Container -->
        <div class="splide__track py-4 relative">
          <div class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-white via-white/80 to-transparent z-10 pointer-events-none"></div>
          <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white via-white/80 to-transparent z-10 pointer-events-none"></div>
          <ul class="splide__list">
          
            <!-- Card 1 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/king-size.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Boarding House / Bed Space
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>

            <!-- Card 2 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/living-room.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Transient House 
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>

            <!-- Card 3 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/residential.png" class="w-10 h-10 object-contain drop-shadow" alt="Boarding House" loading="lazy">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Apartment
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>

            <!-- Card 4 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/condominium.png" loading="lazy" class="w-10 h-10 object-contain drop-shadow" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Condominium
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>

            <!-- Card 5 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/house.png" loading="lazy" class="w-10 h-10 object-contain drop-shadow" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  House 
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>


              <!-- Card 8 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/property.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Commercial Space 
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>


              <!-- Card 9 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/event.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Event Space
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>


              <!-- Card 10 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/car.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Parking Space 
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>


              <!-- Card 11 -->
            <li class="splide__slide px-2">
              <a href="#" class="flex flex-col items-center bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group/card relative overflow-hidden">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center mb-4 group-hover/card:scale-110 transition-transform duration-300 shadow-inner">
                  <img src="assets/images/plant.png" class="w-10 h-10 object-contain drop-shadow" loading="lazy" alt="Boarding House">
                </div>
                <p class="text-slate-700 group-hover/card:text-green-700 font-semibold text-sm text-center leading-snug mb-3 transition-colors">
                  Vacant Lot 
                </p>
                <div class="bg-slate-100 group-hover/card:bg-green-100/80 h-1.5 w-16 rounded-full overflow-hidden transition-colors">
                  <div class="bg-green-500 h-full w-2/3 rounded-full transition-all duration-300 group-hover/card:w-full"></div>
                </div>
              </a>
            </li>


          </ul>
        </div>

        

        <!-- Glassmorphism Custom Arrows -->
        <div class="splide__arrows">
          <button class="splide__arrow splide__arrow--prev !-left-4 !bg-white/90 backdrop-blur-md !shadow-lg !border !border-slate-200/80 hover:!bg-white z-20">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="rotate-[180deg] origin-center lucide lucide-step-back-icon lucide-step-back">
              <path d="M13.971 4.285A2 2 0 0 1 17 6v12a2 2 0 0 1-3.029 1.715l-9.997-5.998a2 2 0 0 1-.003-3.432z"/>
              <path d="M21 20V4"/>
            </svg>
          </button>
          <button class="splide__arrow splide__arrow--next !-right-4 !bg-white/90 backdrop-blur-md !shadow-lg !border !border-slate-200/80 hover:!bg-white z-20">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-step-forward-icon lucide-step-forward">
              <path d="M10.029 4.285A2 2 0 0 0 7 6v12a2 2 0 0 0 3.029 1.715l9.997-5.998a2 2 0 0 0 .003-3.432z"/>
              <path d="M3 4v16"/>
            </svg>
          </button>
        </div>

        </div>
    </section>
    <section class="my-container py-[100px] px-[10px] lg:px-[0px] " id="featured_properties" >
        <div class="flex justify-center items-center lg:justify-start lg:items-start flex-col   w-[100%] mb-10">
          <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Featured Properties</p>
          <h1 class="font-bold text-[1.5rem]  w-fit mb-4">Hand-Picked Rental Spaces</h1>
          <p>Top-rated properties curated for quality and comfort</p>
        </div>
          <div class="overflow-auto mb-15">
            <ul class="flex gap-5 p-5" id="filterTabs">
              <li><button data-filter="all" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-white rounded-[15px] bg-emerald-600">All&nbsp;Spaces</button></li>
              <li><button data-filter="Boarding House / Bedspace" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Boarding&nbsp;House&nbsp;/&nbsp;Bedspace</button></li>
              <li><button data-filter="Condominium" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Condominium</button></li>
              <li><button data-filter="Apartment" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Apartment</button></li>
              <li><button data-filter="Condominium" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Condominium</button></li>
              <li><button data-filter="House" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">House</button></li>
              <li><button data-filter="Commercial Space" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Commercial&nbsp;Space</button></li>
              <li><button data-filter="Transient House" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Transient&nbsp;House</button></li>
              <li><button data-filter="Parking Space" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Parking&nbsp;Space</button></li>
              <li><button data-filter="Vacant Lot" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Vacant&nbsp;Lot</button></li>
              <li><button data-filter="Event Space" class="filter-btn p-3 cursor-pointer text-[0.7rem] lg:text-[0.8rem] text-black rounded-[15px] bg-[#f3f2ee]">Event&nbsp;Space</button></li>
            </ul>
          </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-5">
          <?php

          $limit = 12;
          $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
          if ($page < 1) { $page = 1; }
          $offset = ($page - 1) * $limit;

        
          $total_query = $conn->query("SELECT COUNT(*) AS total FROM rentspace");
          $total_row = $total_query->fetch_assoc();
          $total_rentals = $total_row['total'];
          $total_pages = ceil($total_rentals / $limit);

        
          $get_rental = $conn->prepare("
              SELECT r.*, l.province, l.municipality, l.barangay, l.property_name,r.rate
              FROM rentspace r
              LEFT JOIN landlord l ON l.landlord_id = r.landlord_id
              LIMIT ? OFFSET ?
          ");
          $get_rental->bind_param("ii", $limit, $offset);
          $get_rental->execute();
          $result_rental = $get_rental->get_result();

          if ($result_rental->num_rows > 0) {
              while ($row_rentals = $result_rental->fetch_assoc()) {
                  $name         = htmlspecialchars($row_rentals['name'] ?? '');
                  $rent_id      = htmlspecialchars($row_rentals['rent_id'] ?? '');
                  $landlord_id  = htmlspecialchars($row_rentals['landlord_id'] ?? '');
                  $type         = htmlspecialchars($row_rentals['type'] ?? '');
                  $image        = htmlspecialchars($row_rentals['image_cover'] ?? '');
                  $property_name = htmlspecialchars($row_rentals['property_name'] ?? '');
                  $rate = htmlspecialchars($row_rentals['rate'] ?? '');
                  $price        = $row_rentals['price'] ?? 0;

                  $location = trim(
                      ($row_rentals['barangay'] ?? '') . ', ' .
                      ($row_rentals['municipality'] ?? '') . ', ' .
                      ($row_rentals['province'] ?? ''),
                      ', '
                  );
                  $location = htmlspecialchars($location);

                  $image_url = !empty($image)
                      ? 'assets/uploads/' . $image
                      : 'assets/images/background_cover.png';

             
                  $locate = "house_details.php"; // Default fallback
                  if ($type === "Boarding House / Bedspace") {
                      $locate = "boarding_details.php";
                  } elseif ($type === "Apartment") {
                      $locate = "apartment_details.php";
                  } elseif ($type === "Condominium") {
                      $locate = "condo_details.php";
                  } elseif ($type === "House") {
                      $locate = "house_details.php";
                  }elseif($type === "Event Space"){
                       $locate = "es_details.php";
                  }

                  echo '
                  <div class="rental-card group relative flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                  data-type="' . $type . '">

                      <!-- Image -->
                      <div class="relative h-48 overflow-hidden">
                          <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                              style="background-image:url(\'' . $image_url . '\');" loading="lazy">
                          </div>
                          <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                          <span class="absolute top-3 left-3 text-[0.65rem] font-medium tracking-wide uppercase bg-white/90 backdrop-blur-sm text-emerald-700 px-2.5 py-1 rounded-full shadow-sm">
                              ' . $type . '
                          </span>
                          <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                              <a href="' . $locate . '?id=' . $rent_id . '"
                                class="bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-full shadow-md hover:bg-emerald-600 hover:text-white transition-colors">
                                  View Details
                              </a>
                          </div>
                      </div>

                      <!-- Content -->
                      <div class="flex flex-col flex-1 p-4">
                          <p class="text-sm font-bold text-black truncate mb-1">' . $property_name . '</p>
                          <h2 class="text-sm text-gray-500 truncate mb-1">
                              ' . $name . '
                          </h2>

                          <div class="flex items-center gap-1 text-xs text-gray-500 mb-3 truncate">
                              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                  <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                  <circle cx="12" cy="10" r="3"/>
                              </svg>
                              <span class="truncate">' . ($location !== '' ? $location : 'Location not set') . '</span>
                          </div>

                          <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-100">
                              <p class="text-sm font-bold text-emerald-600">
                                  &#8369;' . number_format((float)$price, 2) . '
                                  <span class="text-xs font-normal text-gray-400">/ ' . $rate . '</span>
                              </p>
                          </div>
                      </div>

                  </div>
                  ';
              }
          } else {
              echo '
              <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3">
                  <path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11"/>
                  <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z"/>
                  <path d="M6 13h12"/>
                  <path d="M6 17h12"/>
                </svg>
                <p class="text-sm font-medium">No rentals found</p>
                <p class="text-xs text-gray-400 mt-1">New listings will appear here once added.</p>
              </div>
              ';
          }
          ?>
      </div>

      <!-- PAGINATION NAVIGATION UI -->
      <?php if ($total_pages > 1): ?>
      <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-2xl shadow-sm mb-6">
          <!-- Mobile Pagination -->
          <div class="flex flex-1 justify-between sm:hidden">
              <?php if ($page > 1): ?>
                  <a href="?page=<?php echo $page - 1; ?>#featured_properties" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
              <?php else: ?>
                  <span class="relative inline-flex items-center rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Previous</span>
              <?php endif; ?>

              <?php if ($page < $total_pages): ?>
                  <a href="?page=<?php echo $page + 1; ?>#featured_properties" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
              <?php else: ?>
                  <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed">Next</span>
              <?php endif; ?>
          </div>
          
          <!-- Desktop Pagination -->
          <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
              <div>
                  <p class="text-sm text-gray-700">
                      Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to 
                      <span class="font-medium"><?php echo min($offset + $limit, $total_rentals); ?></span> of 
                      <span class="font-medium"><?php echo $total_rentals; ?></span> results
                  </p>
              </div>
              <div>
                  <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm gap-1" aria-label="Pagination">
                      <!-- Previous Button -->
                      <?php if ($page > 1): ?>
                          <a href="?page=<?php echo $page - 1; ?>#featured_properties" class="relative inline-flex items-center rounded-lg px-3 py-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                              <span class="sr-only">Previous</span>
                              &#8249;
                          </a>
                      <?php endif; ?>

                      <!-- Page Numbers -->
                      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                          <a href="?page=<?php echo $i; ?>#featured_properties" class="relative inline-flex items-center px-3.5 py-2 text-xs font-semibold rounded-lg transition-colors <?php echo $i === $page ? 'bg-emerald-600 text-white' : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-600'; ?>">
                              <?php echo $i; ?>
                          </a>
                      <?php endfor; ?>

                      <!-- Next Button -->
                      <?php if ($page < $total_pages): ?>
                          <a href="?page=<?php echo $page + 1; ?>#featured_properties" class="relative inline-flex items-center rounded-lg px-3 py-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                              <span class="sr-only">Next</span>
                              &#8250;
                          </a>
                      <?php endif; ?>
                  </nav>
              </div>
          </div>
      </div>
      <?php endif; ?>
    <div id="noResultsMsg" class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400" style="display:none;">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3">
        <circle cx="11" cy="11" r="8"/>
        <path d="m21 21-4.3-4.3"/>
      </svg>
      <p class="text-sm font-medium">No properties found</p>
      <p class="text-xs text-gray-400 mt-1">Try selecting a different category.</p>
    </div>
    </section>
    <section class="my-container py-[50px] pb-[100px] px-[10px] lg:px-[0px]" id="map_rentals">
      <div class="flex justify-center items-center   flex-col   w-[100%] mb-10">
        <h1 class="font-bold text-[1.5rem]  w-fit mb-4">Map View of All Rental Spaces</h1>
      </div>
      <div>
        <div class="relative mb-4">
        <div id="propertyMap" class="w-full h-[500px] rounded-xl shadow-md  z-0"></div>

        <!-- Search bar floating inside the map -->
          <div class="absolute top-3 left-1/2 -translate-x-1/2 z-0 w-full max-w-sm px-3 ">
            <div class="relative">
              <input 
                type="text" 
                id="mapSearchInput" 
                placeholder="Search property name or location..." 
                class="w-full text-sm px-4 py-2.5 pl-10 rounded-full shadow-md border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                autocomplete="off"
              />
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
              </svg>

              <div id="mapSearchResults" class="absolute mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hidden max-h-60 overflow-y-auto"></div>
            </div>
          </div>
        </div>
      </div>
        <?php
        $stat = "Approved";
        $get_all = $conn->prepare("SELECT * FROM `landlord` WHERE `status` = ?");
        $get_all->bind_param("s", $stat);
        $get_all->execute();
        $result_all = $get_all->get_result();

        $properties = [];
        if ($result_all->num_rows > 0) {
            while ($row_ll = mysqli_fetch_assoc($result_all)) {
                $properties[] = [
                    'lat'      => !empty($row_ll['latitude']) ? floatval($row_ll['latitude']) : null,
                    'lng'      => !empty($row_ll['longitude']) ? floatval($row_ll['longitude']) : null,
                    'name'     => htmlspecialchars($row_ll['property_name'] ?? 'Property Location'),
                    'province' => htmlspecialchars($row_ll['province'] ?? ''),
                    'municipality' => htmlspecialchars($row_ll['municipality'] ?? ''),
                    'barangay' => htmlspecialchars($row_ll['barangay'] ?? ''),
                ];
            }
        }
        ?>

      <script>
      document.addEventListener('DOMContentLoaded', () => {
          const properties = <?php echo json_encode($properties); ?>;

          const defaultLat = 12.703015;
          const defaultLng = 124.037141;

          let initialLat = defaultLat;
          let initialLng = defaultLng;
          for (const p of properties) {
              if (p.lat !== null && p.lng !== null) {
                  initialLat = p.lat;
                  initialLng = p.lng;
                  break;
              }
          }

          const map = L.map('propertyMap').setView([initialLat, initialLng], 16);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; OpenStreetMap contributors'
          }).addTo(map);

          const customIcon = L.icon({
              iconUrl: 'assets/images/home.png',
              iconSize: [30, 30],
              iconAnchor: [19, 38],
              popupAnchor: [0, -38]
          });

          const markers = [];

          properties.forEach(p => {
              if (p.lat === null || p.lng === null) return;

              const marker = L.marker([p.lat, p.lng], { icon: customIcon })
                  .addTo(map)
                  .bindPopup('<b>' + p.name + '</b>');

              marker._propertyData = p;
              markers.push(marker);
          });

          if (markers.length > 1) {
              const group = L.featureGroup(markers);
              map.fitBounds(group.getBounds().pad(0.2));
          } else if (markers.length === 1) {
              markers[0].openPopup();
          }

          // ---- SEARCH BAR LOGIC ----
          const searchInput = document.getElementById('mapSearchInput');
          const resultsBox = document.getElementById('mapSearchResults');

          function buildLocationLabel(p) {
              return [p.barangay, p.municipality, p.province].filter(Boolean).join(', ');
          }

          searchInput.addEventListener('input', () => {
              const query = searchInput.value.trim().toLowerCase();

              if (query === '') {
                  resultsBox.classList.add('hidden');
                  resultsBox.innerHTML = '';
                  return;
              }

              const matches = properties.filter(p => {
                  const haystack = (p.name + ' ' + buildLocationLabel(p)).toLowerCase();
                  return haystack.includes(query) && p.lat !== null && p.lng !== null;
              });

              if (matches.length === 0) {
                  resultsBox.innerHTML = '<div class="px-4 py-3 text-sm text-gray-400">No matching properties</div>';
                  resultsBox.classList.remove('hidden');
                  return;
              }

              resultsBox.innerHTML = matches.map((p, i) => `
                  <div class="map-search-item px-4 py-2.5 text-sm cursor-pointer hover:bg-emerald-50 border-b border-gray-50 last:border-0" data-index="${properties.indexOf(p)}">
                      <p class="font-medium text-gray-800 truncate">${p.name}</p>
                      <p class="text-xs text-gray-400 truncate">${buildLocationLabel(p) || 'Location not set'}</p>
                  </div>
              `).join('');

              resultsBox.classList.remove('hidden');
          });

          // click on a suggestion -> fly to marker + open popup
          resultsBox.addEventListener('click', (e) => {
              const item = e.target.closest('.map-search-item');
              if (!item) return;

              const index = parseInt(item.dataset.index, 10);
              const p = properties[index];

              const targetMarker = markers.find(m => m._propertyData === p);

              if (targetMarker) {
                  map.flyTo([p.lat, p.lng], 17, { duration: 0.8 });
                  targetMarker.openPopup();
              }

              resultsBox.classList.add('hidden');
              searchInput.value = p.name;
          });

          // hide dropdown when clicking outside
          document.addEventListener('click', (e) => {
              if (!e.target.closest('#mapSearchInput') && !e.target.closest('#mapSearchResults')) {
                  resultsBox.classList.add('hidden');
              }
          });
      });
      </script>
      </div>
    </section>
    <section class=" py-[100px] px-[10px] lg:px-[0px] bg-gray-100" id="aboutus" >
      <div class="my-container">
        <div class="flex flex-col justify-center items-center">
          <h2 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-15">About Us</h2>
          <div class="flex flex-col lg:flex-row">
            <div class="w-[100%] p-[50px] lg:p-[100px] bg-[#0d9488] rounded-[20px]">
              <div class="flex items-center justify-center  lg:justify-start mb-5 flex-col lg:flex-row">
                <img src="assets/images/security.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3" loading="lazy">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Verified Listings</p>
                  <p class=" text-white">Every property is personally inspected before going live.</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/check.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3" loading="lazy">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">User Friendly</p>
                  <p class=" text-white">Easy Navigation and Filtering</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/time.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Quick Response</p>
                  <p class=" text-white">Admin Quick Response</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/laugh.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3" loading="lazy">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Happy</p>
                  <p class=" text-white">Tenants and Owners are satisfied</p>
                </span>
              </div>
            </div>
            <div class="w-[100%] flex flex-col items-center justify-center px-[0px] pt-[100px]  lg:p-[30px]">
              <p class="leading-relaxed mb-3">
                  RentSpace was born from a simple yet powerful idea — finding a rental property should not feel like a gamble. In a market often clouded by unverified listings, hidden fees, and unreliable arrangements, we recognized a critical need for change. We envisioned and built a modern platform where security meets convenience; a space where every listing is strictly verified, every price point is fully transparent, and every single renter feels absolute confidence and peace of mind before they even step foot inside their future home.
              </p>
              
              <p class="leading-relaxed mb-3">
                  Our platform bridges the gap between diverse lifestyles and the perfect spaces. From university students seeking safe, affordable, and study-friendly boarding houses near their campuses, to growing families searching for long-term, comfortable townhouses to establish their roots, we passionately connect people with environments that truly feel like home. We understand that a rental is not just a structural space; it is where your daily life unfolds, and we take that responsibility seriously.
              </p>

              <p class="leading-relaxed">
                  What truly sets RentSpace apart is our unwavering commitment to quality assurance and community trust. Our dedicated team goes beyond just hosting online advertisements; we actively review properties, verify landlord credentials, and perform rigorous quality checks to eliminate scams and misleading posts. By streamlining communication and securing data transactions, we ensure a seamless, hassle-free booking experience from the very first search click up to the exciting moment of your move-in day.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="my-container py-[100px] px-[10px] lg:px-[0px] " id="works" >
      <div class="flex justify-center items-center flex-col   w-[100%] mb-5">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">How It Works</p>
        <h2 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-15">Find Your Prefered 2 Easy Steps</h2>
      </div>
      <div class="flex gap-[20px] flex-col lg:flex-row">
        <div class="bg-emerald-100 p-[50px] lg:p-[100px] rounded-[20px] flex flex-col justify-center items-center text-center">
          <h3 class="text-gray-400 text-[5rem]">01</h3>
          <img src="assets/images/ai-technology.png" class="bg-white p-[20px] mb-[20px] rounded-[20px]" loading="lazy">
          <h4 class="text-black font-bold text-[2rem] mb-[10px]">Search &amp; Filter</h4>
          <p>
            Browse through our extensive catalog of verified rental properties. Filter by type, location, budget, and amenities to find your perfect match.
          </p>
        </div>
         <div class="bg-blue-100 p-[50px] lg:p-[100px] rounded-[20px] flex flex-col justify-center items-center text-center">
          <h3  class="text-gray-400 text-[5rem]">02</h3>
          <img src="assets/images/check-mark.png" class="bg-white p-[20px] mb-[20px] rounded-[20px]" loading="lazy">
          <h4 class="text-black font-bold text-[2rem] mb-[10px]">Check Availability</h4>
          <p>
            View real-time availability calendars, read detailed descriptions, check amenities, and see high-quality photos before making a decision.
          </p>
        </div>
      </div>
    </section>
  </main>

  <footer class=" pt-[100px]">
    <div class=" py-[100px]  bg-[#0da88a]">
      <div class="my-container flex flex-col justify-center items-center text-center">
          <h2 class="text-white font-bold text-[2rem] mb-3">Ready to Find Your Perfect Rental?</h2>
          <p class="text-white mb-7">
            Join thousands of satisfied tenants who found their ideal boarding house,<br class="hidden lg:flex"> apartment, or villa through our platform. Start    browsing verified properties today.
          </p>
          <div class="flex gap-3 flex-col lg:flex-row">
            <a href="#properties" class="btn btn-white rounded-[10px] p-[20px] text-[#0da88a]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              Browse Properties
            </a>
            <a href="#" class="btn bg-transparent text-white rounded-[10px] p-[20px] bg-[#0da88a]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
              Contact Support
            </a>
          </div>
      </div>
    </div>
    <div class=" bg-black">
      <div class="my-container py-[100px] flex justify-between flex-col lg:flex-row">
        <div class="mb-20 lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start">
          <div class="flex items-center mb-3">
            <img src="assets/images/logo-icon.png" class="w-[30px] me-2 ">
            <p class="text-white font-bold">RENTSPACE</p>
          </div>
          <p class="text-white mb-5">
            Your trusted platform for discovering  verified rental <br class="hidden lg:flex"> properties around Irosin Sorsogon
          </p>
          <ul class="flex gap-3">
            <li>
              <a href="#">
                <img src="assets/images/facebook.png" class="w-[25px]" loading="lazy">
              </a>
            </li>
            <li>
              <a href="#">
                <img src="assets/images/instagram.png" class="w-[25px]" loading="lazy">
              </a>
            </li>
            <li>
              <a href="#">
                <img src="assets/images/twitter.png" class="w-[25px]" loading="lazy">
              </a>
            </li>
          </ul>
        </div>
        <div class="mb-20 lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start"> 
          <p class="text-white font-bold mb-3">Quick Links</p>
          <ul>
            <li>
              <a href="#" class="text-white">Home</a>
            </li>
            <li>
              <a href="#properties" class="text-white">Properties</a>
            </li>
            <li>
              <a href="#aboutus" class="text-white">About Us</a>
            </li>
            <li>
              <a href="#works" class="text-white">How It Works</a>
            </li>
            <li>
              <a href="signin.php" class="text-white">Sign In</a>
            </li>
            <li>
              <a href="signup.php" class="text-white">Sign Up</a>
            </li>
          </ul>
        </div>
        <div class=" lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start">
          <p class="text-white font-bold mb-3">Contact</p>
          <ul>
            <li class="flex gap-3 mb-3">
              <img src="assets/images/phone-call.png" class="me-2" loading="lazy">
              <p class="text-white">(639) 09095416200</p>
            </li>
            <li class="flex gap-3 mb-3">
              <img src="assets/images/email.png" class="me-2" loading="lazy">
              <p class="text-white">sample@gmail.com</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/scripts/query_filter.js"></script>
  <script src="assets/scripts/jquery.js"></script>
  <script src="assets/scripts/map.js"></script>
  <script src="assets/scripts/splide_auto_scroll.js"></script>
  <script src="assets/scripts/splide.js"></script>
  <script src="assets/scripts/index.js"></script>
</body>
</html>



