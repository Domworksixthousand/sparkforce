<?php
  include '../config.php'; 
   if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
      echo "<script>location.href='../index.php';</script>";
      exit; 
  }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>Request Visit</title>
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
  include 'request_visit_add.php';
?>

   <div class="drawer lg:drawer-open">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    
    <div class="drawer-content flex flex-col bg-base-100">
      <!-- Navbar -->
      <nav class="navbar w-full bg-[#0fab9e] text-white shadow-md px-6 min-h-[4rem]">
        <div class="flex items-center gap-3">
          <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden hover:bg-[#0d9488]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-6"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
          </label>
          <div class="text-xl font-bold tracking-wide">Request Visits</div>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main class="p-6 lg:p-10 max-w-7xl w-full mx-auto">
        
        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-base-200/60 p-6 rounded-2xl border border-base-300">
          <div>
            <h1 class="text-2xl font-extrabold text-base-content">Lists of Request</h1>
            <p class="text-sm text-base-content/70 mt-1">Review and manage visit requests.</p>
          </div>
          <div id="pr_result_count" class="text-sm font-medium text-base-content/70 bg-base-100 px-4 py-2 rounded-xl border border-base-300 shadow-sm"></div>
        </div>

        <!-- Filter and Search Toolbar -->
        <div class="flex items-end justify-end flex-col md:flex-row  gap-4 mb-6">
          <label class="input input-bordered flex items-center gap-3 w-full md:w-96 rounded-xl bg-base-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50 shrink-0"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/></svg>
            <input type="text" id="pr_search1" placeholder="Search by type, name, or address..." class="w-full focus:outline-none bg-transparent text-sm" />
          </label>
          <label class="input input-bordered flex items-center gap-3 w-full md:w-96 rounded-xl bg-base-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50 shrink-0"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/></svg>
            <input type="date" id="pr_date" class="w-full focus:outline-none bg-transparent text-sm" />
          </label>
          <button type="button" onclick="request_visit_add.showModal()" class="btn btn-success">Request Add</button>
        </div>

        <!-- Data Table Card -->
        <div class="bg-base-200/40 border border-base-300 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-left" id="pr_table">
  <thead>
    <tr class="bg-[#0fab9e] text-white uppercase text-xs tracking-wider">
      <th class="py-4 px-6 font-semibold text-center">Property Type</th>
      <th class="py-4 px-6 font-semibold text-center">Property Name</th>
      <th class="py-4 px-6 font-semibold text-center">Address</th>
      <th class="py-4 px-6 font-semibold text-center">Schedule</th>
      <th class="py-4 px-6 font-semibold text-center">Message</th>
      <th class="py-4 px-6 font-semibold text-center">Status</th>
      <th class="py-4 px-6 font-semibold text-center">Actions</th>
    </tr>
  </thead>
  <tbody id="pr_table_body1" class="divide-y divide-base-300">
    <?php
      $get = $conn->prepare("
        SELECT rv.status, rv.date_sched, rv.time_sched, rv.message,
               l.landlord_id, l.type, l.property_name, l.province, l.municipality, l.barangay
        FROM `request_visit` rv
        INNER JOIN `landlord` l ON l.landlord_id = rv.landlord_id
        WHERE rv.user_id = ?
        ORDER BY rv.date_sched DESC, rv.time_sched DESC
      ");
      $get->bind_param("s", $user_id_login);
      $get->execute();
      $result_get = $get->get_result();

      while ($row = $result_get->fetch_assoc()) {
        $address = trim($row['province'] . ' ' . $row['municipality'] . ' ' . $row['barangay']);
        $schedule = date('F j, Y', strtotime($row['date_sched'])) . ' · ' . date('g:i A', strtotime($row['time_sched']));
        $message = trim($row['message']) !== '' ? $row['message'] : '—';
        $status = $row['status'];
        $date_raw = date('Y-m-d', strtotime($row['date_sched']));

        if ($status === "Approved") {
          $badge_class = "bg-emerald-500 text-white";
        } elseif ($status === "Pending") {
          $badge_class = "bg-amber-500 text-white";
        } else {
          $badge_class = "bg-red-500 text-white";
        }

        echo '
          <tr class="pr-row hover:bg-base-200/60 transition-colors" data-date="' . htmlspecialchars($date_raw) . '">
            <td class="py-4 px-6 text-center text-sm font-medium text-base-content">' . htmlspecialchars($row['type']) . '</td>
            <td class="py-4 px-6 text-center text-sm text-base-content/80">' . htmlspecialchars($row['property_name']) . '</td>
            <td class="py-4 px-6 text-center text-sm text-base-content/80 max-w-xs truncate" title="' . htmlspecialchars($address) . '">' . htmlspecialchars($address) . '</td>
            <td class="py-4 px-6 text-center text-sm text-base-content/80 whitespace-nowrap">' . htmlspecialchars($schedule) . '</td>
            <td class="py-4 px-6 text-center text-sm text-base-content/80 max-w-xs truncate" title="' . htmlspecialchars($message) . '">' . htmlspecialchars($message) . '</td>
            <td class="py-4 px-6 text-center">
              <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold shadow-sm ' . $badge_class . '">' . htmlspecialchars($status) . '</span>
            </td>
            <td class="py-4 px-6 text-center">
              <a href="property_requests_info.php?id=' . urlencode($row['landlord_id']) . '&location_back=request_visit.php" class="btn btn-xs sm:btn-sm bg-[#0fab9e] hover:bg-[#0d9488] text-white gap-1 border-none shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                More Info
              </a>
            </td>
          </tr>';
      }
      $get->close();
    ?>
  </tbody>
</table>

            <!-- Empty State -->
            <div class="hidden flex-col items-center justify-center py-16 px-5 text-center text-base-content/40" id="pr_empty_state">
                <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                <p class="text-sm m-0">No property requests found.</p>
            </div>
          </div>

          <!-- Pagination Controls -->
          <div class="flex items-center justify-between gap-3 flex-wrap px-6 py-4 border-t border-base-300 bg-base-200/20" id="pr_pagination1">
              <div class="text-sm text-base-content/70" id="pr_pagination_info"></div>
              <div class="flex items-center gap-1.5 flex-wrap" id="pr_pagination_controls"></div>
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

  <!-- Search + date filter + pagination (replaces query_filter.js on this page) -->
  <script>
  (function () {
    const PAGE_SIZE = 10;
    const rows        = Array.from(document.querySelectorAll('#pr_table_body1 .pr-row'));
    const searchInput = document.getElementById('pr_search1');
    const dateInput   = document.getElementById('pr_date');
    const emptyState  = document.getElementById('pr_empty_state');
    const countEl     = document.getElementById('pr_result_count');
    const infoEl      = document.getElementById('pr_pagination_info');
    const controlsEl  = document.getElementById('pr_pagination_controls');
    const table       = document.getElementById('pr_table');

    let filtered = rows;
    let page = 1;

    function applyFilters() {
      const q = searchInput.value.trim().toLowerCase();
      const d = dateInput.value; // YYYY-MM-DD or ""

      filtered = rows.filter(row => {
        // Search only type, name, address (first 3 columns)
        const text = Array.from(row.cells).slice(0, 3)
          .map(c => c.textContent).join(' ').toLowerCase();
        const matchText = !q || text.includes(q);
        const matchDate = !d || row.dataset.date === d;
        return matchText && matchDate;
      });

      page = 1;
      render();
    }

    function render() {
      const total = filtered.length;
      const pages = Math.max(1, Math.ceil(total / PAGE_SIZE));
      page = Math.min(page, pages);
      const start = (page - 1) * PAGE_SIZE;
      const visible = new Set(filtered.slice(start, start + PAGE_SIZE));

      rows.forEach(r => r.style.display = visible.has(r) ? '' : 'none');

      countEl.textContent = total + (total === 1 ? ' request' : ' requests');
      infoEl.textContent = total
        ? 'Showing ' + (start + 1) + '–' + Math.min(start + PAGE_SIZE, total) + ' of ' + total
        : '';

      // Empty state
      const empty = total === 0;
      emptyState.classList.toggle('hidden', !empty);
      emptyState.classList.toggle('flex', empty);
      table.classList.toggle('hidden', empty);

      // Pagination buttons
      controlsEl.innerHTML = '';
      if (pages <= 1) return;
      const mk = (label, p, disabled, active) => {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = label;
        b.className = 'btn btn-sm ' + (active ? 'bg-[#0fab9e] text-white border-none' : 'btn-ghost');
        b.disabled = disabled;
        b.onclick = () => { page = p; render(); };
        controlsEl.appendChild(b);
      };
      mk('Prev', page - 1, page === 1);
      for (let i = 1; i <= pages; i++) mk(i, i, false, i === page);
      mk('Next', page + 1, page === pages);
    }

    searchInput.addEventListener('input', applyFilters);
    dateInput.addEventListener('change', applyFilters);
    applyFilters();
  })();
  </script>
</body>
</html>