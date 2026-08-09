

<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }


   
    $se = "seen";
    $messages_count = $conn->prepare("SELECT COUNT(*) as total_messages FROM `messages` WHERE `receiver_id` = ? AND `status` != ?");
    $messages_count->bind_param("ss", $user_id_login, $se);
    $messages_count->execute();
    $total_messages = $messages_count->get_result()->fetch_assoc()['total_messages'] ?? 0;

    // --- Saved favorites ---
    $fav = $conn->prepare("SELECT COUNT(*) as total_favorites FROM `favorites` WHERE `user_id` = ?");
    $fav->bind_param("s", $user_id_login);
    $fav->execute();
    $total_favorites = $fav->get_result()->fetch_assoc()['total_favorites'] ?? 0;

    // --- Listed properties ---
    $landlord = $conn->prepare("SELECT COUNT(*) as total_properties FROM `landlord` WHERE `user_id` = ?");
    $landlord->bind_param("s", $user_id_login);
    $landlord->execute();
    $total_properties = $landlord->get_result()->fetch_assoc()['total_properties'] ?? 0;

    $stats = [
        [
            'label' => 'Unread Messages',
            'value' => $total_messages,
            'href'  => 'messages.php',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>',
            'badge' => $total_messages > 0,
        ],
        [
            'label' => 'Saved Favorites',
            'value' => $total_favorites,
            'href'  => 'favorites.php',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
            'badge' => false,
        ],
        [
            'label' => 'Listed Properties',
            'value' => $total_properties,
            'href'  => 'my_properties.php',
            'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>',
            'badge' => false,
        ],
    ];
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
        <div class="flex-1 font-bold text-white">Dashboard</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
        <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0fab9e]/10 via-base-100 to-base-100 border border-base-200 p-6 md:p-8 mb-6">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-6 -bottom-6 size-40 text-[#0fab9e]/10 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
          </svg>

          <div class="relative z-10 flex flex-col gap-2">
            <?php
              $hour = (int) date('H');
              if ($hour < 12) {
                  $greeting = "Good morning";
              } elseif ($hour < 18) {
                  $greeting = "Good afternoon";
              } else {
                  $greeting = "Good evening";
              }
            ?>
            <span class="text-xs font-semibold uppercase tracking-wider text-[#0fab9e]"><?php echo $greeting; ?></span>

            <h1 class="text-2xl md:text-[2rem] font-bold leading-tight">
              Welcome back,
              <span class="text-[#0fab9e] font-extrabold uppercase"><?php echo htmlspecialchars($firstnameko); ?></span>
            </h1>

            <p class="text-sm md:text-base text-base-content/60 max-w-md">
              Find your RentSpace that fits your needs and preferences.
            </p>

            <div class="flex items-center gap-3 mt-3">
              <a href="properties.php" class="btn btn-success btn-sm rounded-full px-5 text-white">
                Browse Listings
              </a>
              <a href="my_favorite.php" class="btn btn-ghost btn-sm rounded-full px-5">
                View Saved
              </a>
            </div>
          </div>
        </section>
        <section class="mb-6">
          <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-sm uppercase tracking-wider text-base-content/50">Engagement</h2>
            <span class="text-xs text-base-content/40">Updated just now</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($stats as $stat): ?>
            <a href="<?php echo htmlspecialchars($stat['href']); ?>"
              class="group relative flex items-center gap-4 p-5 bo bg-base-100 border border-gray-200 rounded-2xl shadow-xs hover:shadow-md hover:border-emerald-500/30 hover:-translate-y-0.5 transition-all duration-200">
              
              <!-- Icon Container -->
              <div class="size-12 shrink-0 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <?php echo $stat['icon']; ?>
                </svg>
              </div>

              <!-- Text Details -->
              <div class="min-w-0 flex-1">
                <p class="text-2xl font-bold tracking-tight text-base-content leading-none">
                  <?php echo number_format((int) $stat['value']); ?>
                </p>
                <p class="text-xs font-medium text-base-content/60 mt-1.5 truncate">
                  <?php echo htmlspecialchars($stat['label']); ?>
                </p>
              </div>

              <!-- Chevron Arrow -->
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/30 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>

              <!-- Indicator Badge -->
              <?php if (!empty($stat['badge'])): ?>
                <span class="absolute top-3 right-3 flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                </span>
              <?php endif; ?>

            </a>
          <?php endforeach; ?>
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
