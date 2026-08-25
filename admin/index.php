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
<body class="bg-base-100 no-scrollbar overflow-x-hidden min-h-screen">

  <!---alert-->
  <?php 
      include '../alerts.php'; 
  ?>

  <div class="drawer lg:drawer-open min-h-screen">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    
    <div class="drawer-content flex flex-col min-w-0 overflow-x-hidden">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Dashboard</div>
      </nav>

      <div class="p-6">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 w-full">
              
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
              
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

          <?php
        
            $reg_q_daily = mysqli_query($conn, "
              SELECT DATE(`date_request`) as label, COUNT(*) as total 
              FROM `accounts` 
              WHERE `user_type` > 1 AND `date_request` IS NOT NULL 
              GROUP BY DATE(`date_request`) 
              ORDER BY DATE(`date_request`) ASC
            ");
            $reg_d_labels = []; $reg_d_totals = [];
            while ($r = mysqli_fetch_assoc($reg_q_daily)) {
                $reg_d_labels[] = $r['label'];
                $reg_d_totals[] = (int)$r['total'];
            }

            $reg_q_weekly = mysqli_query($conn, "
              SELECT CONCAT('Week ', WEEK(MIN(`date_request`)), ' (', YEAR(MIN(`date_request`)), ')') as label, COUNT(*) as total 
              FROM `accounts` 
              WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`), WEEK(`date_request`) 
              ORDER BY MIN(`date_request`) ASC
            ");
            $reg_w_labels = []; $reg_w_totals = [];
            while ($r = mysqli_fetch_assoc($reg_q_weekly)) {
                $reg_w_labels[] = $r['label'];
                $reg_w_totals[] = (int)$r['total'];
            }

            $reg_q_monthly = mysqli_query($conn, "
              SELECT DATE_FORMAT(MIN(`date_request`), '%b %Y') as label, COUNT(*) as total 
              FROM `accounts` 
              WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`), MONTH(`date_request`) 
              ORDER BY MIN(`date_request`) ASC
            ");
            $reg_m_labels = []; $reg_m_totals = [];
            while ($r = mysqli_fetch_assoc($reg_q_monthly)) {
                $reg_m_labels[] = $r['label'];
                $reg_m_totals[] = (int)$r['total'];
            }

            $reg_q_yearly = mysqli_query($conn, "
              SELECT YEAR(`date_request`) as label, COUNT(*) as total 
              FROM `accounts` 
              WHERE `user_type` <= 2 AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`) 
              ORDER BY YEAR(`date_request`) ASC
            ");
            $reg_y_labels = []; $reg_y_totals = [];
            while ($r = mysqli_fetch_assoc($reg_q_yearly)) {
                $reg_y_labels[] = $r['label'];
                $reg_y_totals[] = (int)$r['total'];
            }

            /* ---- 2. APPROVED PROPERTIES (prop_) ---- */
            $prop_q_daily = $conn->query("
              SELECT DATE(`date_request`) as label, COUNT(*) as total 
              FROM `landlord` 
              WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
              GROUP BY DATE(`date_request`) 
              ORDER BY DATE(`date_request`) ASC
            ");
            $prop_d_labels = []; $prop_d_totals = [];
            while ($r = $prop_q_daily->fetch_assoc()) {
                $prop_d_labels[] = $r['label'];
                $prop_d_totals[] = (int)$r['total'];
            }

            $prop_q_weekly = $conn->query("
              SELECT CONCAT('Week ', WEEK(MIN(`date_request`)), ' (', YEAR(MIN(`date_request`)), ')') as label, COUNT(*) as total 
              FROM `landlord` 
              WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`), WEEK(`date_request`) 
              ORDER BY MIN(`date_request`) ASC
            ");
            $prop_w_labels = []; $prop_w_totals = [];
            while ($r = $prop_q_weekly->fetch_assoc()) {
                $prop_w_labels[] = $r['label'];
                $prop_w_totals[] = (int)$r['total'];
            }

            $prop_q_monthly = $conn->query("
              SELECT DATE_FORMAT(MIN(`date_request`), '%b %Y') as label, COUNT(*) as total 
              FROM `landlord` 
              WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`), MONTH(`date_request`) 
              ORDER BY MIN(`date_request`) ASC
            ");
            $prop_m_labels = []; $prop_m_totals = [];
            while ($r = $prop_q_monthly->fetch_assoc()) {
                $prop_m_labels[] = $r['label'];
                $prop_m_totals[] = (int)$r['total'];
            }

            $prop_q_yearly = $conn->query("
              SELECT YEAR(`date_request`) as label, COUNT(*) as total 
              FROM `landlord` 
              WHERE `status` = 'Approved' AND `date_request` IS NOT NULL 
              GROUP BY YEAR(`date_request`) 
              ORDER BY YEAR(`date_request`) ASC
            ");
            $prop_y_labels = []; $prop_y_totals = [];
            while ($r = $prop_q_yearly->fetch_assoc()) {
                $prop_y_labels[] = $r['label'];
                $prop_y_totals[] = (int)$r['total'];
            }

            /* ---- 3. REPORTS (rep_) ---- */
            $rep_q_daily = $conn->query("SELECT DATE(date_reported) as period, COUNT(*) as total FROM `report` GROUP BY DATE(date_reported) ORDER BY period ASC");
            $rep_d_labels = []; $rep_d_totals = [];
            while ($row = $rep_q_daily->fetch_assoc()) {
                $rep_d_labels[] = date('M d, Y', strtotime($row['period']));
                $rep_d_totals[] = (int)$row['total'];
            }

            $rep_q_weekly = $conn->query("SELECT YEARWEEK(date_reported, 1) as period, COUNT(*) as total FROM `report` GROUP BY YEARWEEK(date_reported, 1) ORDER BY period ASC");
            $rep_w_labels = []; $rep_w_totals = [];
            while ($row = $rep_q_weekly->fetch_assoc()) {
                $rep_w_labels[] = "Week " . substr($row['period'], 4) . " (" . substr($row['period'], 0, 4) . ")";
                $rep_w_totals[] = (int)$row['total'];
            }

            $rep_q_monthly = $conn->query("SELECT DATE_FORMAT(date_reported, '%Y-%m') as period, COUNT(*) as total FROM `report` GROUP BY DATE_FORMAT(date_reported, '%Y-%m') ORDER BY period ASC");
            $rep_m_labels = []; $rep_m_totals = [];
            while ($row = $rep_q_monthly->fetch_assoc()) {
                $rep_m_labels[] = date('F Y', strtotime($row['period'] . '-01'));
                $rep_m_totals[] = (int)$row['total'];
            }

            $rep_q_yearly = $conn->query("SELECT YEAR(date_reported) as period, COUNT(*) as total FROM `report` GROUP BY YEAR(date_reported) ORDER BY period ASC");
            $rep_y_labels = []; $rep_y_totals = [];
            while ($row = $rep_q_yearly->fetch_assoc()) {
                $rep_y_labels[] = (string)$row['period'];
                $rep_y_totals[] = (int)$row['total'];
            }
          ?>

          <section class="p-6 bg-slate-50 rounded-2xl">
            <div class="w-full bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-w-0">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h2 class="text-xl font-bold text-slate-800">Account Registration Analytics</h2>
                  <p class="text-sm text-slate-500">User registrations trend over time</p>
                </div>
                <div class="inline-flex p-1 bg-slate-100 rounded-lg text-sm font-medium text-slate-600 self-start sm:self-auto">
                  <button onclick="updateChart('reg', 'daily')"   id="reg-btn-daily"   class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
                  <button onclick="updateChart('reg', 'weekly')"  id="reg-btn-weekly"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
                  <button onclick="updateChart('reg', 'monthly')" id="reg-btn-monthly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
                  <button onclick="updateChart('reg', 'yearly')"  id="reg-btn-yearly"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
                </div>
              </div>
              <div id="reg-chart" class="w-full h-80 min-w-0"></div>
            </div>
          </section>

          <section class="p-6 bg-slate-50 rounded-2xl">
            <div class="w-full bg-white p-6 rounded-xl border border-slate-200 shadow-sm min-w-0">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h2 class="text-xl font-bold text-slate-800">Approved Properties Analytics</h2>
                  <p class="text-sm text-slate-500">Properties approved over time</p>
                </div>
                <div class="inline-flex p-1 bg-slate-100 rounded-lg text-sm font-medium text-slate-600 self-start sm:self-auto">
                  <button onclick="updateChart('prop', 'daily')"   id="prop-btn-daily"   class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
                  <button onclick="updateChart('prop', 'weekly')"  id="prop-btn-weekly"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
                  <button onclick="updateChart('prop', 'monthly')" id="prop-btn-monthly" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
                  <button onclick="updateChart('prop', 'yearly')"  id="prop-btn-yearly"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
                </div>
              </div>
              <div id="prop-chart" class="w-full h-80 min-w-0"></div>
            </div>
          </section>

          <section class="p-6 bg-slate-50 rounded-2xl">
            <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
              <h3 class="text-slate-800 font-bold text-lg">Reports Overview</h3>
              <div class="bg-slate-200/80 p-1 rounded-lg flex space-x-1 text-sm font-medium text-slate-600">
                <button id="rep-btn-daily"   onclick="updateChart('rep', 'daily')"   class="px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all">Daily</button>
                <button id="rep-btn-weekly"  onclick="updateChart('rep', 'weekly')"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Weekly</button>
                <button id="rep-btn-monthly" onclick="updateChart('rep', 'monthly')" class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Monthly</button>
                <button id="rep-btn-yearly"  onclick="updateChart('rep', 'yearly')"  class="px-3 py-1.5 rounded-md hover:text-slate-800 transition-all">Yearly</button>
              </div>
            </div>
            <div id="rep-chart"></div>
          </section>

          <script>
            // ---- One config object per chart, keyed by prefix (reg / prop / rep) ----
            const chartConfigs = {
              reg: {
                containerId: 'reg-chart',
                seriesName: 'Registrations',
                color: '#2563eb',
                datasets: {
                  daily:   { categories: <?php echo json_encode($reg_d_labels); ?>, data: <?php echo json_encode($reg_d_totals); ?> },
                  weekly:  { categories: <?php echo json_encode($reg_w_labels); ?>, data: <?php echo json_encode($reg_w_totals); ?> },
                  monthly: { categories: <?php echo json_encode($reg_m_labels); ?>, data: <?php echo json_encode($reg_m_totals); ?> },
                  yearly:  { categories: <?php echo json_encode($reg_y_labels); ?>, data: <?php echo json_encode($reg_y_totals); ?> }
                }
              },
              prop: {
                containerId: 'prop-chart',
                seriesName: 'Approved Properties',
                color: '#059669',
                datasets: {
                  daily:   { categories: <?php echo json_encode($prop_d_labels); ?>, data: <?php echo json_encode($prop_d_totals); ?> },
                  weekly:  { categories: <?php echo json_encode($prop_w_labels); ?>, data: <?php echo json_encode($prop_w_totals); ?> },
                  monthly: { categories: <?php echo json_encode($prop_m_labels); ?>, data: <?php echo json_encode($prop_m_totals); ?> },
                  yearly:  { categories: <?php echo json_encode($prop_y_labels); ?>, data: <?php echo json_encode($prop_y_totals); ?> }
                }
              },
              rep: {
                containerId: 'rep-chart',
                seriesName: 'Reports Count',
                color: '#dc2626',
                datasets: {
                  daily:   { categories: <?php echo json_encode($rep_d_labels); ?>, data: <?php echo json_encode($rep_d_totals); ?> },
                  weekly:  { categories: <?php echo json_encode($rep_w_labels); ?>, data: <?php echo json_encode($rep_w_totals); ?> },
                  monthly: { categories: <?php echo json_encode($rep_m_labels); ?>, data: <?php echo json_encode($rep_m_totals); ?> },
                  yearly:  { categories: <?php echo json_encode($rep_y_labels); ?>, data: <?php echo json_encode($rep_y_totals); ?> }
                }
              }
            };

            // One ApexCharts instance per prefix, so we can .destroy() before re-render
            const chartInstances = {};

            function renderChart(prefix, period) {
              const config = chartConfigs[prefix];
              const dataset = config.datasets[period];
              const container = document.querySelector('#' + config.containerId);
              if (!container) return;

              if (chartInstances[prefix]) {
                chartInstances[prefix].destroy();
              }
              container.innerHTML = '';

              const options = {
                series: [{ name: config.seriesName, data: dataset.data }],
                chart: {
                  type: 'bar',
                  height: 320,
                  toolbar: { show: false },
                  fontFamily: 'Inter, sans-serif',
                  animations: { enabled: true }
                },
                colors: [config.color],
                plotOptions: {
                  bar: {
                    horizontal: false,
                    columnWidth: dataset.data.length < 5 ? '45%' : '55%',
                    borderRadius: 4
                  }
                },
                dataLabels: {
                  enabled: true,
                  style: { fontSize: '1rem', fontWeight: '700', colors: ['#ffffff'] },
                  formatter: (val) => (val > 0 ? val : ''),
                  dropShadow: { enabled: true, top: 1, left: 1, blur: 2, color: '#000000', opacity: 0.4 }
                },
                xaxis: {
                  categories: dataset.categories,
                  labels: { style: { colors: '#64748b', fontSize: '12px', fontWeight: '500' } }
                },
                yaxis: {
                  min: 0,
                  forceNiceScale: true,
                  labels: { style: { colors: '#64748b' }, formatter: (val) => Math.floor(val) }
                },
                grid: { borderColor: '#f1f5f9', padding: { top: 10, right: 20, left: 20 } },
                tooltip: { theme: 'light' }
              };

              chartInstances[prefix] = new ApexCharts(container, options);
              chartInstances[prefix].render();
            }

            function updateChart(prefix, period) {
              renderChart(prefix, period);
              ['daily', 'weekly', 'monthly', 'yearly'].forEach(p => {
                const btn = document.getElementById(`${prefix}-btn-${p}`);
                if (!btn) return;
                btn.className = (p === period)
                  ? 'px-3 py-1.5 rounded-md bg-white text-slate-800 shadow-sm transition-all'
                  : 'px-3 py-1.5 rounded-md hover:text-slate-800 transition-all';
              });
            }

            // Initial load for all three charts
            renderChart('reg', 'daily');
            renderChart('prop', 'daily');
            renderChart('rep', 'daily');
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