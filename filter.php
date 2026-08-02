<?php 

    include 'config.php'; 
    if(isset($_GET['min']) && isset($_GET['max']) && isset($_GET['type'])){
        $min = $_GET['min'];
        $max = $_GET['max'];
        $type = $_GET['type'];
        if($min >= $max){
            $_SESSION['error'] = "Minimum Price Must not Greather than equal to Maximum Price";
            header("location:index.php");
            exit;
        }
    }else{
        header("location:index.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>

</head>
<body class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] min-h-screen">

<main>
    <Section class="my-container py-[100px]">
        <div class="flex justify-between mb-15 flex-col-reverse md:flex-row">
            <div>
               <h3 class="text-lg text-white font-bold"> FILTERED RESULTS</h3>
                <p class="text-white">Showing spaces matching your selected criteria</p>
            </div>
            <a href="index.php" class="flex justify-end items-end"><img src="assets/images/back.png" class="bg-white p-2 border-3 border-green-800 rounded-lg  hover:border-white"></a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-5">
      <?php
        $get_rental = $conn->prepare("
            SELECT r.*, l.province, l.municipality, l.barangay, l.property_name,r.price,r.type
            FROM rentspace r
            LEFT JOIN landlord l ON l.landlord_id = r.landlord_id WHERE r.price >= ? AND r.price <= ? AND r.type = ?
        ");
        $get_rental->bind_param("iis", $min, $max, $type);
        $get_rental->execute();
        $result_rental = $get_rental->get_result();

        if ($result_rental->num_rows > 0) {
            while ($row_rentals = $result_rental->fetch_assoc()) {
                $name        = htmlspecialchars($row_rentals['name'] ?? '');
                $rent_id     = htmlspecialchars($row_rentals['rent_id'] ?? '');
                $landlord_id = htmlspecialchars($row_rentals['landlord_id'] ?? '');
                $type        = htmlspecialchars($row_rentals['type'] ?? '');
                $image       = htmlspecialchars($row_rentals['image_cover'] ?? '');
                $property_name       = htmlspecialchars($row_rentals['property_name'] ?? '');
                $price       = $row_rentals['price'] ?? 0;

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


                  if($type === "Event Space" || $type === "Transient House" || $type === "Parking Space" ||  $type === "Vacant Lot" ){
                    $extention = "Hour";
                  }else{
                    $extention = "Month";
                  }

                if($type === "Boarding House / Bedspace"){
                  $locate = "boarding_details.php";
                }elseif($type === "Apartment"){
                  $locate = "apartment_details.php";
                }

                echo '
                <div class="rental-card group relative flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                data-type="' . $type . '">

                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                            style="background-image:url(\'' . $image_url . '\');">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                        <span class="absolute top-3 left-3 text-[0.65rem] font-medium tracking-wide uppercase bg-white/90 backdrop-blur-sm text-emerald-700 px-2.5 py-1 rounded-full shadow-sm">
                            ' . $type . '
                        </span>



                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="'.$locate.'?id=' . $rent_id .'"
                              class="bg-white text-gray-900 text-xs font-semibold px-4 py-2 rounded-full shadow-md hover:bg-emerald-600 hover:text-white transition-colors">
                                View Details
                            </a>
                                
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex flex-col flex-1 p-4">
                        <p class="text-sm font-bold text-black truncate mb-1">'.$property_name.'</p>
                        <h2 class="text-sm  text-gray-500 truncate mb-1">
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
                                <span class="text-xs font-normal text-gray-400">/ ' .$extention.'</span>
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
    </Section>
</main>




<!-- SCRIPTS -->
<script src="assets/scripts/jquery.js"></script>
<script src="assets/scripts/index.js"></script>


</body>
</html>