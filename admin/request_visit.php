
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
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>Request Visits</title>
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
            <div class="flex-1 font-bold text-white"> Requests Visit</div>
        </nav>
        <div class="py-6">
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
                                    <th>Fullname</th>
                                    <th>Property Address</th>
                                    <th>Landlord</th>
                                    <th>Request Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                                <tbody class="myTable">
                                    <?php
                                        $get_request = $conn->prepare("SELECT rv.req_id,rv.status,rv.landlord_id,rv.user_id,rv.date_request,rv.time_request,rv.date_sched,rv.date_sched,rv.time_sched,a.lastname,a.firstname,a.middlename,a.suffix,a.user_id FROM request_visit as rv 
                                        LEFT JOIN accounts as a  ON rv.user_id = a.user_id
                                        ORDER BY rv.date_sched DESC");
                                        $get_request->execute();
                                        $result_get = $get_request->get_result();
                                        if($result_get->num_rows>0){
                                            while($row_get = mysqli_fetch_assoc($result_get)){
                                                $req_id = $row_get['req_id'];
                                                $status = $row_get['status'];
                                                $landlord_id = $row_get['landlord_id'];
                                                $user_id = $row_get['user_id'];
                                                $date_request = $row_get['date_request'];
                                                $time_request = $row_get['time_request'];
                                                $date_sched = $row_get['date_sched'];
                                                $time_sched = $row_get['time_sched'];
                                                $lastname = $row_get['lastname']  ?? '';
                                                $middlename = $row_get['middlename']  ?? '';
                                                $firstname = $row_get['firstname']  ?? '';
                                                $suffix = $row_get['suffix'] ?? '';
                                                $fullname = $lastname . ' ' . $firstname . ' ' . $middlename . ' ' . $suffix;
                                                ?>
                                                <tr>
                                                    <td></td>
                                                </tr>

                                                <?php
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
