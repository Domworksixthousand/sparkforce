

<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }

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
  if($result_data->num_rows>0){
    while($row = mysqli_fetch_assoc($result_data)){
        $property_name = $row['property_name'];
        $type  = $row['type'];
    }
  }

  if($type === "Boarding House / Bedspace"){
    $location_add = "boarding_house_add.php";
    $location_edit = "my_bh_edit.php";
    $location_info = "my_bh_info.php";
  }elseif($type === "Apartment"){
    $location_add = "apartment_add.php";

  }


 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property_name; ?></title>
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
        <div class="flex-1 font-bold text-white"> <?php echo $property_name; ?></div>
      </nav>
      <div class="p-0 lg:p-6">
        <!--main content-->
        <main>
            <section class="my-container py-[50px]">
                <div class="mb-3 text-end">
                    <a href="<?php echo $location_add; ?>?property_id=<?php echo $landlord_id; ?>" class="btn btn-success text-white ">Add</a>
                </div>
                <div class="flex justify-start items-start mb-5  w-[100%]">
                    <label class="input validator  w-[100%] rounded-[5px]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                        <input type="text"  class="autoInput search_data_property input w-[100%] " placeholder="Search Room Name/ Number"  />
                    </label>
                </div>
                <div class="flex items-center gap-2 mb-10">
                    <select id="entries_limit1" class="select w-fit rounded-[5px]">
                        <option value="8" selected>8</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="All">All</option>
                    </select>
                    <p>Entries per Page</p>
                </div>
                <div class="data-container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-5">
                  <?php
                  $get_data = $conn->prepare("SELECT * FROM `rentspace` as r WHERE `type` = ? AND `user_id` = ? AND `landlord_id` = ? ORDER BY name");
                  $get_data->bind_param("sss", $type, $user_id_login, $landlord_id);
                  $get_data->execute();
                  $result_data = $get_data->get_result();

                  if($result_data->num_rows > 0){
                      while($row = $result_data->fetch_assoc()){

                         echo '
                        <div class="main-data group relative h-80 overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

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
                                    <a href="'.$location_edit.'?property_id=' .$landlord_id. '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-primary btn-sm w-fit "  >
                                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                                    </a>
                                  </div>
                                  <div class="tooltip tooltip-left tooltip-start" data-tip="View">
                                    <a href="'.$location_info.'?property_id=' .$landlord_id. '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-success btn-sm w-fit text-white">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                  </div>
                                  <div class="tooltip tooltip-left tooltip-start" data-tip="Delete">
                                    <a href="property_delete.php?property_id=' .$landlord_id. '&id='.htmlspecialchars($row['rent_id']).'" class="btn btn-error btn-sm w-fit text-white">
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
                                                &#8369; '.number_format($row['price'],2).' / Month
                                            </p>
                                        </div>
                                     
                                    </div>

                                </div>

                            </div>

                        </div>
                      ';
                      }
                  } else {
                      echo '<div class="col-span-full flex items-center justify-center py-20 text-center">
                              <div class="flex flex-col items-center gap-3 text-gray-400">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-icon lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                                  <p class="text-sm font-medium">No Data Found</p>
                              </div>
                            </div>';
                    }
                  ?>
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




  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/map.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>


