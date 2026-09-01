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
    <title>Amenities Management</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head> 
<body class="bg-base-100 min-h-screen">

  <?php include '../alerts.php'; ?>

  <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    
    <div class="drawer-content flex flex-col bg-base-100">
      <!-- Navbar -->
      <nav class="navbar w-full bg-[#0fab9e] text-white shadow-md px-6 min-h-[4rem]">
        <div class="flex items-center gap-3">
          <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden hover:bg-[#0d9488]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-6"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </label>
          <div class="text-xl font-bold tracking-wide">Property Management / Amenities</div>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main class="p-6 lg:p-10 max-w-7xl w-full mx-auto">
        
        <!-- Header Banner & Action Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-base-200/60 p-6 rounded-2xl border border-base-300">
          <div>
            <h1 class="text-2xl font-extrabold text-base-content">Amenities List</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage and track property amenities effortlessly.</p>
          </div>
          <a href="amenities_add.php" class="btn bg-[#0fab9e] hover:bg-[#0d9488] text-white border-none shadow-sm gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add New Amenity
          </a>
        </div>

        <!-- Filter and Search Controls -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
          <label class="input input-bordered flex items-center gap-3 w-full md:w-96 rounded-xl bg-base-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/></svg>
            <input type="search" class="search_data1 w-full focus:outline-none bg-transparent text-sm" placeholder="Search amenities..." />
          </label>

          <div class="flex items-center gap-3 w-full md:w-auto justify-end">
            <span class="text-sm text-base-content/70">Show</span>
            <select id="entries_limit1" class="select select-bordered select-sm rounded-lg w-24 bg-base-100">
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
            <span class="text-sm text-base-content/70">entries</span>
          </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-base-200/40 border border-base-300 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-left">
              <thead>
                <tr class="bg-[#0fab9e] text-white uppercase text-xs tracking-wider">
                  <th class="py-4 px-6 font-semibold">Amenity Name</th>
                  <th class="py-4 px-6 font-semibold">Description</th>
                  <th class="py-4 px-6 font-semibold text-center">Actions</th>
                </tr>
              </thead>
              <tbody class="myTable1 divide-y divide-base-300">
                <?php
                    $yes_status = "yes";
                    $get_amen = $conn->prepare("SELECT * FROM `amenities` WHERE `user_id` = ? AND `active` = ?");
                    $get_amen->bind_param("ss", $user_id_login, $yes_status);
                    $get_amen->execute();
                    $result_amen = $get_amen->get_result();
                    
                    if($result_amen->num_rows > 0){
                        while($row = mysqli_fetch_assoc($result_amen)){
                            $amen_id = $row['amen_id'];
                            $amenity = htmlspecialchars($row['amenity']);
                            $description = htmlspecialchars($row['description']);
                            $short_desc = (mb_strlen($description) > 60) 
                            ? mb_substr($description, 0, 60) . '...' 
                            : $description;

                            echo '
                                <tr class="data-row1 hover:bg-base-200/60 transition-colors">
                                    <td class="py-4 px-6 font-medium text-base-content">' . $amenity . '</td>
                                    <td class="py-4 px-6 text-base-content/80 text-sm max-w-xs truncate" title="' . $description . '">' . ($short_desc ? $short_desc : '<span class="italic text-base-content/40">No description</span>') . '</td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="amenities_edit.php?id=' . $amen_id . '" class="btn btn-xs sm:btn-sm btn-info text-white gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <a href="amenities_delete.php?id=' . $amen_id . '" class="btn btn-xs sm:btn-sm btn-error text-white gap-1" onclick="return confirm(\'Are you sure you want to delete this amenity?\');">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>';
                        }
                    } else {
                        ?>
                        <div class=" flex-col items-center justify-center py-16 px-5 text-center text-base-content/40" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <p class="text-sm m-0">No Amenities found.</p>
                        </div>
                        <?php
                    }
                ?>
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>

    <!-- Sidebar Drawer -->
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>