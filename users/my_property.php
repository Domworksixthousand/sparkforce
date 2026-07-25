

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
                        <input type="text"  class="search_data1 input w-[100%] " placeholder="Search Amenities"  />
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                  <?php
                  $get_data = $conn->prepare("SELECT * FROM `rentspace` WHERE `type` = ? AND `user_id` = ? AND `landlord_id` = ?");
                  $get_data->bind_param("sss", $type, $user_id_login, $landlord_id);
                  $get_data->execute();
                  $result_data = $get_data->get_result();

                  if($result_data->num_rows > 0){
                      while($row = $result_data->fetch_assoc()){

                         echo '
<div class="group relative h-80 overflow-hidden rounded-2xl shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">

    <!-- Background Cover -->
    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-110"
        style="background-image:url(\'../assets/uploads/'.htmlspecialchars($row['image_cover']).'\');">
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

    <!-- Content -->
    <div class="relative flex h-full flex-col justify-end p-5 text-white">

        <div class="backdrop-blur-sm bg-white/10 rounded-xl p-3 border border-white/20">

            <h2 class="text-sm font-bold truncate">
                '.htmlspecialchars($row['name']).'
            </h2>

            <div class="mt-2 flex items-center justify-between">

                <div>

                    <p class="font-bold text-green-400">
                        &#8369; '.number_format($row['price'],2).'
                    </p>
                </div>

                <a href="view_property.php?rent_id='.$row['rent_id'].'"
                    class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">

                    View

                    <svg xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>

                </a>

            </div>

        </div>

    </div>

</div>
';
                      }
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




  <!--leaflet script-->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>
  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/map.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>


