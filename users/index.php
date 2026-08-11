<?php
  include '../config.php';
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }


  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

  // --- Visited properties (recent viewing history for this user) ---
  // Single JOIN instead of a query-per-row loop.
  $visited_sql = "SELECT rv.landlord_id, rv.rent_id, rv.date_viewed, rv.time_viewed,
                          l.property_name, l.type, rs.name AS rentspace_name
                   FROM `rent_views` rv
                   JOIN `landlord` l ON l.landlord_id = rv.landlord_id
                   JOIN `rentspace` rs ON rs.rent_id = rv.rent_id
                   WHERE rv.user_id = ?
                   ORDER BY rv.date_viewed DESC, rv.time_viewed DESC
                   LIMIT 50";
  $visited = $conn->prepare($visited_sql);
  $visited->bind_param("s", $user_id_login);
  $visited->execute();
  $result_visited = $visited->get_result();

  // Maps a property type to its details page. Add new types here as they're introduced.
  function get_details_page(?string $type): string {
      switch ($type) {
          case 'Boarding House / Bedspace':
              return 'boarding_details.php';
          case 'Apartment':
              return 'apartment_details.php';
          default:
              return 'property.php'; // fallback for unknown/future types
      }
  }

  $visited_properties = [];
  if ($result_visited->num_rows > 0) {
      while ($row_visit = $result_visited->fetch_assoc()) {
   
          $row_visit['viewed_at'] = trim($row_visit['date_viewed'] . ' ' . $row_visit['time_viewed']);
          $row_visit['locate']    = get_details_page($row_visit['type']);
          $visited_properties[] = $row_visit;
      }
  }


  function time_ago(string $datetime): string {
      $ts = strtotime($datetime);
      if ($ts === false) return $datetime;

      $diff = time() - $ts;
      if ($diff < 60)          return 'Just now';
      if ($diff < 3600)        return floor($diff / 60) . ' min ago';
      if ($diff < 86400)       return floor($diff / 3600) . ' hr' . (floor($diff / 3600) > 1 ? 's' : '') . ' ago';
      if ($diff < 604800)      return floor($diff / 86400) . ' day' . (floor($diff / 86400) > 1 ? 's' : '') . ' ago';
      return date('M j, Y', $ts);
  }

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
          'href'  => 'my_favorite.php',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>',
          'badge' => false,
      ],
      [
          'label' => 'Listed Properties',
          'value' => $total_properties,
          'href'  => 'property_requests.php',
          'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>',
          'badge' => false,
      ],
  ];

