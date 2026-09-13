<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
    exit; // stop execution here so nothing below runs on an invalid session
  }

  $user_id_login = $_SESSION['user_login'];

  if(isset($_GET['property_id'])){
    $landlord_id = $_GET['property_id'] ?? '';
  }else{
    header("location:index.php");
    exit;
  }

  $get_data = $conn->prepare("SELECT * FROM `landlord` WHERE `landlord_id` = ?");
  $get_data->bind_param("s", $landlord_id);
  $get_data->execute();
  $result_data = $get_data->get_result();

  $type = null;
  $property_name = '';

  if($result_data->num_rows > 0){
    while($row = mysqli_fetch_assoc($result_data)){
        $property_name = $row['property_name'];
        $type  = $row['type'];
    }
  }

  $location_add = $location_edit = $location_info = $placeholder = '';

  if($type === "Boarding House / Bedspace"){
    $location_add = "boarding_house_add.php";
    $location_edit = "my_bh_edit.php";
    $location_info = "my_bh_info.php";
    $placeholder = "Search Room Name / Number";
  }elseif($type === "Apartment"){
    $location_add = "apartment_add.php";
    $location_edit = "apartment_edit.php";
    $location_info = "apartment_info.php";
    $placeholder = "Search Apartment Name / Number";
  }elseif($type === "Condominium"){
    $location_add = "condo_add.php";
    $location_edit = "condo_edit.php";
    $location_info = "condo_info.php";
    $placeholder = "Search Condo Name / Number";
  }elseif($type === "House"){
    $location_add = "house_add.php";
    $location_edit = "house_edit.php";
    $location_info = "house_info.php";
    $placeholder = "Search House Name / Number";
  }elseif($type === "Commercial Space"){
    $location_add = "cs_add.php";
    $location_edit = "cs_edit.php";
    $location_info = "cs_info.php";
    $placeholder = "Search Commercial Space Name / Number";
  }elseif($type === "Event Space"){
    $location_add = "es_add.php";
    $location_edit = "es_edit.php";
    $location_info = "es_info.php";
    $placeholder = "Search Event Space Name / Number";
  }elseif($type === "Transient House"){
    $location_add = "trasient_add.php";
    $location_edit = "trasient_edit.php";
    $location_info = "trasient_info.php";
    $placeholder = "Search Event Trasient Name / Number";
  }elseif($type === "Parking Space"){
    $location_add = "ps_add.php";
    $location_edit = "ps_edit.php";
    $location_info = "ps_info.php";
    $placeholder = "Search  Parking Space Name / Number";
  }

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title><?php echo htmlspecialchars($property_name); ?></title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
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
        <div class="flex-1 font-bold text-white"> <?php echo htmlspecialchars($property_name); ?></div>
      </nav>
      <div class="p-0 lg:p-6">
        <!--main content-->
        <main>
            <section class="my-container py-[50px]">

                <!-- Toolbar card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6 flex justify-between flex-col md:flex-row gap-2">
        

                    <div class="flex items-center justify-between gap-3 flex-wrap w-[100%]">
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 max-w-[100%] md:max-w-[420px] w-[100%] focus-within:border-[#0fab9e] focus-within:ring-2 focus-within:ring-[#0fab9e]/20 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 shrink-0"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                            <input type="text" id="pr_search" placeholder="<?php echo htmlspecialchars($placeholder); ?>" class="border-none w-[100%] outline-none bg-transparent py-2.5 w-full text-sm" />
                        </label>
                        <div id="pr_result_count" class="text-sm text-gray-500 whitespace-nowrap w-[100%]"></div>
                    </div>
                      <a href="<?php echo htmlspecialchars($location_add); ?>?property_id=<?php echo htmlspecialchars($landlord_id); ?>" class="inline-flex items-center gap-1.5 btn btn-sm bg-[#0d9488] hover:bg-[#0b7d73] text-white px-4 py-2.5 rounded-lg text-sm font-semibold no-underline transition hover:-translate-y-0.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add
                        </a>
                </div>

                <div class="data-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-5" id="pr_grid">
                  <?php
                  $get_data = $conn->prepare("SELECT * FROM `rentspace` as r WHERE `type` = ? AND `user_id` = ? AND `landlord_id` = ? ORDER BY  `name` ASC");
                  $get_data->bind_param("sss", $type, $user_id_login, $landlord_id);
                  $get_data->execute();
                  $result_data = $get_data->get_result();

                  if($result_data->num_rows > 0){
                      while($row = $result_data->fetch_assoc()){
                      $row_type = $row['type']; // renamed to avoid clobbering $type used above
                      $rate = $row['rate'];

                         echo '
                        <div class="main-data pr-card-item group relative h-80 overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1" data-name="'.htmlspecialchars(strtolower($row['name'])).'">

                            <!-- Background Cover -->
                            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
                                style="background-image:url(\'../assets/uploads/'.htmlspecialchars($row['image_cover']).'\');">
                            </div>

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0d9488] via-black/40 to-transparent"></div>

                            <!-- Content -->
                            <div class="relative flex h-full flex-col justify-end p-5 text-white">
                               <div class="flex flex-col items-end gap-2">
                                  <div class="tooltip tooltip-left tooltip-start" data-tip="Edit">
                                    <a href="'.htmlspecialchars($location_edit).'?property_id=' .htmlspecialchars($landlord_id). '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-primary btn-sm w-fit "  >
                                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a2 2 0 0 1 .506-.852z"/></svg>
                                    </a>
                                  </div>
                                  <div class="tooltip tooltip-left tooltip-start" data-tip="View">
                                    <a href="'.htmlspecialchars($location_info).'?property_id=' .htmlspecialchars($landlord_id). '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-success btn-sm w-fit text-white">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                  </div>
                                  <div class="tooltip tooltip-left tooltip-start" data-tip="Delete">
                                    <a href="property_delete.php?property_id=' .htmlspecialchars($landlord_id). '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-error btn-sm w-fit text-white">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </a>
                                  </div>
                              </div>
                              <h2 class="text-sm font-bold truncate mb-3">
                                  '.htmlspecialchars($row['name']).'
                              </h2>
                                <div class="backdrop-blur-sm bg-white/10 rounded-xl p-2 border border-white/20">
                                    <div class="flex flex-col">
                                        <div>
                                            <p class="font-bold text-emerald-300 text-sm">
                                                &#8369; '.number_format($row['price'],2).' / '.htmlspecialchars($rate).'
                                            </p>
                                        </div>
                                     
                                    </div>

                                </div>

                            </div>

                        </div>
                      ';
                      }
                  }
                  ?>
                </div>

                <div class="hidden no-data-shown col-span-full items-center justify-center py-20 text-center" id="pr_empty_state">
                  <div class="flex flex-col items-center gap-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-icon lucide-bed">
                      <path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/>
                    </svg>
                    <p class="text-sm font-medium">No Data Found</p>
                  </div>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between gap-3 flex-wrap bg-white rounded-xl shadow-sm border border-gray-100 px-5 py-4" id="pr_pagination">
                    <div class="text-sm text-gray-500" id="pr_pagination_info"></div>
                    <div class="flex items-center gap-1.5 flex-wrap" id="pr_pagination_controls"></div>
                </div>

            </section>
        </main>

      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>




  <script src="../assets/scripts/index.js"></script>
  <script src="../assets/scripts/map.js"></script>
  <script src="../assets/scripts/query_filter.js"></script>
</body>
</html>