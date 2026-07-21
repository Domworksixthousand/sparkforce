
<?php
  include '../config.php'; 
  if(!isset($_SESSION['admin_login'])){
    echo "<script>location.href='../index.php';</script>";
  }
 ?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Request</title>
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

  <!---alert-->
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
            <div class="flex-1 font-bold text-white">Pending Property Requests</div>
        </nav>
        <div class="p-6">
            <!--main content-->
            <main>
                <section class="my-container">
                    <div class="flex justify-start items-start mb-5  w-[100%]">
                    <label class="input validator  w-[100%] rounded-[5px]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                            <input type="text"  class="search_data input w-[100%] " placeholder="Search Property Name,Address,landlord"  />
                        </label>
                    </div>
                    <div class="flex items-center gap-2 mb-10">
                        <select id="entries_limit" class="select w-fit rounded-[5px]">
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
                                    <th>Property Name</th>
                                    <th>Property Address</th>
                                    <th>Landlord</th>
                                    <th>Request Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                                <tbody class="myTable">
                                    <?php
                                        $request_pending = "Pending";
                                        $request = $conn->prepare("SELECT l.landlord_id,l.user_id,l.province,l.municipality,l.barangay,l.type,l.property_name,l.date_request,a.firstname,a.lastname,a.middlename,a.suffix,a.user_id,l.status
                                        FROM `landlord` as l 
                                        LEFT JOIN accounts as a ON l.user_id = a.user_id
                                        WHERE  l.status = ? ORDER BY l.date_request ASC");
                                        $request->bind_param("s",$request_pending);
                                        $request->execute();
                                        $result_request = $request->get_result();
                                        if($result_request->num_rows > 0){
                                            while($row = mysqli_fetch_assoc($result_request)){
                                                $firstname = $row['firstname']; 
                                                $lastname = $row['lastname']; 
                                                $middlename = $row['middlename']; 
                                                $suffix = $row['suffix'];
                                                $fullname = $lastname . ' ' . $firstname . ' ' . $middlename . ' ' . $suffix;
                                                $user_id = $row['user_id'];   
                                                $landlord_id = $row['landlord_id'];    
                                                $province = $row['province'];  
                                                $municipality = $row['municipality']; 
                                                $barangay = $row['barangay'];
                                                $location = $barangay . ' ' . $municipality . ' ' . $province;
                                                $type = $row['type'];  
                                                $property_name = $row['property_name'];  
                                                $date_request = $row['date_request']; 
                                                $status = $row['status'];
                                                $new_date_request =  date("F j, Y", strtotime($row['date_request']));

                                             echo '
                                                <tr class="data-row">
                                                    <td>  ' . $property_name . '</td>
                                                    <td>  ' . $location . '</td>
                                                    <td>  ' . $fullname . '</td>
                                                    <td>  ' . $new_date_request . '</td>
                                                    <td>
                                                        <a href="pending_request_info.php?id=' . $user_id . '&location_back=pending_properties.php" class="btn btn-success text-white">More Info</a>
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
