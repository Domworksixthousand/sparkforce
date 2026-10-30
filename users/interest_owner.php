<?php
include '../config.php';
if (!isset($_SESSION['user_login']) || $statusko === "Deactivate") {
    echo "<script>location.href='../index.php';</script>";
    exit;
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Approve / Decline handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['int_action'])) {
    $token_ok = isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    $action   = $_POST['int_action'];
    $int_id   = $_POST['int_id'] ?? '';
    if ($token_ok && in_array($action, ['Approved', 'Decline', 'Completed'], true) && $int_id !== '') {
        // Pending -> Approved/Decline, Approved -> Completed
        $required_status = ($action === 'Completed') ? 'Approved' : 'Pending';
        // Ang may-ari lang ng property ang puwedeng mag-update
        $u = $conn->prepare("
            UPDATE interested AS i
            INNER JOIN landlord AS l ON l.landlord_id = i.landlord_id
            SET i.status = ?
            WHERE i.int_id = ? AND l.user_id = ? AND i.status = ?
        ");
        $u->bind_param("ssss", $action, $int_id, $user_id_login, $required_status);
        $u->execute();
        if ($u->affected_rows > 0) {

            // Kunin ang applicant (receiver) mula sa database, hindi galing sa browser
            $g = $conn->prepare("SELECT user_id FROM interested WHERE int_id = ?");
            $g->bind_param("s", $int_id);
            $g->execute();
            $receiver = $g->get_result()->fetch_assoc()['user_id'] ?? '';

            if ($receiver !== '') {
                if ($action === 'Approved') {
                    $text_noti = $fullnameko . " approved your request. You can now message them to arrange a visit or view details.";
                    $noti_link = "interested.php"; // TODO: palitan ng page na dapat buksan ng applicant
                } elseif ($action === 'Completed') {
                    $text_noti = $fullnameko . " marked your request as completed. You can now rate and comment.";
                    $noti_link = "comment_rate.php";
                } else {
                    $text_noti = $fullnameko . " declined your request.";
                    $noti_link = "interested.php"; // TODO: palitan ng page na dapat buksan ng applicant
                }
                $noti_status = "unseen";

                $insert_notifications = $conn->prepare("
                    INSERT INTO notifications (`text_noti`,`status`,`date_sent`,`time_sent`,`sender`,`receiver`,`link`)
                    VALUES (?,?,?,?,?,?,?)
                ");
                $insert_notifications->bind_param(
                    "sssssss",
                    $text_noti, $noti_status, $datetoday, $timetoday1, $fullnameko, $receiver, $noti_link
                );
                $insert_notifications->execute();
            }

            $success_msgs = [
                'Approved'  => 'The request has been approved.',
                'Decline'   => 'The request has been declined.',
                'Completed' => 'The request has been marked as completed.',
            ];
            $_SESSION['success'] = $success_msgs[$action];
        } else {
            $_SESSION['error'] = 'Unable to update this request.';
        }
    } else {
        $_SESSION['error'] = 'Invalid request.';
    }
    echo "<script>location.href='interest_owner.php';</script>";
    exit;
}

/**
 * Pages per type. Palitan ang file names ayon sa actual pages mo.
 * 'default' ang gagamitin kapag walang match na type (Apartment, etc.)
 */
$type_pages = [
    'Boarding House / Bedspace' => ['page' => 'my_bh_info.php',         'text' => 'View Boarding House'],
    'Commercial Space'          => ['page' => 'my_commercial_info.php', 'text' => 'View Commercial Space'],
    'default'                   => ['page' => 'my_apartment_info.php',  'text' => 'View Apartment'],
];

/**
 * Gumawa ng link at button text base sa type.
 * Return: [link, link_text]
 * Ang link ay may `id` (rent_id) at `property_id` (landlord_id) dahil pareho itong required ng target page.
 */
function getTypeLink($type_pages, $type, $id, $landlord_id) {
    $cfg = $type_pages[$type] ?? $type_pages['default'];
    return [$cfg['page'] . '?id=' . urlencode($id) . '&property_id=' . urlencode($landlord_id), $cfg['text']];
}

/**
 * Kumuha ng detail ng isang interested item depende sa type.
 * Magdagdag ng bagong `case` para sa bawat type/table.
 * Return: array na may title, image, status, fields, link, link_text (o null kung walang nahanap)
 */
function getInterestDetail($conn, $type_pages, $type, $data_id, $property_name, $landlord_id) {
    switch ($type) {

        case 'Boarding House / Bedspace':
            $q = $conn->prepare("SELECT boarding_id, bed_number, status, num_decks, image, rent_id
                                 FROM boarding_house WHERE boarding_id = ?");
            $q->bind_param("s", $data_id);
            $q->execute();
            $b = $q->get_result()->fetch_assoc();
            if (!$b) return null;
            // Kung boarding_id ang hinahanap ng my_bh_info.php, palitan ng $b['boarding_id']
            [$link, $link_text] = getTypeLink($type_pages, $type, $b['rent_id'], $landlord_id);
            return [
                'title'     => 'Bed #' . $b['bed_number'],
                'image'     => '../assets/uploads/' . $b['image'],
                'status'    => $b['status'],
                'fields'    => [
                    'Decks'    => $b['num_decks'],
                    'Property' => $property_name,
                ],
                'link'      => $link,
                'link_text' => $link_text,
            ];

        case 'Commercial Space':
            // TODO: palitan ng actual table/columns ng commercial mo
            $q = $conn->prepare("SELECT rent_id, name, price, image_cover, other_info
                                 FROM rentspace WHERE rent_id = ?");
            $q->bind_param("s", $data_id);
            $q->execute();
            $r = $q->get_result()->fetch_assoc();
            if (!$r) return null;
            [$link, $link_text] = getTypeLink($type_pages, $type, $r['rent_id'], $landlord_id);
            return [
                'title'     => $r['name'],
                'image'     => '../assets/uploads/' . $r['image_cover'],
                'status'    => '',
                'fields'    => [
                    'Price'    => '₱' . number_format((float)$r['price'], 2) . ' / month',
                    'Property' => $property_name,
                ],
                'link'      => $link,
                'link_text' => $link_text,
            ];

        default: // Apartment, etc. (rentspace muna)
            $q = $conn->prepare("SELECT rent_id, name, price, image_cover
                                 FROM rentspace WHERE rent_id = ?");
            $q->bind_param("s", $data_id);
            $q->execute();
            $r = $q->get_result()->fetch_assoc();
            if (!$r) return null;
            [$link, $link_text] = getTypeLink($type_pages, $type, $r['rent_id'], $landlord_id);
            return [
                'title'     => $r['name'],
                'image'     => '../assets/uploads/' . $r['image_cover'],
                'status'    => '',
                'fields'    => [
                    'Price'    => '₱' . number_format((float)$r['price'], 2) . ' / month',
                    'Property' => $property_name,
                ],
                'link'      => $link,
                'link_text' => $link_text,
            ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>Interested</title>
    <link rel="shortcut icon" href="../assets/images/logo-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="../assets/styles/index.css">
    <script src="../assets/scripts/tailwind.js"></script>
    <script src="../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100 min-h-screen">

  <?php
    include '../alerts.php';
    include '../banned_modal.php';
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
          <div class="text-xl font-bold tracking-wide">Interested</div>
        </div>
      </nav>

      <main class="p-6 lg:p-10 max-w-7xl w-full mx-auto">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 bg-base-200/60 p-6 rounded-2xl border border-base-300">
          <div>
            <h1 class="text-2xl font-extrabold text-base-content">Interested</h1>
            <p class="text-sm text-base-content/70 mt-1">Manage and track property that you're interested in.</p>
          </div>
        </div>

        <!-- Filter and Search Controls -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
          <label class="input input-bordered flex items-center gap-3 w-full md:w-96 rounded-xl bg-base-100">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/50"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.34-4.34"/></svg>
            <input type="search" class="search_data2 w-full focus:outline-none bg-transparent text-sm" placeholder="Search..." />
          </label>

          <div class="flex items-center gap-3 w-full md:w-auto justify-end">
            <span class="text-sm text-base-content/70">Show</span>
            <select id="entries_limit2" class="select select-bordered select-sm rounded-lg w-24 bg-base-100">
              <option value="8" selected>8</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="All">All</option>
            </select>
            <span class="text-sm text-base-content/70">entries</span>
          </div>
        </div>

        <!-- Data Table Card -->
        <div class="bg-base-200/40 border border-base-300 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-left">
              <thead>
                <tr class="bg-[#0fab9e] text-white uppercase text-xs tracking-wider">
                  <th class="py-4 px-6 font-semibold">Property Name</th>
                  <th class="py-4 px-6 font-semibold">Address</th>
                  <th class="py-4 px-6 font-semibold">Type</th>
                  <th class="py-4 px-6 font-semibold">Date Request</th>
                  <th class="py-4 px-6 font-semibold">Time Request</th>
                  <th class="py-4 px-6 font-semibold">Status</th>
                  <th class="py-4 px-6 font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="myTable2 divide-y divide-base-300">
                <?php
                $get_data = $conn->prepare("
                    SELECT l.province, l.municipality, l.barangay, l.type, l.property_name,
                           i.status, i.data_id, i.landlord_id, i.date_sent, i.time_sent, i.int_id,l.user_id
                    FROM `interested` AS i
                    LEFT JOIN landlord AS l ON l.landlord_id = i.landlord_id
                    WHERE l.user_id = ?
                    ORDER BY i.date_sent DESC, i.time_sent DESC
                ");
                $get_data->bind_param("s", $user_id_login);
                $get_data->execute();
                $result = $get_data->get_result();

                while ($row = $result->fetch_assoc()) {
                    $status = $row['status'];
                    if ($status === "Pending") {
                        $bg_color = 'bg-warning';
                    } elseif ($status === "Decline") {
                        $bg_color = 'bg-error';
                    } elseif ($status === "Completed") {
                        $bg_color = 'bg-info';
                    } else {
                        $bg_color = 'bg-success';
                    }

                    $detail = getInterestDetail($conn, $type_pages, $row['type'], $row['data_id'], $row['property_name'], $row['landlord_id']);
                ?>
                  <tr class="data-row2">
                    <td><?= htmlspecialchars($row['property_name'] ?? ''); ?></td>
                    <td><?= htmlspecialchars(($row['barangay'] ?? '') . ', ' . ($row['municipality'] ?? '') . ', ' . ($row['province'] ?? '')); ?></td>
                    <td><?= htmlspecialchars($row['type'] ?? ''); ?></td>
                    <td><?= date('M j, Y', strtotime($row['date_sent'])); ?></td>
                    <td><?= date('g:i A', strtotime($row['time_sent'])); ?></td>
                    <td>
                      <p class="<?= $bg_color; ?> rounded-full p-0 text-center"><?= htmlspecialchars($status); ?></p>
                    </td>
                    <td>
                      <div class="flex items-center gap-1 flex-wrap">
                        <?php if ($detail): ?>
                          <button type="button" class="btn btn-info btn-xs text-white"
                                  data-detail="<?= htmlspecialchars(json_encode($detail), ENT_QUOTES); ?>"
                                  onclick="openInfoModal(this)">VIEW</button>
                        <?php else: ?>
                          <span class="text-xs text-base-content/50">N/A</span>
                        <?php endif; ?>

                        <?php if ($status === 'Pending'): ?>
                          <button type="button" class="btn btn-success btn-xs text-white"
                                  data-int-id="<?= htmlspecialchars($row['int_id'], ENT_QUOTES); ?>"
                                  data-action="Approved"
                                  data-name="<?= htmlspecialchars($row['property_name'] ?? '', ENT_QUOTES); ?>"
                                  onclick="confirmAction(this)">APPROVE</button>
                          <button type="button" class="btn btn-error btn-xs text-white"
                                  data-int-id="<?= htmlspecialchars($row['int_id'], ENT_QUOTES); ?>"
                                  data-action="Decline"
                                  data-name="<?= htmlspecialchars($row['property_name'] ?? '', ENT_QUOTES); ?>"
                                  onclick="confirmAction(this)">DECLINE</button>
                        <?php endif; ?>

                        <?php if ($status === 'Approved'): ?>
                          <button type="button" class="btn btn-primary btn-xs text-white"
                                  data-int-id="<?= htmlspecialchars($row['int_id'], ENT_QUOTES); ?>"
                                  data-action="Completed"
                                  data-name="<?= htmlspecialchars($row['property_name'] ?? '', ENT_QUOTES); ?>"
                                  onclick="confirmAction(this)">COMPLETE</button>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
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

  <!-- Info Modal (generic para sa lahat ng type) -->
  <dialog id="info_modal" class="modal">
    <div class="modal-box max-w-2xl p-0 overflow-hidden">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 z-10">✕</button>
      </form>
      <div class="w-full h-64 sm:h-96 bg-base-200">
        <img id="info_img" src="" alt="" class="w-full h-full object-cover"
             onerror="this.src='../assets/images/placeholder-bed.jpg';" />
      </div>
      <div class="p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-bold" id="info_title"></h3>
          <span id="info_status" class="badge badge-lg"></span>
        </div>
        <div id="info_fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4"></div>
        <div class="flex justify-end">
          <a id="info_link" href="#" class="btn btn-success btn-sm text-white hidden">View Property</a>
        </div>
      </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

  <!-- Hidden form para sa Approve / Decline -->
  <form id="action_form" method="POST" action="interest_owner.php" class="hidden">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
    <input type="hidden" name="int_id" id="action_int_id">
    <input type="hidden" name="int_action" id="action_type">
  </form>

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/query_filter.js"></script>
  <script>
    // Confirm Approve / Decline gamit ang CoolAlert
    function confirmAction(btn) {
      const action  = btn.dataset.action;   // "Approved", "Decline" or "Completed"
      const intId   = btn.dataset.intId;
      const name    = btn.dataset.name || 'this request';
      const texts = {
        Approved:  { title: 'Are you sure you want to approve?',  yes: 'Yes, approve' },
        Decline:   { title: 'Are you sure you want to decline?',  yes: 'Yes, decline' },
        Completed: { title: 'Are you sure you want to mark this as completed?', yes: 'Yes, complete' }
      };
      const t = texts[action];

      const submitIt = () => {
        document.getElementById('action_int_id').value = intId;
        document.getElementById('action_type').value = action;
        document.getElementById('action_form').submit();
      };

      const result = CoolAlert.show({
        icon: 'question',
        title: t.title,
        text: 'Property: ' + name,
        showCancelButton: true,
        confirmButtonText: t.yes,
        cancelButtonText: 'Cancel',
        customClass: {
          container: 'z-[9999]'
        }
      });

      if (result && typeof result.then === 'function') {
        result.then((r) => { if (r && r.isConfirmed) submitIt(); });
      }
    }

    function openInfoModal(btn) {
      const d = JSON.parse(btn.getAttribute('data-detail'));

      document.getElementById('info_img').src = d.image;
      document.getElementById('info_title').textContent = d.title;

      // Status badge (lalabas lang kung may status)
      const st = document.getElementById('info_status');
      st.textContent = d.status || '';
      st.className = 'badge badge-lg';
      if (!d.status) st.classList.add('hidden');
      else if (d.status === 'Available') st.classList.add('badge-success', 'text-white');
      else if (d.status === 'Occupied') st.classList.add('badge-error', 'text-white');
      else st.classList.add('badge-ghost');

      // Dynamic fields (kahit ilan, depende sa type)
      const box = document.getElementById('info_fields');
      box.innerHTML = '';
      Object.entries(d.fields).forEach(([label, value]) => {
        const wrap = document.createElement('div');
        wrap.className = 'p-4 bg-base-200/60 rounded-xl';
        const l = document.createElement('p');
        l.className = 'text-xs text-base-content/60 uppercase tracking-wider';
        l.textContent = label;
        const v = document.createElement('p');
        v.className = 'text-lg font-semibold mt-1 truncate';
        v.textContent = value;
        wrap.append(l, v);
        box.appendChild(wrap);
      });

      // Link (depende sa type, galing sa PHP)
      const link = document.getElementById('info_link');
      if (d.link) {
        link.href = d.link;
        link.textContent = d.link_text || 'View Property';
        link.classList.remove('hidden');
      } else {
        link.classList.add('hidden');
      }

      document.getElementById('info_modal').showModal();
    }
  </script>
</body>
</html>