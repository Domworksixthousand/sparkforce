<?php
  include '../config.php';
  if(!isset($_SESSION['admin_login'])){
    echo "<script>location.href='../index.php';</script>";
    exit;
  }
    $not_user1 = "4";
    $not_user2 = "1";
  // ---- Fetch pending requests once, build rows + stats ----
  $user_status = "Disapproved";
  $request = $conn->prepare("SELECT * FROM `accounts` WHERE `status` = ? AND (`user_type` != ? AND `user_type` != ? ) ORDER BY date_request ASC");
  $request->bind_param("sss", $user_status,$not_user1, $not_user2);
  $request->execute();
  $result_request = $request->get_result();
 
  $rows = [];
  $count_tenant = 0;
  $count_landlord = 0;
  while($r = $result_request->fetch_assoc()){
    $rows[] = $r;
    if($r['user_type'] === '2') $count_landlord++;
    if($r['user_type'] === '3') $count_tenant++;
  }
  $count_total = count($rows);
 
  // "2 days ago" style helper
  function time_ago($datetime){
    if(!$datetime) return 'N/A';
    $ts = strtotime($datetime);
    if(!$ts) return 'N/A';
    $diff = time() - $ts;
    if($diff < 60) return 'Just now';
    if($diff < 3600) return floor($diff/60).' min ago';
    if($diff < 86400) return floor($diff/3600).' hr ago';
    if($diff < 2592000) return floor($diff/86400).' day'.(floor($diff/86400) > 1 ? 's' : '').' ago';
    return date('M d, Y', $ts);
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>Disapproved Accounts</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-200">

  <!---alert-->
  <?php 
    include '../alerts.php'; 
  ?>

    <div class="drawer lg:drawer-open">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <nav class="navbar w-full bg-[#0fab9e] px-4 shadow-md">
                <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </label>
                <div class="flex-1 font-semibold text-lg text-white">Disapproved Accounts</div>
            </nav>
            <div class="p-4 md:p-6">
                 <main class="max-w-7xl mx-auto space-y-6">

                    <!-- Page heading -->
                    <header class="fade-up">
                        <h1 class="text-2xl font-bold text-base-content">Disapproved Accounts</h1>
                        <p class="text-sm text-base-content/60 mt-1">Manage and review disapproved tenant and landlord accounts.</p>
                    </header>

                    <!-- Stats -->
                    <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 fade-up">
                        <button type="button" data-filter="all" class="stat-card text-left bg-base-100 rounded-xl border border-base-300 p-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-[#0d9488]/10 text-[#0d9488] grid place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold" id="stat-total"><?php echo $count_total; ?></div>
                                <div class="text-sm text-base-content/60">Total Disapproved</div>
                            </div>
                        </button>
                        <button type="button" data-filter="3" class="stat-card text-left bg-base-100 rounded-xl border border-base-300 p-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary grid place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold"><?php echo $count_tenant; ?></div>
                                <div class="text-sm text-base-content/60">Tenants</div>
                            </div>
                        </button>
                        <button type="button" data-filter="2" class="stat-card text-left bg-base-100 rounded-xl border border-base-300 p-5 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-info/10 text-info grid place-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/></svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold"><?php echo $count_landlord; ?></div>
                                <div class="text-sm text-base-content/60">Landlords</div>
                            </div>
                        </button>
                    </section>

                    <!-- Table card -->
                    <section class="bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden fade-up">

                        <!-- Toolbar -->
                        <div class="p-4 md:p-5 border-b border-base-300 space-y-4">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                <div class="flex flex-wrap gap-2" id="type-chips">
                                    <button type="button" data-filter="all" class="chip active btn btn-sm btn-outline rounded-full">All <span class="badge badge-sm ml-1"><?php echo $count_total; ?></span></button>
                                    <button type="button" data-filter="3" class="chip btn btn-sm btn-outline rounded-full">Tenants <span class="badge badge-sm ml-1"><?php echo $count_tenant; ?></span></button>
                                    <button type="button" data-filter="2" class="chip btn btn-sm btn-outline rounded-full">Landlords <span class="badge badge-sm ml-1"><?php echo $count_landlord; ?></span></button>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                    <label class="input input-bordered flex items-center gap-2 w-full sm:w-80">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-50"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
                                        <input type="text" class="search_data grow" placeholder="Search name, email, address..." />
                                        <kbd class="kbd kbd-sm hidden md:inline">/</kbd>
                                    </label>
                                    <select id="entries_limit" class="select select-bordered w-full sm:w-fit">
                                        <option value="8" selected>8 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="25">25 per page</option>
                                        <option value="50">50 per page</option>
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="table" id="requestsTable">
                                <thead>
                                    <tr class="bg-[#0d9488]">
                                        <th class="sortable font-semibold text-white" data-col="0">User </th>
                                        <th class="sortable font-semibold text-white hidden md:table-cell" data-col="1">Email </th>
                                        <th class="font-semibold text-white hidden lg:table-cell">Contact Number</th>
                                        <th class="sortable font-semibold text-white" data-col="3">Account Type </th>
                                        <th class="sortable font-semibold text-white hidden sm:table-cell" data-col="4">Requested </th>
                                        <th class="font-semibold text-white ">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="myTable divide-y divide-base-200">
                                    <?php foreach($rows as $row):
                                        $middlename = htmlspecialchars($row['middlename'] ?? '');
                                        $lastname   = htmlspecialchars($row['lastname'] ?? '');
                                        $firstname  = htmlspecialchars($row['firstname'] ?? '');
                                        $suffix     = htmlspecialchars($row['suffix'] ?? '');
                                        $fullname   = trim($lastname . ' ' . $firstname . ' ' . $middlename . ' ' . $suffix);
                                        $email      = htmlspecialchars($row['email'] ?? '');
                                        $contact    = htmlspecialchars($row['contact_number'] ?? '');
                                        $province   = htmlspecialchars($row['province'] ?? '');
                                        $municipality = htmlspecialchars($row['municipality'] ?? '');
                                        $barangay   = htmlspecialchars($row['barangay'] ?? '');
                                        $address    = trim($barangay . ', ' . $municipality . ', ' . $province, ', ');
                                        $id_photo   = htmlspecialchars($row['id_photo'] ?? '');
                                        $user_id    = htmlspecialchars($row['user_id'] ?? '');
                                        $user_type  = $row['user_type'];
                                        $type_label = ($user_type === '2') ? 'Landlord' : (($user_type === '3') ? 'Tenant' : 'N/A');
                                        $type_badge = ($user_type === '3') ? 'badge-primary' : 'badge-info';
                                        $requested_raw = $row['date_request'] ?? '';
                                        $requested_ts  = $requested_raw ? strtotime($requested_raw) : 0;
                                        $requested_ago = time_ago($requested_raw);
                                        $requested_full = $requested_ts ? date('M d, Y h:i A', $requested_ts) : '';
                                        $initial = strtoupper(mb_substr($firstname ?: $lastname ?: '?', 0, 1));
                                    ?>
                                    <tr class="data-row hover:bg-base-200/40 transition-colors" data-type="<?php echo htmlspecialchars($user_type); ?>">
                                        <td data-sort="<?php echo strtolower($fullname); ?>">
                                            <div class="flex items-center gap-3">
                                                <button type="button" class="avatar-btn avatar" data-photo="../assets/uploads/<?php echo $id_photo; ?>" data-name="<?php echo $fullname; ?>" title="Preview ID photo">
                                                    <div class="w-11 h-11 rounded-full ring ring-[#0d9488]/20 ring-offset-base-100 ring-offset-2 bg-[#0d9488]/10 text-[#0d9488] grid place-items-center font-semibold">
                                                        <img src="../assets/uploads/<?php echo $id_photo; ?>" alt="<?php echo $fullname; ?>" class="object-cover w-full h-full" onerror="this.remove(); this.parentNode.textContent='<?php echo $initial; ?>';" />
                                                    </div>
                                                </button>
                                                <div class="min-w-0">
                                                    <div class="font-medium text-base-content truncate"><?php echo $fullname; ?></div>
                                                    <div class="text-xs text-base-content/60 truncate max-w-[220px]"><?php echo $address; ?></div>
                                                    <div class="text-xs text-base-content/60 md:hidden truncate"><?php echo $email; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell text-base-content/80" data-sort="<?php echo strtolower($email); ?>">
                                            <div class="flex items-center gap-2">
                                                <span class="truncate max-w-[220px]"><?php echo $email; ?></span>
                                                <button type="button" class="copy-btn btn btn-ghost btn-xs btn-square" data-copy="<?php echo $email; ?>" title="Copy email">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="hidden lg:table-cell text-base-content/80"><?php echo $contact; ?></td>
                                        <td data-sort="<?php echo $type_label; ?>">
                                            <span class="badge <?php echo $type_badge; ?> badge-sm"><?php echo $type_label; ?></span>
                                        </td>
                                        <td class="hidden sm:table-cell text-sm text-base-content/70 whitespace-nowrap" data-sort="<?php echo $requested_ts; ?>" title="<?php echo $requested_full; ?>">
                                            <?php echo $requested_ago; ?>
                                        </td>
                                        <td >
                                            <a href="request_account_info.php?id=<?php echo $user_id; ?>&location_back=disapproved_accounts.php"
                                               class="row-action btn btn-sm bg-[#0d9488] hover:bg-[#0f766e] text-white border-none gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                    <?php if($count_total === 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-16">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-14 h-14 rounded-full bg-success/10 text-success grid place-items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                </div>
                                                <p class="font-semibold text-base-content">You're all caught up</p>
                                                <p class="text-sm text-base-content/60">There are no pending account requests.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                   
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 border-t border-base-300 text-sm text-base-content/60 flex items-center justify-between">
                            <span>Showing <strong id="visible-count"><?php echo $count_total; ?></strong> of <?php echo $count_total; ?> pending requests</span>
                            <span class="hidden sm:inline">Oldest requests are listed first</span>
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


      <!-- ID photo preview modal -->
    <dialog id="photoModal" class="modal">
        <div class="modal-box max-w-lg p-0 overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-base-300">
                <h3 class="font-semibold" id="photoModalName">ID Photo</h3>
                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost">✕</button></form>
            </div>
            <div class="bg-base-200 p-4 grid place-items-center">
                <img id="photoModalImg" src="" alt="ID photo" class="max-h-[60vh] rounded-lg object-contain"
                     onerror="this.alt='No ID photo available'; this.style.display='none';" onload="this.style.display='block';" />
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Toast -->
    <div class="toast toast-end z-50 hidden" id="toast">
        <div class="alert bg-[#0d9488] text-white text-sm py-2"><span id="toastMsg">Copied</span></div>
    </div>

    <script src="./../assets/scripts/index.js"></script>
    <script src="./../assets/scripts/query_filter.js"></script>
    <script>
    (function(){
        const rows = Array.from(document.querySelectorAll('#requestsTable .data-row'));
        const tbody = document.querySelector('#requestsTable tbody.myTable');
        const noResults = document.getElementById('no-results');
        const visibleCount = document.getElementById('visible-count');
        const searchInput = document.querySelector('.search_data');
        let currentFilter = 'all';

        // ---- Visible count + empty state (works alongside query_filter.js) ----
        function refreshState(){
            if(!rows.length) return;
            const shown = rows.filter(r => r.style.display !== 'none' && !r.classList.contains('type-hidden')).length;
            visibleCount.textContent = shown;
            noResults.classList.toggle('hidden', shown !== 0);
        }

        // ---- Type filter (chips + stat cards) ----
        function setFilter(type){
            currentFilter = type;
            rows.forEach(r => r.classList.toggle('type-hidden', type !== 'all' && r.dataset.type !== type));
            document.querySelectorAll('#type-chips .chip').forEach(c => c.classList.toggle('active', c.dataset.filter === type));
            refreshState();
        }
        document.querySelectorAll('[data-filter]').forEach(el => el.addEventListener('click', () => setFilter(el.dataset.filter)));

        // Re-check state after search / page-size changes
        ['input','keyup','change'].forEach(evt => {
            searchInput && searchInput.addEventListener(evt, () => setTimeout(refreshState, 50));
            document.getElementById('entries_limit').addEventListener(evt, () => setTimeout(refreshState, 50));
        });
        new MutationObserver(refreshState).observe(tbody, {attributes:true, subtree:true, attributeFilter:['style']});

        // ---- Sorting ----
        let sortCol = null, sortDir = 1;
        document.querySelectorAll('th.sortable').forEach(th => {
            th.addEventListener('click', () => {
                const col = parseInt(th.dataset.col, 10);
                sortDir = (sortCol === col) ? -sortDir : 1;
                sortCol = col;
                document.querySelectorAll('th.sortable').forEach(t => t.classList.remove('sorted'));
                th.classList.add('sorted');
                th.querySelector('.sort-arrow').textContent = sortDir === 1 ? '▲' : '▼';
                const sorted = rows.slice().sort((a, b) => {
                    const av = a.children[col].dataset.sort ?? a.children[col].textContent.trim();
                    const bv = b.children[col].dataset.sort ?? b.children[col].textContent.trim();
                    const an = parseFloat(av), bn = parseFloat(bv);
                    if(!isNaN(an) && !isNaN(bn)) return (an - bn) * sortDir;
                    return av.localeCompare(bv) * sortDir;
                });
                sorted.forEach(r => tbody.insertBefore(r, noResults));
            });
        });

        // ---- ID photo preview ----
        const modal = document.getElementById('photoModal');
        document.querySelectorAll('.avatar-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('photoModalImg').src = btn.dataset.photo;
                document.getElementById('photoModalName').textContent = btn.dataset.name || 'ID Photo';
                modal.showModal();
            });
        });

        // ---- Copy email ----
        const toast = document.getElementById('toast');
        function showToast(msg){
            document.getElementById('toastMsg').textContent = msg;
            toast.classList.remove('hidden');
            clearTimeout(showToast.t);
            showToast.t = setTimeout(() => toast.classList.add('hidden'), 1800);
        }
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                try { await navigator.clipboard.writeText(btn.dataset.copy); showToast('Email copied'); }
                catch(e){ showToast('Could not copy'); }
            });
        });

        // ---- "/" focuses search ----
        document.addEventListener('keydown', e => {
            if(e.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)){
                e.preventDefault(); searchInput && searchInput.focus();
            }
        });

        refreshState();
    })();
    </script>

    <script src="../assets/scripts/index.js"></script>
    <script src="../assets/scripts/query_filter.js"></script>
</body>
</html>
