

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
    <title>Welcome User!</title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">



  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Messages</div>
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
                    <button class="btn btn-circle text-white btn-success btn-xs text-base-content/70 hover:text-base-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"/></svg>
                    </button>
                    </div>
                </div>

                <!-- Search Input -->
                <label class="input input-sm input-bordered flex items-center gap-2 rounded-full bg-base-200/50 focus-within:bg-base-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" class="grow text-xs" placeholder="Search Messenger..." />
                </label>
                </div>

                <!-- Active Contacts Horizontal Scroll (Optional Messenger Feature) -->
                <div class="flex items-center gap-4 px-4 py-3 border-b border-base-200 overflow-x-auto no-scrollbar">
                <!-- Active User Item -->
                <div class="flex flex-col items-center gap-1 min-w-[56px] cursor-pointer group">
                    <?php
                    $get = $conn->prepare("SELECT DISTINCT sender_id,receiver_id,user_id FROM `messages` WHERE ( `sender_id` = ?  OR `receiver_id` = ?)");
                    $get->bind_param("ss",$user_id_login,$user_id_login);
                    $get->execute();
                    $result_get = $get->get_result();
                    if($result_get->num_rows>0){
                        while($row_get = mysqli_fetch_assoc($result_get)){
                            $user_id = $row_get['user_id'] ?? '';

                        $get_user_info = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
                        $get_user_info->bind_param("s", $user_id);
                        $get_user_info->execute();
                        $get_user_data=$get_user_info->get_result();
                        if($get_user_data->num_rows>0){
                            while($row_info =mysqli_fetch_assoc($get_user_data)){
                                $middlename = $row_info['lastname'] ?? '';
                                $lastname = $row_info['lastname'] ?? '';
                                $firstname = $row_info['firstname'] ?? '';
                                $suffix = $row_info['suffix'] ?? '';
                                $selfie_photo = $row_info['selfie_photo'] ?? '';
                                $fullname = $firstname . ' ' . $lastname;
                                $final_photo = (empty($selfie_photo)) ? '../assets/images/logo-icon.png' : "../assets/uploads/$selfie_photo";
                                ?>

                                <div class="avatar online">
                                    <div class="w-12 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
                                        <img src="<?php echo $final_photo; ?>" alt="User" />
                                    </div>
                                </div>
                                <span class="text-[11px] text-base-content/70 truncate w-14 text-center"><?php echo $firstname; ?></span>

                            <?php


                            }
                        }  
                        }
                    }
                    ?>
                   
                </div>
                </div>

                <!-- Message List -->
                <div class="divide-y divide-base-200/50">

                <!-- ITEM 1: UNREAD MESSAGE -->
                <a href="chat.php?id=1" class="flex items-center gap-3 p-3.5 hover:bg-base-200/60 transition-colors cursor-pointer relative group">
                    <!-- Avatar with Online Status -->
                    <div class="avatar online shrink-0">
                    <div class="w-12 h-12 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" alt="Sender" />
                    </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <!-- Bold Name for Unread -->
                        <h4 class="font-bold text-sm text-base-content truncate pr-2">Maria Clara (Landlord)</h4>
                        <span class="text-[11px] font-bold text-primary shrink-0">2m</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <!-- Bold Preview Message for Unread -->
                        <p class="text-xs font-bold text-base-content truncate">
                        Available pa po ba yung room for bedspace next month?
                        </p>
                        <!-- Unread Blue Dot Badge -->
                        <span class="size-2.5 bg-primary rounded-full shrink-0"></span>
                    </div>
                    </div>
                </a>

                <!-- ITEM 2: READ MESSAGE -->
                <a href="chat.php?id=2" class="flex items-center gap-3 p-3.5 hover:bg-base-200/60 transition-colors cursor-pointer relative group">
                    <!-- Avatar Offline -->
                    <div class="avatar offline shrink-0">
                    <div class="w-12 h-12 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1507003211169-0a1dd7228f2d.webp" alt="Sender" />
                    </div>
                    </div>

                    <!-- Details -->
                    <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <!-- Normal Weight Name for Read -->
                        <h4 class="font-semibold text-sm text-base-content/90 truncate pr-2">Juan Dela Cruz</h4>
                        <span class="text-[11px] text-base-content/50 shrink-0">1h</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <!-- Normal Preview Message -->
                        <p class="text-xs text-base-content/60 truncate">
                        You: Sige po, pupuntahan ko po bukas para ma-viewing.
                        </p>
                        <!-- Small Seen Avatar Icon -->
                        <div class="avatar shrink-0">
                        <div class="w-4 h-4 rounded-full">
                            <img src="https://img.daisyui.com/images/stock/photo-1507003211169-0a1dd7228f2d.webp" alt="Seen" />
                        </div>
                        </div>
                    </div>
                    </div>
                </a>

                <!-- ITEM 3: READ MESSAGE WITHOUT SEEN AVATAR -->
                <a href="chat.php?id=3" class="flex items-center gap-3 p-3.5 hover:bg-base-200/60 transition-colors cursor-pointer relative group">
                    <div class="avatar online shrink-0">
                    <div class="w-12 h-12 rounded-full">
                        <img src="https://img.daisyui.com/images/stock/photo-1494790108377-be9c29b29330.webp" alt="Sender" />
                    </div>
                    </div>

                    <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <h4 class="font-semibold text-sm text-base-content/90 truncate pr-2">Sarah Geronimo</h4>
                        <span class="text-[11px] text-base-content/50 shrink-0">Yesterday</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs text-base-content/60 truncate">
                        Salamat po sa tulong!
                        </p>
                    </div>
                    </div>
                </a>

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
