
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
    <title>Reports</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
      <script src="./../assets/scripts/jquery.js"></script>
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
        <div class="flex-1 font-bold text-white">Property Request</div>
      </nav>
      <div class="">
        <!--main content-->
        <main class="">
           <section class="my-container py-[50px]">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    <!-- Toolbar -->
                    <div class="flex items-center justify-between gap-3 flex-wrap px-5 py-4 border-b border-gray-100">
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 max-w-[380px] w-full focus-within:border-[#0fab9e] focus-within:ring-2 focus-within:ring-[#0fab9e]/20 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 shrink-0"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                            <input type="text" id="pr_search2" placeholder="Search Report Type or User Reported" class="border-none outline-none bg-transparent py-2.5 w-full text-sm" />
                        </label>
                        <div id="pr_result_count" class="text-sm text-gray-500 whitespace-nowrap"></div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table w-full border-collapse" id="pr_table">
                            <thead>
                                <tr class="bg-gradient-to-b from-[#0fab9e] to-[#0d9488] text-white">
                                    <th class="text-center font-semibold text-xs uppercase tracking-wide px-4 py-3.5">Report Type</th>
                                    <th class="text-center font-semibold text-xs uppercase tracking-wide px-4 py-3.5">User Reported</th>
                                    <th class="text-center font-semibold text-xs uppercase tracking-wide px-4 py-3.5">Date Reported</th>
                                    <th class="text-center font-semibold text-xs uppercase tracking-wide px-4 py-3.5">Status</th>
                                    <th class="text-center font-semibold text-xs uppercase tracking-wide px-4 py-3.5">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pr_table_body">
                                <?php
                                        $get = $conn->prepare("SELECT * FROM `report` ORDER BY date_reported DESC");
                                        $get->execute();
                                        $result_get = $get->get_result();
                                        if($result_get->num_rows>0){
                                            while($row = mysqli_fetch_assoc($result_get)){
                                            $report_type = $row['report_type'];
                                            $user_id_reported = $row['user_id_reported'];
                                            $status = $row['status'];                                      
                                            $date_reported = $row['date_reported'];
                                            $report_id = $row['report_id'];


                                      $get_user = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
                                      $get_user->bind_param("s", $user_id_reported);
                                      $get_user->execute();
                                      $result_user = $get_user->get_result();
                                      if($result_user->num_rows>0){
                                        while($row_result = mysqli_Fetch_assoc($result_user)){
                                        $lastname = $row_result['lastname'];
                                        $firstname = $row_result['firstname'];
                                        $middlename = $row_result['middlename'];
                                        $suffix = $row_result['suffix'];
                                        $fullname = $lastname . " " . $firstname . " " . $middlename . " " . $suffix;
                                      

                                            if($status === "Resolved"){
                                                $badge_class = "bg-emerald-500";
                                            }elseif($status === "Pending"){
                                                $badge_class = "bg-amber-500";
                                            }else{
                                                $badge_class = "bg-red-500";
                                            }

                                        echo '
                                            <tr class="pr-row2 border-b border-gray-100 last:border-b-0 hover:bg-teal-50/60 transition-colors">
                                                <td class="text-center px-4 py-3.5 text-sm text-gray-700">' . htmlspecialchars($report_type) . '</td>
                                                <td class="text-center px-4 py-3.5 text-sm text-gray-700">' . htmlspecialchars($fullname) . '</td>
                                                <td class="text-center px-4 py-3.5 text-sm text-gray-700">' . htmlspecialchars($date_reported) . '</td>
                                                <td class="text-center px-4 py-3.5">
                                                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold text-white ' . $badge_class . '">' . htmlspecialchars($status) . '</span>
                                                </td>
                                                <td class="text-center px-4 py-3.5">
                                                    <a href="report_info.php?id=' . $report_id . '&location_back=request_accounts.php" class="inline-flex items-center gap-1.5 bg-[#0d9488] hover:bg-[#0b7d73] text-white px-4 py-2 rounded-md text-sm font-semibold no-underline transition hover:-translate-y-0.5">
                                                        More Info
                                                    </a>
                                                </td>
                                            </tr>';
                                              }
                                      }
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>

                        <div class="hidden flex-col items-center justify-center py-16 px-5 text-center text-gray-400" id="pr_empty_state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <p class="text-sm m-0">No property requests found.</p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-between gap-3 flex-wrap px-5 py-4 border-t border-gray-100" id="pr_pagination2">
                        <div class="text-sm text-gray-500" id="pr_pagination_info"></div>
                        <div class="flex items-center gap-1.5 flex-wrap" id="pr_pagination_controls"></div>
                    </div>

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
