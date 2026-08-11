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
<!-- INAYOS: Binigyan ng overflow-x-hidden at min-h-screen -->
<body class="bg-base-100 no-scrollbar overflow-x-hidden min-h-screen">

  <!---alert-->
  <?php 
      include '../alerts.php'; 
  ?>

  <div class="drawer lg:drawer-open min-h-screen">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    
    <!-- INAYOS: Binigyan ng min-w-0 at overflow-x-hidden para hindi lumagpas ang main content -->
    <div class="drawer-content flex flex-col min-w-0 overflow-x-hidden">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Dashboard</div>
      </nav>

      <div class="p-6">
        <!--main content-->
        <main class="space-y-6">
          <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0fab9e]/10 via-base-100 to-base-100 border border-base-200 p-6 md:p-8">
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

          <section class="p-6 bg-slate-50 rounded-2xl">
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

            <!-- INAYOS: Pinalitan ang w-[100%] ng w-full -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 w-full">
              
              <!-- Verified Card -->
              <div class="bg-white p-5 rounded-xl border border-emerald-100 cursor-pointer shadow-sm flex items-center justify-between min-w-0" onclick="location.href='accounts.php'">
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
              <div class="bg-white p-5 rounded-xl border border-amber-100 cursor-pointer shadow-sm flex items-center justify-between min-w-0" onclick="location.href='request_accounts.php'">
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
              <div class="bg-white p-5 rounded-xl border border-rose-100 cursor-pointer shadow-sm flex items-center justify-between min-w-0" onclick="location.href='disapproved_accounts.php'">
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
              <div class="bg-white p-5 rounded-xl border border-slate-200 cursor-pointer shadow-sm flex items-center justify-between min-w-0" onclick="location.href='blocked_accounts.php'">
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

          <section class="p-6 bg-slate-50 rounded-2xl">
            <?php
              $res1 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Approved'");
              $verified_properties = mysqli_fetch_assoc($res1)['total'] ?? 0;

              $res2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Pending'");
              $pending_properties = mysqli_fetch_assoc($res2)['total'] ?? 0;

              $res3 = mysqli_query($conn, "SELECT COUNT(*) as total FROM `landlord` WHERE `status` = 'Disapproved'");
              $disapproved_properties = mysqli_fetch_assoc($res3)['total'] ?? 0;
            ?>

            <!-- INAYOS: Pinalitan ang w-[100%] ng w-full -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
              
              <!-- Verified Properties Card -->
              <div class="bg-white p-5 rounded-xl border border-emerald-100 shadow-sm flex items-center justify-between cursor-pointer hover:border-emerald-300 transition-colors min-w-0" onclick="location.href='verified_properties.php'">
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
              <div class="bg-white p-5 rounded-xl border border-amber-100 shadow-sm flex items-center justify-between cursor-pointer hover:border-amber-300 transition-colors min-w-0" onclick="location.href='pending_properties.php'">
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

            </div>
          </section>
         </section>
          <section class="p-6 bg-slate-50 rounded-2xl">
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

            <div class="w-full bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-w-0">
              <!-- Header & Toggle Buttons -->
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h2 class="text-xl font-bold text-slate-800">Account Registration Analytics</h2>
                  <p class="text-sm text-slate-500">User registrations trend over time </p>
                </div>

                <!-- Filter Controls -->
                <div class="inline-flex p-1 bg-slate-100 rounded-lg text-sm font-medium text-slate-600 self-start sm:self-auto">
                  <button onclick="updateChart('daily')" id="btn-daily" class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
                  <button onclick="updateChart('weekly')" id="btn-weekly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
                  <button onclick="updateChart('monthly')" id="btn-monthly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
                  <button onclick="updateChart('yearly')" id="btn-yearly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
                </div>
              </div>

              <!-- Chart Container -->
              <div id="registrationChart" class="w-full h-80 min-w-0"></div>
            </div>
          </section>
        <section class="p-6 bg-slate-50 rounded-2xl">
            <?php

              $q_daily = $conn->query("
                SELECT DATE(`date_request`) as label, COUNT(*) as total 
                FROM `landlord` 
                WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
                GROUP BY DATE(`date_request`) 
                ORDER BY DATE(`date_request`) ASC
              ");
              $d_labels = []; $d_totals = [];
              while ($r = $q_daily->fetch_assoc()) {
                  $d_labels[] = $r['label'];
                  $d_totals[] = (int)$r['total'];
              }

              $q_weekly = $conn->query("
                SELECT CONCAT('Week ', WEEK(MIN(`date_request`)), ' (', YEAR(MIN(`date_request`)), ')') as label, COUNT(*) as total 
                FROM `landlord` 
                WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
                GROUP BY YEAR(`date_request`), WEEK(`date_request`) 
                ORDER BY MIN(`date_request`) ASC
              ");
              $w_labels = []; $w_totals = [];
              while ($r = $q_weekly->fetch_assoc()) {
                  $w_labels[] = $r['label'];
                  $w_totals[] = (int)$r['total'];
              }

              $q_monthly = $conn->query("
                SELECT DATE_FORMAT(MIN(`date_request`), '%b %Y') as label, COUNT(*) as total 
                FROM `landlord` 
                WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
                GROUP BY YEAR(`date_request`), MONTH(`date_request`) 
                ORDER BY MIN(`date_request`) ASC
              ");
              $m_labels = []; $m_totals = [];
              while ($r = $q_monthly->fetch_assoc()) {
                  $m_labels[] = $r['label'];
                  $m_totals[] = (int)$r['total'];
              }

              $q_yearly = $conn->query("
                SELECT YEAR(`date_request`) as label, COUNT(*) as total 
                FROM `landlord` 
                WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
                GROUP BY YEAR(`date_request`) 
                ORDER BY YEAR(`date_request`) ASC
              ");
              $y_labels = []; $y_totals = [];
              while ($r = $q_yearly->fetch_assoc()) {
                  $y_labels[] = $r['label'];
                  $y_totals[] = (int)$r['total'];
              }
            ?>

            <div class="w-full bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-w-0">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h2 class="text-xl font-bold text-slate-800">Approved Properties Analytics</h2>
                  <p class="text-sm text-slate-500">Properties approved over time</p>
                </div>

                <div class="inline-flex p-1 bg-slate-100 rounded-lg text-sm font-medium text-slate-600 self-start sm:self-auto">
                  <button onclick="updatePropChart('daily')" id="prop-btn-daily" class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
                  <button onclick="updatePropChart('weekly')" id="prop-btn-weekly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
                  <button onclick="updatePropChart('monthly')" id="prop-btn-monthly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
                  <button onclick="updatePropChart('yearly')" id="prop-btn-yearly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
                </div>
              </div>

              <div id="propertiesChart" class="w-full h-80 min-w-0"></div>
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


<script>
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

  let chart = null;

  function renderChart(period) {
    const dataset = chartDatasets[period];
    const container = document.querySelector("#registrationChart");

    if (chart) {
      chart.destroy();
    }
    container.innerHTML = '';

    const options = {
      series: [{
        name: 'Registered Accounts',
        data: dataset.data
      }],
      chart: {
        type: 'bar',
        height: 320,
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif',
        animations: { enabled: true }
      },
      colors: ['#2563eb'],
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: dataset.data.length < 5 ? '45%' : '55%',
          borderRadius: 4,
          dataLabels: {
            position: 'center'  
          }
        }
      },
      dataLabels: {
        enabled: true,
        enabledOnSeries: [0],
        offsetY: 0,
        offsetX: 0,
        textAnchor: 'middle',
        style: {
          fontSize: '2rem',       
          fontWeight: '800',      
          colors: ['#ffffff']     
        },
        formatter: function (val) {
          return val > 0 ? val : '';
        },
        background: {
          enabled: false       
        },
        dropShadow: {
          enabled: true,
          top: 1,
          left: 1,
          blur: 2,
          color: '#000000',
          opacity: 0.4    
        }
      },
      xaxis: {
        categories: dataset.categories,
        labels: { 
          style: { colors: '#64748b', fontSize: '13px', fontWeight: '500' } 
        }
      },
      yaxis: {
        min: 0,
        forceNiceScale: true,
        labels: {
          style: { colors: '#64748b' },
          formatter: (val) => Math.floor(val)
        }
      },
      grid: {
        borderColor: '#f1f5f9',
        padding: { top: 10, right: 20, left: 20 }
      },
      tooltip: { theme: 'light' }
    };

    chart = new ApexCharts(container, options);
    chart.render();
  }

  renderChart('daily');

  function updateChart(period) {
    renderChart(period);

    ['daily', 'weekly', 'monthly', 'yearly'].forEach(p => {
      const btn = document.getElementById(`btn-${p}`);
      btn.className = (p === period) 
        ? "px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all"
        : "px-3 py-1.5 rounded-md hover:text-slate-800 transition-all";
    });
  }
</script>



<script>
  const propDatasets = {
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

  let propChart = null;

  function renderPropChart(period) {
    const dataset = propDatasets[period];
    const container = document.querySelector("#propertiesChart");

    if (propChart) {
      propChart.destroy();
    }
    container.innerHTML = '';

    const options = {
      series: [{
        name: 'Approved Properties',
        data: dataset.data
      }],
      chart: {
        type: 'bar',
        height: 320,
        toolbar: { show: false },
        fontFamily: 'Inter, sans-serif',
        animations: { enabled: true }
      },
      colors: ['#059669'], 
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: dataset.data.length < 5 ? '45%' : '55%',
          borderRadius: 4,
          dataLabels: {
            position: 'center'
          }
        }
      },
      dataLabels: {
        enabled: true,
        enabledOnSeries: [0],
        offsetY: 0,
        offsetX: 0,
        textAnchor: 'middle',
        style: {
          fontSize: '20px',
          fontWeight: '800',
          colors: ['#ffffff']
        },
        formatter: function (val) {
          return val > 0 ? val : '';
        },
        background: { enabled: false },
        dropShadow: {
          enabled: true,
          top: 1,
          left: 1,
          blur: 2,
          color: '#000000',
          opacity: 0.4
        }
      },
      xaxis: {
        categories: dataset.categories,
        labels: { 
          style: { colors: '#64748b', fontSize: '13px', fontWeight: '500' } 
        }
      },
      yaxis: {
        min: 0,
        forceNiceScale: true,
        labels: {
          style: { colors: '#64748b' },
          formatter: (val) => Math.floor(val)
        }
      },
      grid: {
        borderColor: '#f1f5f9',
        padding: { top: 10, right: 20, left: 20 }
      },
      tooltip: { theme: 'light' }
    };

    propChart = new ApexCharts(container, options);
    propChart.render();
  }

  renderPropChart('daily');

  function updatePropChart(period) {
    renderPropChart(period);

    ['daily', 'weekly', 'monthly', 'yearly'].forEach(p => {
      const btn = document.getElementById(`prop-btn-${p}`);
      btn.className = (p === period) 
        ? "px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all"
        : "px-3 py-1.5 rounded-md hover:text-slate-800 transition-all";
    });
  }
</script>