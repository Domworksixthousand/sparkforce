

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
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr  class="bg-[#0d9488] text-white">
                                <th class="text-center">Amenity</th>
                                <th class="text-center">Desc</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                            <tbody class="myTable1">
                                <?php
                                        $yes_status = "yes";
                                        $get_amen = $conn->prepare("SELECT * FROM `amenities` WHERE `user_id` = ? AND `active` = ?");
                                        $get_amen->bind_param("ss",$user_id_login,$yes_status);
                                        $get_amen->execute();
                                        $result_amen = $get_amen->get_result();
                                        if($result_amen->num_rows>0){
                                            while($row = mysqli_fetch_assoc($result_amen)){
                                                $amen_id = $row['amen_id'];
                                                $amenity = $row['amenity'];
                                                $description = $row['description'];
                                                $short_desc = (strlen($description) > 10) 
                                                ? mb_substr($description, 0, 10) . '...' 
                                                : $description;
                                            
                                        echo '
                                            <tr class="data-row1">
                                                <td class="text-center">' . $amenity . '</td>
                                                <td class="text-center">' . $short_desc . '</td>
                                                <td class="text-center">
                                                    <a href="amenities_delete.php?id=' . $amen_id . '" class="btn btn-error text-white">Delete</a>
                                                    <a href="amenities_edit.php?id=' . $amen_id . '" class="btn btn-primary text-white">Edit</a>
                                                </td>
                                            </tr>';
                                        }
                                    }
                                ?>
                        </tbody>
                    </table>
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


