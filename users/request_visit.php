<?php
  include '../config.php';
  if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
      echo "<script>location.href='../index.php';</script>";
      exit;
  }

  // Small escape helper
  function rv_e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

  // Status -> badge class (table + details dialog) and calendar color
  function rv_badge($status) {
      if ($status === 'Approved') return 'bg-emerald-500 text-white';
      if ($status === 'Pending')  return 'bg-amber-500 text-white';
      return 'bg-red-500 text-white';
  }
  function rv_color($status) {
      if ($status === 'Approved') return '#059669';
      if ($status === 'Pending')  return '#d97706';
      return '#dc2626';
  }

  // ONE query: requests I sent  +  requests other users sent to MY properties
  $get = $conn->prepare("
    SELECT rv.user_id AS requester_id, rv.status, rv.date_sched, rv.time_sched, rv.message,
           l.landlord_id, l.type, l.property_name, l.province, l.municipality, l.barangay,
           a.firstname, a.lastname
    FROM `request_visit` rv
    INNER JOIN `landlord` l ON l.landlord_id = rv.landlord_id
    LEFT JOIN `accounts` a ON a.user_id = rv.user_id
    WHERE rv.user_id = ? OR l.user_id = ?
    ORDER BY rv.date_sched DESC, rv.time_sched DESC
  ");
  $get->bind_param("ss", $user_id_login, $user_id_login);
  $get->execute();
  $result_get = $get->get_result();

  $visits = [];
  $events = [];
  while ($row = $result_get->fetch_assoc()) {
      $owner     = ((string)$row['requester_id'] === (string)$user_id_login) ? 'mine' : 'others';
      $fullname  = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? ''));
      $requester = $owner === 'mine' ? 'You' : ($fullname !== '' ? $fullname : 'Unknown user');
      $address   = trim($row['province'] . ' ' . $row['municipality'] . ' ' . $row['barangay']);
      $ts        = strtotime($row['date_sched'] . ' ' . $row['time_sched']);
      $schedule  = date('F j, Y', $ts) . ' · ' . date('g:i A', $ts);
      $message   = trim((string)$row['message']) !== '' ? $row['message'] : '—';
      $status    = $row['status'];

      $visits[] = [
          'owner'     => $owner,
          'type'      => $row['type'],
          'property'  => $row['property_name'],
          'address'   => $address,
          'requester' => $requester,
          'schedule'  => $schedule,
          'status'    => $status,
          'badge'     => rv_badge($status),
          'date_raw'  => date('Y-m-d', $ts),
          'landlord'  => $row['landlord_id'],
      ];

      // FullCalendar event (array index == row idx)
      $events[] = [
          'title'           => $owner === 'mine' ? $row['property_name'] : ($requester . ' · ' . $row['property_name']),
          'start'           => date('Y-m-d\TH:i:s', $ts),
          'allDay'          => false,
          'backgroundColor' => rv_color($status),
          'borderColor'     => rv_color($status),
          'extendedProps'   => [
              'idx'       => count($visits) - 1,
              'owner'     => $owner,
              'type'      => $row['type'],
              'property'  => $row['property_name'],
              'address'   => $address,
              'requester' => $requester,
              'schedule'  => $schedule,
              'status'    => $status,
              'badge'     => rv_badge($status),
              'message'   => $message,
              'link'      => 'property_requests_info.php?id=' . urlencode($row['landlord_id']) . '&location_back=request_visit.php',
          ],
      ];
  }
  $get->close();

  $eventsJson = json_encode($events, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
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
    <!-- FullCalendar (remove this line if your layout already loads it) -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

    <style>
      #visit_calendar .fc-toolbar { flex-wrap: wrap; gap: .5rem; }
      #visit_calendar .fc-toolbar-title { font-size: 1.15rem; font-weight: 700; }
      #visit_calendar .fc-button-primary { background: #0fab9e; border-color: #0fab9e; text-transform: capitalize; }
      #visit_calendar .fc-button-primary:hover { background: #0d9488; border-color: #0d9488; }
      #visit_calendar .fc-button-primary:not(:disabled).fc-button-active,
      #visit_calendar .fc-button-primary:not(:disabled):active { background: #0b7d73; border-color: #0b7d73; }
      #visit_calendar .fc-button-primary:focus { box-shadow: 0 0 0 3px rgba(15,171,158,.25); }
      #visit_calendar .fc-day-today { background: rgba(15,171,158,.08) !important; }
      #visit_calendar .fc-event { cursor: pointer; padding: 1px 3px; border-radius: 6px; }
    </style>
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

      <main class="p-6 lg:p-10 max-w-7xl w-full mx-auto">

        <!-- Header Banner -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 bg-base-200/60 p-6 rounded-2xl border border-base-300">
          <div>
            <h1 class="text-2xl font-extrabold text-base-content">Lists of Request</h1>
            <p class="text-sm text-base-content/70 mt-1">Review and manage visit requests.</p>
          </div>
          <div id="pr_result_count" class="text-sm font-medium text-base-content/70 bg-base-100 px-4 py-2 rounded-xl border border-base-300 shadow-sm"></div>
        </div>

        <!-- Toolbar: owner filter + search + date + view toggle + add -->
        <div class="flex flex-col lg:flex-row lg:items-end gap-3 mb-6">

          <!-- My / Other requests -->
          <div class="w-full lg:w-52">
            <label class="block text-[11px] font-semibold uppercase tracking-wide text-base-content/50 mb-1">Show</label>
            <select id="pr_owner" class="select select-bordered w-full rounded-xl bg-base-100 text-sm">
              <option value="mine" selected>My Requests</option>
              <option value="others">Other Requests</option>
            </select>
          </div>

          <!-- Search -->
          <div class="w-full lg:flex-1">
            <label class="block text-[11px] font-semibold uppercase tracking-wide text-base-content/50 mb-1">Search</label>
            <label class="input input-bordered flex items-center gap-3 w-full rounded-xl bg-base-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50 shrink-0"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/></svg>
              <input type="text" id="pr_search1" placeholder="Search by type, name, address or requester..." class="w-full focus:outline-none bg-transparent text-sm" />
            </label>
          </div>

          <!-- Date -->
          <div class="w-full lg:w-52">
            <label class="block text-[11px] font-semibold uppercase tracking-wide text-base-content/50 mb-1">Date</label>
            <label class="input input-bordered flex items-center gap-3 w-full rounded-xl bg-base-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50 shrink-0"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
              <input type="date" id="pr_date" class="w-full focus:outline-none bg-transparent text-sm" />
            </label>
          </div>

          <!-- View toggle -->
          <div class="join w-full lg:w-auto" id="view_toggle">
            <button type="button" data-view="table" class="join-item btn flex-1 lg:flex-none gap-2 border-none bg-[#0fab9e] text-white">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/></svg>
              Table
            </button>
            <button type="button" data-view="calendar" class="join-item btn btn-ghost flex-1 lg:flex-none gap-2 border border-base-300">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
              Calendar
            </button>
          </div>

          <button type="button" onclick="request_visit_add.showModal()" class="btn btn-success w-full lg:w-auto">Request Add</button>
        </div>

        <!-- ============================================ -->
        <!-- TABLE VIEW -->
        <!-- ============================================ -->
        <div id="table_view" class="bg-base-200/40 border border-base-300 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-left" id="pr_table">
              <thead>
                <tr class="bg-[#0fab9e] text-white uppercase text-xs tracking-wider">
                  <th class="py-4 px-6 font-semibold text-center">Property Type</th>
                  <th class="py-4 px-6 font-semibold text-center">Property Name</th>
                  <th class="py-4 px-6 font-semibold text-center">Address</th>
                  <th class="py-4 px-6 font-semibold text-center">Requested By</th>
                  <th class="py-4 px-6 font-semibold text-center">Schedule</th>
                  <th class="py-4 px-6 font-semibold text-center">Status</th>
                  <th class="py-4 px-6 font-semibold text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="pr_table_body1" class="divide-y divide-base-300">
                <?php foreach ($visits as $i => $v): ?>
                  <tr class="pr-row hover:bg-base-200/60 transition-colors"
                      data-idx="<?= $i ?>"
                      data-owner="<?= rv_e($v['owner']) ?>"
                      data-date="<?= rv_e($v['date_raw']) ?>">
                    <td class="py-4 px-6 text-center text-sm font-medium text-base-content"><?= rv_e($v['type']) ?></td>
                    <td class="py-4 px-6 text-center text-sm text-base-content/80"><?= rv_e($v['property']) ?></td>
                    <td class="py-4 px-6 text-center text-sm text-base-content/80 max-w-xs truncate" title="<?= rv_e($v['address']) ?>"><?= rv_e($v['address']) ?></td>
                    <td class="py-4 px-6 text-center text-sm text-base-content/80"><?= rv_e($v['requester']) ?></td>
                    <td class="py-4 px-6 text-center text-sm text-base-content/80 whitespace-nowrap"><?= rv_e($v['schedule']) ?></td>
                    <td class="py-4 px-6 text-center">
                      <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold shadow-sm <?= rv_e($v['badge']) ?>"><?= rv_e($v['status']) ?></span>
                    </td>
                    <td class="py-4 px-6 text-center">
                      <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="openVisitDetails(<?= $i ?>)" class="btn btn-xs sm:btn-sm btn-ghost border border-base-300 gap-1">
                          Details
                        </button>
                        <?php if ($v['owner'] === 'mine'): ?>
                          <a href="property_requests_info.php?id=<?= urlencode($v['landlord']) ?>&location_back=request_visit.php"
                             class="btn btn-xs sm:btn-sm bg-[#0fab9e] hover:bg-[#0d9488] text-white gap-1 border-none shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Comment &amp; Rate
                          </a>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

            <!-- Empty State -->
            <div class="hidden flex-col items-center justify-center py-16 px-5 text-center text-base-content/40" id="pr_empty_state">
              <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
              <p class="text-sm m-0" id="pr_empty_text">No property requests found.</p>
            </div>
          </div>

          <!-- Pagination -->
          <div class="flex items-center justify-between gap-3 flex-wrap px-6 py-4 border-t border-base-300 bg-base-200/20" id="pr_pagination1">
            <div class="text-sm text-base-content/70" id="pr_pagination_info"></div>
            <div class="flex items-center gap-1.5 flex-wrap" id="pr_pagination_controls"></div>
          </div>
        </div>

        <!-- ============================================ -->
        <!-- CALENDAR VIEW -->
        <!-- ============================================ -->
        <div id="calendar_view" class="hidden bg-base-100 border border-base-300 rounded-2xl shadow-sm p-4 sm:p-6">
          <div id="visit_calendar"></div>

          <!-- Legend -->
          <div class="flex items-center gap-5 flex-wrap mt-5 pt-4 border-t border-base-300 text-xs text-base-content/70">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#059669"></span>Approved</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#d97706"></span>Pending</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full" style="background:#dc2626"></span>Declined / Other</span>
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

  <!-- ============================================ -->
  <!-- DETAILS DIALOG (table button + calendar click) -->
  <!-- ============================================ -->
  <dialog id="visit_details" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-full max-w-lg p-0 overflow-hidden rounded-2xl shadow-2xl border border-slate-100">

      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-800">Visit Request Details</h3>
            <p class="text-xs text-slate-500" id="vd_owner_label"></p>
          </div>
        </div>
        <form method="dialog">
          <button class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-50 text-slate-400 hover:text-rose-500 flex items-center justify-center transition-colors" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </form>
      </div>

      <div class="p-6 space-y-4">
        <div class="flex items-start justify-between gap-3">
          <div class="min-w-0">
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Property</p>
            <p class="text-base font-bold text-slate-800 break-words" id="vd_property"></p>
            <p class="text-xs text-slate-500" id="vd_type"></p>
          </div>
          <span id="vd_status" class="inline-block px-3 py-1 rounded-full text-xs font-bold shadow-sm shrink-0"></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Schedule</p>
            <p class="text-sm text-slate-700" id="vd_schedule"></p>
          </div>
          <div>
            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Requested By</p>
            <p class="text-sm text-slate-700" id="vd_requester"></p>
          </div>
        </div>

        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Address</p>
          <p class="text-sm text-slate-700" id="vd_address"></p>
        </div>

        <div>
          <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400 mb-1">Message</p>
          <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 whitespace-pre-line break-words" id="vd_message"></div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
          <a id="vd_link" href="#" class="hidden px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition-all">Comment &amp; Rate</a>
          <form method="dialog">
            <button class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">Close</button>
          </form>
        </div>
      </div>
    </div>

    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

  <script src="./../assets/scripts/index.js"></script>

  <script>
  (function () {
    const PAGE_SIZE = 10;
    const events = <?= $eventsJson ?>;           // index == row data-idx

    const rows        = Array.from(document.querySelectorAll('#pr_table_body1 .pr-row'));
    const ownerSel    = document.getElementById('pr_owner');
    const searchInput = document.getElementById('pr_search1');
    const dateInput   = document.getElementById('pr_date');
    const emptyState  = document.getElementById('pr_empty_state');
    const emptyText   = document.getElementById('pr_empty_text');
    const countEl     = document.getElementById('pr_result_count');
    const infoEl      = document.getElementById('pr_pagination_info');
    const controlsEl  = document.getElementById('pr_pagination_controls');
    const table       = document.getElementById('pr_table');
    const paginationW = document.getElementById('pr_pagination1');
    const tableView   = document.getElementById('table_view');
    const calView     = document.getElementById('calendar_view');
    const toggleBtns  = Array.from(document.querySelectorAll('#view_toggle button'));

    let baseRows = [];     // owner + search
    let filtered = [];     // owner + search + date  (table)
    let page = 1;
    let view = 'table';
    let calendar = null;

    // ---------- FILTERING ----------
    function matchesBase(row) {
      if (row.dataset.owner !== ownerSel.value) return false;
      const q = searchInput.value.trim().toLowerCase();
      if (!q) return true;
      const text = Array.from(row.cells).slice(0, 4).map(c => c.textContent).join(' ').toLowerCase();
      return text.includes(q);
    }

    function applyFilters() {
      baseRows = rows.filter(matchesBase);
      const d = dateInput.value;
      filtered = d ? baseRows.filter(r => r.dataset.date === d) : baseRows;
      page = 1;
      render();
      if (calendar) calendar.refetchEvents();
    }

    // ---------- TABLE RENDER ----------
    function render() {
      const total = filtered.length;
      const pages = Math.max(1, Math.ceil(total / PAGE_SIZE));
      page = Math.min(page, pages);
      const start = (page - 1) * PAGE_SIZE;
      const visible = new Set(filtered.slice(start, start + PAGE_SIZE));

      rows.forEach(r => r.style.display = visible.has(r) ? '' : 'none');

      updateCount();
      infoEl.textContent = total
        ? 'Showing ' + (start + 1) + '–' + Math.min(start + PAGE_SIZE, total) + ' of ' + total
        : '';

      const empty = total === 0;
      emptyState.classList.toggle('hidden', !empty);
      emptyState.classList.toggle('flex', empty);
      table.classList.toggle('hidden', empty);
      paginationW.classList.toggle('hidden', empty);
      emptyText.textContent = ownerSel.value === 'mine'
        ? 'You have no visit requests yet.'
        : 'No one has requested a visit to your properties yet.';

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

    function updateCount() {
      const n = view === 'calendar' ? baseRows.length : filtered.length;
      countEl.textContent = n + (n === 1 ? ' request' : ' requests');
    }

    // ---------- CALENDAR ----------
    function initCalendar() {
      calendar = new FullCalendar.Calendar(document.getElementById('visit_calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: { month: 'Month', week: 'Week', list: 'List' },
        height: 'auto',
        navLinks: true,
        dayMaxEvents: 3,
        events: function (info, success) {
          const ids = new Set(baseRows.map(r => Number(r.dataset.idx)));
          success(events.filter((e, i) => ids.has(i)));
        },
        eventClick: function (info) {
          info.jsEvent.preventDefault();
          openVisitDetails(info.event.extendedProps.idx);
        },
        eventDidMount: function (info) {
          info.el.setAttribute('title', info.event.title + ' | ' + info.event.extendedProps.status);
        }
      });
      calendar.render();
      if (dateInput.value) calendar.gotoDate(dateInput.value);
    }

    // ---------- VIEW TOGGLE ----------
    function setView(v) {
      view = v;
      tableView.classList.toggle('hidden', v !== 'table');
      calView.classList.toggle('hidden', v !== 'calendar');

      toggleBtns.forEach(b => {
        const on = b.dataset.view === v;
        b.className = 'join-item btn flex-1 lg:flex-none gap-2 ' +
          (on ? 'border-none bg-[#0fab9e] text-white' : 'btn-ghost border border-base-300');
      });

      if (v === 'calendar') {
        if (!calendar) initCalendar(); else { calendar.updateSize(); calendar.refetchEvents(); }
      }
      updateCount();
    }

    toggleBtns.forEach(b => b.addEventListener('click', () => setView(b.dataset.view)));

    // ---------- DETAILS DIALOG ----------
    window.openVisitDetails = function (idx) {
      const p = events[idx].extendedProps;
      const set = (id, val) => document.getElementById(id).textContent = val;

      set('vd_property',  p.property);
      set('vd_type',      p.type);
      set('vd_schedule',  p.schedule);
      set('vd_requester', p.requester);
      set('vd_address',   p.address || '—');
      set('vd_message',   p.message);
      set('vd_owner_label', p.owner === 'mine' ? 'Request you sent' : 'Request sent to your property');

      const st = document.getElementById('vd_status');
      st.textContent = p.status;
      st.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold shadow-sm shrink-0 ' + p.badge;

      const link = document.getElementById('vd_link');
      if (p.owner === 'mine') { link.href = p.link; link.classList.remove('hidden'); }
      else { link.classList.add('hidden'); }

      document.getElementById('visit_details').showModal();
    };

    // ---------- EVENTS ----------
    ownerSel.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
    dateInput.addEventListener('change', function () {
      applyFilters();
      if (calendar && dateInput.value) calendar.gotoDate(dateInput.value);
    });

    applyFilters();
  })();
  </script>
</body>
</html>