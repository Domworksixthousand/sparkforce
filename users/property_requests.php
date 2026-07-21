

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
    <title>My Account</title>
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
        <div class="flex-1 font-bold text-white">Property Request</div>
      </nav>
      <div class="">
        <!--main content-->
        <main class="">
           <section class="my-container py-[50px]">
                    <div class="flex justify-start items-start mb-5  w-[100%]">
                        <label class="input validator  w-[100%] rounded-[5px]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                            <input type="text"  class="search_data1 input w-[100%] " placeholder="Property Type, Property Name, Address"  />
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
                                    <th class="text-center">Property Type</th>
                                    <th class="text-center">Property Name</th>
                                    <th class="text-center">Address</th>
                                    <th class="text-center">Date Request</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                                <tbody class="myTable1">
                                    <?php
                                            $request_status = "Pending";
                                            $get = $conn->prepare("SELECT * FROM `landlord` WHERE  `user_id` = ?  ORDER BY date_request DESC");
                                            $get->bind_param("s",$user_id_login);
                                            $get->execute();
                                            $result_get = $get->get_result();
                                            if($result_get->num_rows>0){
                                                while($row = mysqli_fetch_assoc($result_get)){
                                                $province = $row['province'];
                                                $municipality = $row['municipality'];
                                                $barangay = $row['barangay'];
                                                $address = $row['province'] . ' ' . $row['municipality'] . ' ' . $row['barangay'];
                                                $type = $row['type'];
                                                $property_name = $row['property_name'];
                                                $date_request = $row['date_request'];
                                                $status = $row['status'];
                                                $landlord_id = $row['landlord_id'];

                                                if($status === "Approved"){
                                                    $bg_color = "bg-success";
                                                }elseif($status === "Pending"){
                                                    $bg_color = "bg-warning";
                                                }else{
                                                    $bg_color = "bg-error";
                                                }
                                             
                                            echo '
                                                <tr class="data-row1">
                                                    <td class="text-center">' . $type . '</td>
                                                    <td class="text-center">' . $property_name . '</td>
                                                    <td class="text-center">' . $address . '</td>
                                                    <td class="text-center">' . date('F j, Y', strtotime($date_request)) . '</td>
                                                    <td class="' . $bg_color . ' text-center font-bold">' . $status . '</td>
                                                    <td class="text-center">
                                                        <a href="property_requests_info.php?id=' . $landlord_id . '&location_back=request_accounts.php" class="btn btn-success text-white">More Info</a>
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



    <script src="./../assets/scripts/index.js"></script>
    <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>

