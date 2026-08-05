

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
    <title>Welcome User!</title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">



  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">My Favorites</div>
      </nav>
      <div class="py-6">
        <!--main content-->
        <main>
            <section class="my-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php

            $query = "SELECT 
                        f.rent_id, 
                        f.type, 
                        rs.name, 
                        rs.price, 
                        rs.image_cover, 
                        l.property_name, 
                        l.barangay, 
                        l.municipality, 
                        l.province
                    FROM `favorites` f
                    INNER JOIN `rentspace` rs ON f.rent_id = rs.rent_id
                    INNER JOIN `landlord` l ON rs.landlord_id = l.landlord_id
                    WHERE f.user_id = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $user_id_login);
            $stmt->execute();
            $result_fav = $stmt->get_result();

            if ($result_fav->num_rows > 0) {
                while ($row = $result_fav->fetch_assoc()) {
                    $rent_id       = $row['rent_id'];
                    $type          = $row['type'];
                    $property_name = $row['property_name'];
                    $name          = $row['name'];
                    $price         = $row['price'];
                    $image_cover   = $row['image_cover'];

                  
                    if ($type === "Boarding House / Bedspace") {
                        $locate = "boarding_details.php";
                        
                    } elseif ($type === "Apartment") {
                        $locate = "apartment_info.php";
                    } else {
                        $locate = "details.php"; 
                    }

                   
                    $extention = in_array($type, ["Event Space", "Transient House", "Parking Space", "Vacant Lot"]) ? "Hour" : "Month";

         
                    $location_parts = array_filter([$row['barangay'] ?? '', $row['municipality'] ?? '', $row['province'] ?? '']);
                    $location       = !empty($location_parts) ? implode(', ', $location_parts) : 'Location not set';

                   
                    $switch       = 'on';
                    $tooltip_text = 'Remove from Favorites';
                    $icon_color   = '#dc2626';
                    $stroke_color = '#dc2626';
                    $class_color  = 'text-red-600';

                    echo '
                    <div class="rental-card group bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col hover:-translate-y-1 cursor-pointer" data-id="' . htmlspecialchars($rent_id) . '">
                        <div class="relative h-44 overflow-hidden bg-slate-100">
                            <img src="../assets/uploads/' . htmlspecialchars($image_cover) . '" alt="' . htmlspecialchars($property_name) . '" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            
                            <span class="absolute top-2.5 left-2.5 text-[10px] font-bold tracking-wider uppercase bg-white/90 backdrop-blur-md text-emerald-800 px-2 py-0.5 rounded shadow-sm">
                                ' . htmlspecialchars($type) . '
                            </span>

                            <!-- Favorite Button Form -->
                            <form action="../functions.php" method="POST" class="tooltip tooltip-left absolute bottom-2.5 right-2.5 z-10" data-tip="' . htmlspecialchars($tooltip_text) . '">
                                <input type="hidden" name="type" value="' . htmlspecialchars($type) . '">
                                <input type="hidden" name="locate" value="my_favorite.php">
                                <input type="hidden" name="rent_id" value="' . htmlspecialchars($rent_id) . '">
                                <input type="hidden" name="current_status" value="' . htmlspecialchars($switch) . '">

                                <button type="submit" name="add_favorite_btn" value="1" class="bg-white/80 hover:bg-white rounded-full p-1.5 flex items-center justify-center transition-all cursor-pointer border-none outline-none shadow-sm backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" 
                                        width="20" 
                                        height="20" 
                                        viewBox="0 0 24 24" 
                                        fill="' . $icon_color . '" 
                                        stroke="' . $stroke_color . '" 
                                        stroke-width="2" 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        class="' . $class_color . ' transition-colors">
                                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/>
                                    </svg>
                                </button>
                            </form>

                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="' . $locate . '?id=' . urlencode($rent_id) . '" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-full shadow-lg transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <div class="p-3.5 flex flex-col flex-1">
                            <h3 class="text-sm font-bold text-slate-900 truncate mb-0.5">' . htmlspecialchars($property_name) . '</h3>
                            <p class="text-xs text-slate-500 truncate mb-2">' . htmlspecialchars($name) . '</p>
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 mb-3 truncate">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="truncate">' . htmlspecialchars($location) . '</span>
                            </div>
                            <div class="mt-auto pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-slate-400 block font-normal">Rent Rate</span>
                                    <p class="text-sm font-extrabold text-emerald-600">
                                        &#8369;' . number_format((float)$price, 2) . ' 
                                        <span class="text-[10px] text-slate-400 font-normal">/ ' . htmlspecialchars($extention) . '</span>
                                    </p>
                                </div>
                                
                                <a href="' . $locate . '?id=' . urlencode($rent_id) . '" class="lg:hidden text-xs font-semibold text-emerald-600 hover:text-emerald-800 flex items-center gap-0.5">
                                    More
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '
                 <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                  <p class="text-sm font-medium">No Favorites found</p>
                  <p class="text-xs text-gray-400 mt-1">New listings will appear here once added.</p>
                </div>
                ';
            }
            $stmt->close();
            ?>
            </section>
        </main>
      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>
