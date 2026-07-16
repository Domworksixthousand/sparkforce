

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
    <title>Notifications</title>
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
        <div class="flex-1 font-bold text-white">Notifications</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section class="my-container">
                <h4 class="text-md font-bold mb-4">Today</h4>
             <?php

                $get_notifications = $conn->prepare("
                    SELECT *, noti.status FROM `notifications` AS noti 
                    LEFT JOIN `accounts` AS ac ON noti.receiver = ac.user_id
                    WHERE noti.receiver = ? AND noti.date_sent = ?
                ");

                $get_notifications->bind_param("ss", $user_id_login, $datetoday);
                $get_notifications->execute();
                $result_noti = $get_notifications->get_result();

                if ($result_noti->num_rows > 0) {

                    while ($row_noti = $result_noti->fetch_assoc()) {
                        // Notifications details
                        $noti_id    = $row_noti['noti_id'];
                        $text_noti  = $row_noti['text_noti'];
                        $status     = $row_noti['status'];
                        $time_sent  = $row_noti['time_sent'];
                        $sender     = $row_noti['sender'];
                        
                    
                        $link       = isset($row_noti['link']) ? $row_noti['link'] : '#'; 

                        // Account / Receiver details
                        $firstname  = $row_noti['firstname'];
                        $middlename = $row_noti['middlename'];
                        $lastname   = $row_noti['lastname'];
                        $suffix     = $row_noti['suffix'];
                        $profile    = $row_noti['profile'];
                        $pic = ($profile === NULL) ? "../assets/images/logo-icon.png" : "../assets/uplaods/$profile";

                        $status_color = ($status === "seen") ? "status-success" : "status-error";
                ?>
                    
                     
                         <div class="group flex flex-col lg:flex-row items-center shadow-sm p-[20px] mb-4 border border-gray-100 rounded-xl hover:shadow-xl hover:border hover:border-gray-300  cursor-pointer transition-all ">
                            <div class="me-3 mb-4 lf:mb-0 ">
                                <img src="<?php echo $pic; ?>" class=" w-[3rem] h-[3rem]  rounded-full  ">
                            </div>
                           <div class="flex flex-col w-[100%]">
                                <div class="flex items-center justify-between  gap-2 mb-2 lg:mb-0">
                                    <p class="font-sm font-bold group-hover:text-[#0fab9e]"><?php echo htmlspecialchars($sender); ?></p>
                                    <div aria-label="error" class="status p-2 <?php echo $status_color; ?> "></div>
                                </div>
                                <p class="text-sm text-gray-500 mb-2  lg:mb-0"><?php echo htmlspecialchars($text_noti); ?></p>
                                <small  class="text-sm text-gray-500 flex  items-center mn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock7-icon lucide-clock-7"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l-2 4"/></svg>
                                    <?php echo date("h:i A", strtotime($time_sent)); ?>
                                </small>
                                <div class="flex gap-2 justify-end">
                                    <a href="../functions.php?noti_id=<?php echo $noti_id; ?>&link=<?php echo $link; ?>" class="bg-success w-fit p-2 rounded-lg text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                                    </a>
                                    <a href="notifications_delete.php?id=<?php echo $noti_id;  ?>" class="bg-error w-fit p-2 rounded-lg text-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </a>
                                </div>
                           </div>
                        </div>
                   
                <?php
                    }
                } else {
                    echo "<p>No notifications for today.</p>";
                }
                ?>
                 <h4 class="text-md font-bold mb-4 mt-20">Previous</h4>
                <?php

                    $get_notifications = $conn->prepare("
                        SELECT *, noti.status FROM `notifications` AS noti 
                        LEFT JOIN `accounts` AS ac ON noti.receiver = ac.user_id
                        WHERE noti.receiver = ? AND noti.date_sent != ?
                    ");

                    $get_notifications->bind_param("ss", $user_id_login, $datetoday);
                    $get_notifications->execute();
                    $result_noti = $get_notifications->get_result();

                    if ($result_noti->num_rows > 0) {

                        while ($row_noti = $result_noti->fetch_assoc()) {
                            // Notifications details
                            $noti_id    = $row_noti['noti_id'];
                            $text_noti  = $row_noti['text_noti'];
                            $status     = $row_noti['status'];
                            $time_sent  = $row_noti['time_sent'];
                            $sender     = $row_noti['sender'];
                            
                        
                            $link       = isset($row_noti['link']) ? $row_noti['link'] : '#'; 

                            // Account / Receiver details
                            $firstname  = $row_noti['firstname'];
                            $middlename = $row_noti['middlename'];
                            $lastname   = $row_noti['lastname'];
                            $suffix     = $row_noti['suffix'];
                            $profile    = $row_noti['profile'];
                           $pic = ($profile === NULL) ? "../assets/images/logo-icon.png" : "../assets/uploads/$profile";
  
                            $status_color = ($status === "seen") ? "status-success" : "status-error";
                    ?>
                        
                      
                            <div class="group flex flex-col lg:flex-row items-center shadow-sm p-[20px] mb-4 border border-gray-100 rounded-xl hover:shadow-xl hover:border hover:border-gray-300  cursor-pointer transition-all ">
                                <div class="me-3 mb-4 lf:mb-0 ">
                                    <img src="<?php echo $pic; ?>" class=" w-[3rem] h-[3rem]  rounded-full  ">
                                </div>
                                <div class="flex flex-col w-[100%]">
                                    <div class="flex items-center justify-between  gap-2 mb-2 lg:mb-0">
                                        <p class="font-sm font-bold group-hover:text-[#0fab9e]"><?php echo htmlspecialchars($sender); ?></p>
                                        <div aria-label="error" class="status p-2 <?php echo $status_color; ?> "></div>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-2  lg:mb-0"><?php echo htmlspecialchars($text_noti); ?></p>
                                    <small  class="text-sm text-gray-500 flex  items-center mn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="me-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock7-icon lucide-clock-7"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l-2 4"/></svg>
                                        <?php echo date("h:i A", strtotime($time_sent)); ?>
                                    </small>
                                   <div class="flex gap-2 justify-end">
                                        <a href="../functions.php?noti_id=<?php echo $noti_id; ?>&link=<?php echo $link; ?>" class="bg-success w-fit p-2 rounded-lg text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                                        </a>
                                        <a href="notifications_delete.php?id=<?php echo $noti_id;  ?>" class="bg-error w-fit p-2 rounded-lg text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </a>
                                   </div>
                                </div>
                            </div>
                       
                    <?php
                        }
                    } else {
                        echo "<p>No Previous notifications.</p>";
                    }
                    ?>
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
