

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
           <section class="my-container   bg-slate-50/50 rounded-2xl">
                <!-- Today Section -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-slate-800 tracking-tight">Today</h4>
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Recent Update</span>
                    </div>

                    <?php
                    // Helper function to avoid repeating HTML structure
                    if (!function_exists('renderNotificationCard')) {
                        function renderNotificationCard($row) {
                            $noti_id    = htmlspecialchars($row['noti_id']);
                            $text_noti  = htmlspecialchars($row['text_noti']);
                            $status     = $row['status'];
                            $time_sent  = date("h:i A", strtotime($row['time_sent']));
                            $sender     = htmlspecialchars($row['sender']);
                            $link       = !empty($row['link']) ? $row['link'] : '';

                            // Handle Profile Picture
                            $profile    = $row['profile'];
                            $pic        = empty($profile) ? "../assets/images/logo-icon.png" : "../assets/uploads/$profile";

                            // Unread vs Seen status styling
                            $is_unread  = ($status !== "seen");
                            $bg_status  = $is_unread ? "bg-emerald-50 border-emerald-100" : "bg-white border-slate-200/80 hover:border-slate-300";
                            ?>
                            <div class="group relative flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 mb-3 border rounded-xl shadow-xs hover:shadow-md transition-all duration-200 ease-in-out <?php echo $bg_status; ?>">
                                
                                <div class="flex items-start sm:items-center gap-4 w-full sm:w-auto mb-3 sm:mb-0">
                                    <!-- Avatar -->
                                    <div class="relative shrink-0">
                                        <img src="<?php echo $pic; ?>" alt="<?php echo $sender; ?>" class="w-12 h-12 rounded-full object-cover ring-2 ring-white shadow-xs">
                                        <?php if ($is_unread): ?>
                                            <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h5 class="text-sm font-semibold text-slate-900 group-hover:text-emerald-600 transition-colors">
                                                <?php echo $sender; ?>
                                            </h5>
                                            <span class="text-[11px] font-medium px-2 py-0.5 rounded-full <?php echo $is_unread ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'; ?>">
                                                <?php echo $is_unread ? 'Unread' : 'Seen'; ?>
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-1.5">
                                            <?php echo $text_noti; ?>
                                        </p>
                                        <div class="flex items-center text-xs text-slate-400 font-medium">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <?php echo $time_sent; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-2 self-end sm:self-center shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 w-full sm:w-auto justify-end">
                                    <?php if (!empty($link)): ?>
                                        <a href="../functions.php?noti_id=<?php echo $noti_id; ?>&link=<?php echo urlencode($link); ?>" 
                                        class="inline-flex items-center justify-center p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors duration-150" 
                                        title="View details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <a href="notifications_delete.php?id=<?php echo $noti_id; ?>" 
                                    class="inline-flex items-center justify-center p-2 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-colors duration-150" 
                                    title="Delete notification">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v2M4 7h16"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }

                    // --- Fetch Today's Notifications ---
                    $get_notifications = $conn->prepare("
                        SELECT *, noti.status FROM `notifications` AS noti 
                        LEFT JOIN `accounts` AS ac ON noti.receiver = ac.user_id
                        WHERE noti.receiver = ? AND noti.date_sent = ? ORDER BY date_sent DESC
                    ");
                    $get_notifications->bind_param("ss", $user_id_login, $datetoday);
                    $get_notifications->execute();
                    $result_noti = $get_notifications->get_result();

                    if ($result_noti->num_rows > 0) {
                        while ($row_noti = $result_noti->fetch_assoc()) {
                            renderNotificationCard($row_noti);
                        }
                    } else {
                        echo '<div class="p-6 text-center text-sm text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">No notifications for today.</div>';
                    }
                    ?>
                </div>

                <!-- Previous Section -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-bold text-slate-800 tracking-tight">Previous</h4>
                        <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Older</span>
                    </div>

                    <?php
                    // --- Fetch Previous Notifications ---
                    $get_notifications_prev = $conn->prepare("
                        SELECT *, noti.status FROM `notifications` AS noti 
                        LEFT JOIN `accounts` AS ac ON noti.receiver = ac.user_id
                        WHERE noti.receiver = ? AND noti.date_sent != ? ORDER BY date_sent DESC
                    ");
                    $get_notifications_prev->bind_param("ss", $user_id_login, $datetoday);
                    $get_notifications_prev->execute();
                    $result_noti_prev = $get_notifications_prev->get_result();

                    if ($result_noti_prev->num_rows > 0) {
                        while ($row_noti = $result_noti_prev->fetch_assoc()) {
                            renderNotificationCard($row_noti);
                        }
                    } else {
                        echo '<div class="p-6 text-center text-sm text-slate-400 bg-white rounded-xl border border-dashed border-slate-200">No previous notifications.</div>';
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

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>
