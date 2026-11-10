<?php
  include '../config.php'; 
   if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
      echo "<script>location.href='../index.php';</script>";
      exit; 
  }
    
    $data_stat = 'viewed';
    $data_seen = 'seen';
    $update = $conn->prepare("UPDATE messages SET `status` = ? WHERE  `receiver_id` = ? AND `status` != ? ");
    $update->bind_param("sss", $data_stat, $user_id_login,$data_seen);
    $update->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>Messages</title>
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
    include '../banned_modal.php'; 
?>

  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex justify-between items-center w-[100%] ">
         <div class="font-bold text-white">Messages</div>
          <div class="dropdown dropdown-left">
            <div tabindex="0" role="button" class="cursor-pointer m-1">
              <div class="avatar avatar-online">
                <div class="w-10  rounded-full">
                  <img alt="Tailwind-CSS-Avatar-component" src="<?php echo $final_profileko; ?>" />
                </div>
              </div>
            </div>
            <ul tabindex="-1" class="dropdown-content menu bg-white rounded-box z-1 w-52 p-2 shadow-sm">
              <li >
                <div class="flex">
                   <img alt="Tailwind-CSS-Avatar-component  rounded-full" class="w-10 " src="<?php echo $final_profileko; ?>" />
                  <div class="flex flex-col">
                    <p class="text-[12px]"><?php echo $fullnameko; ?></p>
                    <p class="text-[10px] text-gray-500">Verified <?php echo $usertypeko; ?></p>
                  </div>
                </div>
              </li>
              <hr class="text-gray-300">
              <li>
                <a href="signout.php?location_back=index.php&property_id=<?php echo $_GET['property_id'] ?? '' ?>" class="flex items-center gap-3 active text-sm ">                                                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="15"  height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                          <span class="text-sm">Sign Out</span>
                </a>
              </li>
            </ul>
        </div>
       </div>
      </nav>
      <div class="py-6">
        <!--main content-->
        <main>
            <section class="my-container">
            <!-- Outer Card Container -->
            <div class="bg-base-100 border border-base-200 rounded-2xl shadow-sm overflow-hidden">
                
                <!-- Header & Search Bar -->
                <div class="p-4 border-b border-base-200 space-y-3 bg-base-100/50 backdrop-blur">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold tracking-tight text-base-content">Chats</h1>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                    <button type="button" onclick="location.href='start_chat.php'"  class="btn btn-circle text-white btn-success btn-xs text-base-content/70 hover:text-base-content tooltip tooltip-left" data-tip="Find User">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 ms-[2px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/></svg>
                    </button>
                    </div>
                </div>

                <!-- Search Input with ID added -->
                <label class="input input-sm input-bordered flex items-center gap-2 rounded-full bg-base-200/50 focus-within:bg-base-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="search_input" class="grow text-xs" placeholder="Search Messenger..." />
                </label>
                </div>

                <!-- Message List -->
              <div id="users_list" class="divide-y divide-base-200/50 p-6 sm:p-10">
                 
                </div>
                <div id="noSearchResults" class="hidden flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M17.925 20.056a6 6 0 0 0-11.851.001"/><circle cx="12" cy="11" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-500">No User Found!</h3>
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

  <script src="../assets/scripts/index.js"></script>
  <script src="../assets/scripts/messages.js"></script>
  <script src="../assets/scripts/query_filter.js"></script>
  <script src="../assets/scripts/filter_messages.js"></script>
</body>
</html>