//peroty voews
  $landlord_ids = [];

  $properties = $conn->prepare("SELECT landlord_id, property_name FROM `landlord` WHERE `user_id` = ?");
  $properties->bind_param("s", $user_id_login);
  $properties->execute();
  $result_landlord = $properties->get_result();

  if ($result_landlord->num_rows > 0) {
      while ($row_landlord = $result_landlord->fetch_assoc()) {
          $landlord_ids[] = $row_landlord['landlord_id'];
      }
  }

  $daily   = []; // 'Y-m-d'            => count
  $weekly  = []; // 'oo-Www' e.g. 2026-W32 => count
  $monthly = []; // 'Y-m'              => count
  $yearly  = []; // 'Y'                => count

  if (!empty($landlord_ids)) {
      $placeholders = implode(',', array_fill(0, count($landlord_ids), '?'));
      $types = str_repeat('s', count($landlord_ids));

      $sql = "SELECT landlord_id, date_viewed FROM `rent_views` WHERE `landlord_id` IN ($placeholders)";
      $get_views = $conn->prepare($sql);
      $get_views->bind_param($types, ...$landlord_ids);
      $get_views->execute();
      $result_view = $get_views->get_result();

      if ($result_view->num_rows > 0) {
          while ($row_view = $result_view->fetch_assoc()) {
              $ts = strtotime($row_view['date_viewed']);
              if ($ts === false) continue;

              $dayKey   = date('Y-m-d', $ts);
              $weekKey  = date('o', $ts) . '-W' . date('W', $ts);
              $monthKey = date('Y-m', $ts);
              $yearKey  = date('Y', $ts);

              $daily[$dayKey]     = ($daily[$dayKey] ?? 0) + 1;
              $weekly[$weekKey]   = ($weekly[$weekKey] ?? 0) + 1;
              $monthly[$monthKey] = ($monthly[$monthKey] ?? 0) + 1;
              $yearly[$yearKey]   = ($yearly[$yearKey] ?? 0) + 1;
          }
      }
  }

  ksort($daily);
  ksort($weekly);
  ksort($monthly);
  ksort($yearly);

  $daily   = array_slice($daily, -30, null, true);   // last 30 days
  $weekly  = array_slice($weekly, -26, null, true);  // last 26 weeks
  $monthly = array_slice($monthly, -12, null, true); // last 12 months
  $yearly  = array_slice($yearly, -5, null, true);   // last 5 years

  $chartData = [
      'daily'   => ['labels' => array_keys($daily),   'data' => array_values($daily)],
      'weekly'  => ['labels' => array_keys($weekly),  'data' => array_values($weekly)],
      'monthly' => ['labels' => array_keys($monthly), 'data' => array_values($monthly)],
      'yearly'  => ['labels' => array_keys($yearly),  'data' => array_values($yearly)],
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
    <script src="../assets/scripts/apex_chart.js"></script>
    
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
              <span class="text-[#0fab9e] font-extrabold uppercase"><?php echo htmlspecialchars($firstnameko ?? ''); ?></span>
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

        <section class="mb-8">
          <div class="flex items-end justify-between mb-4">
            <div>
              <h2 class="font-bold text-base text-base-content tracking-tight">Engagement</h2>
              <p class="text-xs text-base-content/50 mt-0.5">Your activity at a glance</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs text-base-content/40">
              <span class="size-1.5 rounded-full bg-[#0fab9e] animate-pulse"></span>
              Updated just now
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <?php foreach ($stats as $stat): ?>
            <a href="<?php echo htmlspecialchars($stat['href']); ?>"
              class="group relative flex items-center gap-4 p-5 bg-white border border-gray-200/80 rounded-2xl shadow-sm hover:shadow-lg hover:border-[#0fab9e]/40 hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

              <!-- accent edge, revealed on hover -->
              <span class="absolute inset-y-0 left-0 w-1 bg-[#0fab9e] scale-y-0 group-hover:scale-y-100 origin-center transition-transform duration-200"></span>

              <div class="size-12 shrink-0 rounded-xl bg-[#0fab9e]/10 flex items-center justify-center text-[#0fab9e] group-hover:bg-[#0fab9e] group-hover:text-white transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <?php echo $stat['icon']; ?>
                </svg>
              </div>

              <div class="min-w-0 flex-1">
                <p class="text-2xl font-bold tracking-tight text-base-content leading-none tabular-nums">
                  <?php echo number_format((int) $stat['value']); ?>
                </p>
                <p class="text-xs font-medium text-base-content/50 mt-1.5 uppercase tracking-wide truncate">
                  <?php echo htmlspecialchars($stat['label']); ?>
                </p>
              </div>

              <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/25 group-hover:text-[#0fab9e] group-hover:translate-x-1 transition-all duration-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>

              <?php if (!empty($stat['badge'])): ?>
                <span class="absolute top-3.5 right-3.5 flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500 ring-2 ring-white"></span>
                </span>
              <?php endif; ?>

            </a>
          <?php endforeach; ?>
          </div>
        </section>
        <section class="mb-6">
          <h2 class="mb-1 font-bold text-lg">Property Views</h2>
          <p class="text-sm text-base-content/60">Total views tracked for your properties.</p>

          <div class="flex flex-wrap gap-2 my-4">
            <button data-range="daily" class="filter-btn px-4 py-2 text-sm rounded-lg cursor-pointer transition-colors bg-blue-600 text-white border border-blue-600">Daily</button>
            <button data-range="weekly" class="filter-btn px-4 py-2 text-sm rounded-lg cursor-pointer transition-colors bg-gray-100 border border-gray-300 hover:bg-gray-200">Weekly</button>
            <button data-range="monthly" class="filter-btn px-4 py-2 text-sm rounded-lg cursor-pointer transition-colors bg-gray-100 border border-gray-300 hover:bg-gray-200">Monthly</button>
            <button data-range="yearly" class="filter-btn px-4 py-2 text-sm rounded-lg cursor-pointer transition-colors bg-gray-100 border border-gray-300 hover:bg-gray-200">Yearly</button>
          </div>

          <div class="relative h-[400px]">
            <div id="viewsChart"></div>
          </div>

          <?php if (empty($landlord_ids)): ?>
            <p class="text-gray-500 italic mt-3">No properties found for this account.</p>
          <?php elseif (array_sum($chartData['daily']['data']) === 0
                    && array_sum($chartData['weekly']['data']) === 0
                    && array_sum($chartData['monthly']['data']) === 0
                    && array_sum($chartData['yearly']['data']) === 0): ?>
            <p class="text-gray-500 italic mt-3">No views recorded yet for your properties.</p>
          <?php endif; ?>
        </section>
        <section class="mb-6">
          <div class="flex items-end justify-between mb-4">
            <div>
              <h2 class="font-bold text-lg text-base-content tracking-tight">Visited Properties</h2>
              <p class="text-xs text-base-content/50 mt-0.5">Properties you've recently looked at</p>
            </div>
            <?php if (!empty($visited_properties)): ?>
              <span class="text-xs text-base-content/40"><?php echo count($visited_properties); ?> total</span>
            <?php endif; ?>
          </div>

          <?php if (empty($visited_properties)): ?>
            <div class="flex flex-col items-center justify-center text-center gap-2 py-12 bg-white border border-dashed border-gray-300 rounded-2xl">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-base-content/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
              </svg>
              <p class="text-sm text-gray-500 italic">You haven't visited any properties yet.</p>
              <a href="properties.php" class="text-xs font-medium text-[#0fab9e] hover:underline">Browse listings &rarr;</a>
            </div>
          <?php else: ?>
            <div id="visitedList" class="bg-white border border-gray-200/80 rounded-2xl shadow-sm divide-y divide-gray-100 overflow-hidden">
              <?php foreach ($visited_properties as $i => $visit): ?>
                <a href="<?php echo htmlspecialchars($visit['locate']); ?>?id=<?php echo urlencode($visit['rent_id']); ?>"
                  class="visited-row group flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors duration-150<?php echo $i >= 8 ? ' hidden' : ''; ?>">

                  <div class="size-11 shrink-0 rounded-xl bg-[#0fab9e]/10 flex items-center justify-center text-[#0fab9e] group-hover:bg-[#0fab9e] group-hover:text-white transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/>
                    </svg>
                  </div>

                  <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-base-content truncate">
                      <?php echo htmlspecialchars($visit['rentspace_name'] ?? $visit['property_name'] ?? 'Unnamed property'); ?>
                    </p>
                    <?php if (!empty($visit['property_name'])): ?>
                      <p class="text-xs text-base-content/60 truncate mt-0.5">
                        <?php echo htmlspecialchars($visit['property_name']); ?>
                      </p>
                    <?php endif; ?>
                    <p class="text-xs text-base-content/40 mt-0.5">
                      Viewed <?php echo htmlspecialchars(time_ago($visit['viewed_at'])); ?>
                    </p>
                  </div>

                  <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/25 group-hover:text-[#0fab9e] group-hover:translate-x-1 transition-all duration-200 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
                </a>
              <?php endforeach; ?>
            </div>

            <?php if (count($visited_properties) > 8): ?>
              <div class="flex justify-center mt-3">
                <button id="toggleVisitedBtn" type="button"
                  class="flex items-center gap-1.5 text-sm font-medium text-[#0fab9e] hover:text-[#0c8a80] transition-colors py-2 px-4">
                  <span id="toggleVisitedLabel">Show all <?php echo count($visited_properties); ?></span>
                  <svg id="toggleVisitedIcon" xmlns="http://www.w3.org/2000/svg" class="size-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
              </div>
            <?php endif; ?>
          <?php endif; ?>
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
    const chartData = <?php echo json_encode($chartData); ?>;

    let viewsChart = null;

    function renderViewsChart(range) {
      const container = document.querySelector("#viewsChart");
      const dataset = chartData[range];

      if (viewsChart) {
        viewsChart.destroy();
      }
      container.innerHTML = '';

      const titles = {
        daily: 'Daily Views',
        weekly: 'Weekly Views',
        monthly: 'Monthly Views',
        yearly: 'Yearly Views'
      };

      viewsChart = new ApexCharts(container, {
        series: [{
          name: 'Views',
          data: dataset.data
        }],
        chart: {
          type: 'area',
          height: 400,
          toolbar: { show: false },
          fontFamily: 'Inter, sans-serif',
          animations: { enabled: true }
        },
        colors: ['#2d6cdf'],
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.4,
            opacityTo: 0.05,
            stops: [0, 100]
          }
        },
        stroke: {
          curve: 'smooth',
          width: 2
        },
        dataLabels: { enabled: false },
        xaxis: {
          categories: dataset.labels,
          labels: {
            style: { colors: '#64748b', fontSize: '12px' }
          },
          axisBorder: { show: false },
          axisTicks: { show: false }
        },
        yaxis: {
          labels: {
            style: { colors: '#64748b' },
            formatter: (val) => Math.floor(val)
          }
        },
        grid: {
          borderColor: '#f1f5f9',
          strokeDashArray: 4
        },
        markers: {
          size: 4,
          colors: ['#2d6cdf'],
          strokeColors: '#fff',
          strokeWidth: 2,
          hover: { size: 6 }
        },
        tooltip: {
          theme: 'light',
          y: {
            formatter: (val) => val + ' views'
          }
        },
        title: {
          text: titles[range],
          align: 'left',
          style: {
            fontSize: '16px',
            fontWeight: '600',
            color: '#1e293b'
          }
        }
      });

      viewsChart.render();
    }

    // Initial render
    renderViewsChart('daily');

    // Button toggle logic
    const activeClasses = ['bg-blue-600', 'text-white', 'border-blue-600'];
    const inactiveClasses = ['bg-gray-100', 'border-gray-300', 'hover:bg-gray-200'];

    document.querySelectorAll('.filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const range = btn.dataset.range;

        document.querySelectorAll('.filter-btn').forEach(b => {
          b.classList.remove(...activeClasses);
          b.classList.add(...inactiveClasses);
        });
        btn.classList.remove(...inactiveClasses);
        btn.classList.add(...activeClasses);

        renderViewsChart(range);
      });
    });
  </script>