
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
    <title>Dashboard</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
    <script src="../assets/scripts/apex_chart.js"></script>
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
                <span class="text-[#0fab9e] font-extrabold uppercase">ADMIN</span>
              </h1>

              <p class="text-sm md:text-base text-base-content/60 max-w-md">
                Manage Accounts Registration and Properties
              </p>
            </div>
          </section>
          <section class="p-6 bg-slate-50">
            <?php
        
              $res1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `accounts` WHERE `status` = 'Approved'");
              $verified = mysqli_fetch_assoc($res1)['total'] ?? 0;

              $res2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `accounts` WHERE `status` = 'Pending'");
              $pending = mysqli_fetch_assoc($res2)['total'] ?? 0;

              $res3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `accounts` WHERE `status` = 'Disapproved'");
              $disapproved = mysqli_fetch_assoc($res3)['total'] ?? 0;

              $res4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `accounts` WHERE `status` = 'Blocked'");
              $blocked = mysqli_fetch_assoc($res4)['total'] ?? 0;
            ?>

            <!-- 4-Column Responsive Tailwind Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5  mx-auto">
              
              <!-- Verified Card -->
              <div class="bg-white p-5 rounded-xl border border-emerald-100 cursor-pointer shadow-sm flex items-center justify-between" onclick="location.ref='accounts.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Verified</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $verified; ?></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>

              <!-- Pending Card -->
              <div class="bg-white p-5 rounded-xl border border-amber-100 cursor-pointer shadow-sm flex items-center justify-between" onclick="location.ref='request_accounts.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $pending; ?></h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

              <!-- Disapproved Card -->
              <div class="bg-white p-5 rounded-xl border border-rose-100 cursor-pointer shadow-sm flex items-center justify-between" onclick="location.ref='disapproved_accounts.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Disapproved</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $disapproved; ?></h3>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </div>
              </div>

              <!-- Blocked Card -->
              <div class="bg-white p-5 rounded-xl border border-slate-200 cursor-pointer shadow-sm flex items-center justify-between" onclick="location.ref='blocked_accounts.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Blocked</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $blocked; ?></h3>
                </div>
                <div class="p-3 bg-slate-100 text-slate-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                  </svg>
                </div>
              </div>
            </div>
          </section>
          <section class="p-6 bg-slate-50">
            <?php
         
              $res1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Approved'");
              $verified_properties = mysqli_fetch_assoc($res1)['total'] ?? 0;

            
              $res2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Pending'");
              $pending_properties = mysqli_fetch_assoc($res2)['total'] ?? 0;

             
              $res3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Disapproved'");
              $disapproved_properties = mysqli_fetch_assoc($res3)['total'] ?? 0;
            ?>

            <!-- 3-Column Responsive Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mx-auto">
              
              <!-- Verified Properties Card -->
              <div class="bg-white p-5 rounded-xl border border-emerald-100 shadow-sm flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors" onclick="location.href='verified_properties.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Verified Properties</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $verified_properties; ?></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                </div>
              </div>

              <!-- Pending Properties Card -->
              <div class="bg-white p-5 rounded-xl border border-amber-100 shadow-sm flex items-center justify-between cursor-pointer hover:border-amber-300 transition-colors" onclick="location.href='pending_properties.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending Properties</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php echo $pending_properties; ?></h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

              <!-- Disapproved Properties Card
              <div class="bg-white p-5 rounded-xl border border-rose-100 shadow-sm flex items-center justify-between cursor-pointer hover:border-rose-300 transition-colors" onclick="location.href='disapproved_properties.php'">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-wider text-rose-600">Disapproved Properties</p>
                  <h3 class="text-3xl font-bold text-slate-800 mt-1"><?php // echo $disapproved_properties; ?></h3>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-lg">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                  </svg>
                </div>
              </div> -->

            </div>
          </section>
     <section class="p-6 bg-slate-50">
 <?php
  // --- 1. DAILY QUERY ---
  $q_daily = mysqli_query($conn, "
    SELECT DATE(`date_request`) as label, COUNT(*) as total 
    FROM `accounts` 
    WHERE `user_type` > 1 AND `date_request` IS NOT NULL 
    GROUP BY DATE(`date_request`) 
    ORDER BY DATE(`date_request`) ASC
  ");
  $d_labels = []; $d_totals = [];
  while ($r = mysqli_fetch_assoc($q_daily)) {
      $d_labels[] = $r['label'];
      $d_totals[] = (int)$r['total'];
  }

  // --- 2. WEEKLY QUERY ---
  $q_weekly = mysqli_query($conn, "
    SELECT CONCAT('Week ', WEEK(MIN(`date_request`)), ' (', YEAR(MIN(`date_request`)), ')') as label, COUNT(*) as total 
    FROM `accounts` 
    WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
    GROUP BY YEAR(`date_request`), WEEK(`date_request`) 
    ORDER BY MIN(`date_request`) ASC
  ");
  $w_labels = []; $w_totals = [];
  while ($r = mysqli_fetch_assoc($q_weekly)) {
      $w_labels[] = $r['label'];
      $w_totals[] = (int)$r['total'];
  }

  // --- 3. MONTHLY QUERY ---
  $q_monthly = mysqli_query($conn, "
    SELECT DATE_FORMAT(MIN(`date_request`), '%b %Y') as label, COUNT(*) as total 
    FROM `accounts` 
    WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
    GROUP BY YEAR(`date_request`), MONTH(`date_request`) 
    ORDER BY MIN(`date_request`) ASC
  ");
  $m_labels = []; $m_totals = [];
  while ($r = mysqli_fetch_assoc($q_monthly)) {
      $m_labels[] = $r['label'];
      $m_totals[] = (int)$r['total'];
  }

  // --- 4. YEARLY QUERY ---
  $q_yearly = mysqli_query($conn, "
    SELECT YEAR(`date_request`) as label, COUNT(*) as total 
    FROM `accounts` 
    WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
    GROUP BY YEAR(`date_request`) 
    ORDER BY YEAR(`date_request`) ASC
  ");
  $y_labels = []; $y_totals = [];
  while ($r = mysqli_fetch_assoc($q_yearly)) {
      $y_labels[] = $r['label'];
      $y_totals[] = (int)$r['total'];
  }
?>
  <div class="mx-auto bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
    <!-- Header & Toggle Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h2 class="text-xl font-bold text-slate-800">Account Registration Analytics</h2>
        <p class="text-sm text-slate-500">User registrations trend over time (User Type &le; 2)</p>
      </div>

      <!-- Filter Controls -->
      <div class="inline-flex p-1 bg-slate-100 rounded-lg text-sm font-medium text-slate-600">
        <button onclick="updateChart('daily')" id="btn-daily" class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
        <button onclick="updateChart('weekly')" id="btn-weekly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
        <button onclick="updateChart('monthly')" id="btn-monthly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
        <button onclick="updateChart('yearly')" id="btn-yearly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
      </div>
    </div>

    <!-- Chart Container -->
    <div id="registrationChart" class="w-full h-80"></div>
  </div>
</section>
<script>
  // Clean JSON array outputs from PHP
  const chartDatasets = {
    daily: {
      categories: <?php echo json_encode($d_labels); ?>,
      data: <?php echo json_encode($d_totals); ?>
    },
    weekly: {
      categories: <?php echo json_encode($w_labels); ?>,
      data: <?php echo json_encode($w_totals); ?>
    },
    monthly: {
      categories: <?php echo json_encode($m_labels); ?>,
      data: <?php echo json_encode($m_totals); ?>
    },
    yearly: {
      categories: <?php echo json_encode($y_labels); ?>,
      data: <?php echo json_encode($y_totals); ?>
    }
  };

  // Bar Chart Configuration
  const options = {
    series: [{
      name: 'Registered Accounts',
      data: chartDatasets.daily.data
    }],
    chart: {
      type: 'bar', // Changed from 'line' to 'bar'
      height: 320,
      toolbar: { show: false },
      fontFamily: 'Inter, sans-serif'
    },
    colors: ['#2563eb'], // Primary Blue color
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '40%',     // Width of the bars
        borderRadius: 6,         // Rounded tops for bars
        endingShape: 'rounded'
      }
    },
    dataLabels: { 
      enabled: true,             // Shows numbers on top of the bars
      style: {
        fontSize: '12px',
        colors: ['#fff']
      }
    },
    xaxis: {
      categories: chartDatasets.daily.categories,
      labels: { style: { colors: '#64748b' } }
    },
    yaxis: {
      min: 0,
      forceNiceScale: true,
      labels: {
        style: { colors: '#64748b' },
        formatter: (val) => Math.floor(val)
      }
    },
    grid: { borderColor: '#f1f5f9' },
    tooltip: { theme: 'light' }
  };

  const chart = new ApexCharts(document.querySelector("#registrationChart"), options);
  chart.render();

  function updateChart(period) {
    chart.updateOptions({
      series: [{
        name: 'Registered Accounts',
        data: chartDatasets[period].data
      }],
      xaxis: {
        categories: chartDatasets[period].categories
      }
    });

    ['daily', 'weekly', 'monthly', 'yearly'].forEach(p => {
      const btn = document.getElementById(`btn-${p}`);
      btn.className = (p === period) 
        ? "px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all"
        : "px-3 py-1.5 rounded-md hover:text-slate-800 transition-all";
    });
  }
</script>
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
