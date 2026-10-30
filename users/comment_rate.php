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

// Submit review handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $token_ok = isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    $int_id   = $_POST['int_id'] ?? '';
    $rating   = (int)($_POST['rating'] ?? 0);
    $comment  = trim($_POST['comment'] ?? '');

    if (!$token_ok || $int_id === '') {
        $_SESSION['error'] = 'Invalid request.';
    } elseif ($rating < 1 || $rating > 5) {
        $_SESSION['error'] = 'Please select a rating from 1 to 5 stars.';
    } elseif ($comment === '') {
        $_SESSION['error'] = 'Please write a comment.';
    } elseif (mb_strlen($comment) > 500) {
        $_SESSION['error'] = 'Comment must not exceed 500 characters.';
    } else {
        // Dapat sa user ang request at Completed na
        $c = $conn->prepare("SELECT landlord_id FROM interested WHERE int_id = ? AND user_id = ? AND status = 'Completed'");
        $c->bind_param("ss", $int_id, $user_id_login);
        $c->execute();
        $own = $c->get_result()->fetch_assoc();

        if (!$own) {
            $_SESSION['error'] = 'You can only rate completed requests.';
        } else {
            // Isang review lang bawat request
            $e = $conn->prepare("SELECT review_id FROM reviews WHERE int_id = ?");
            $e->bind_param("s", $int_id);
            $e->execute();
            if ($e->get_result()->fetch_assoc()) {
                $_SESSION['error'] = 'You already reviewed this request.';
            } else {
                $ins = $conn->prepare("
                    INSERT INTO reviews (`int_id`,`landlord_id`,`user_id`,`rating`,`comment`,`date_sent`,`time_sent`)
                    VALUES (?,?,?,?,?,?,?)
                ");
                $ins->bind_param("sssisss", $int_id, $own['landlord_id'], $user_id_login, $rating, $comment, $datetoday, $timetoday1);
                $ins->execute();
                $_SESSION['success'] = 'Thank you! Your rating and comment have been submitted.';
            }
        }
    }
    echo "<script>location.href='comment_rate.php';</script>";
    exit;
}

// Helper: ★★★☆☆
function renderStars($n) {
    $n = max(0, min(5, (int)$n));
    return str_repeat('★', $n) . str_repeat('☆', 5 - $n);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate &amp; Comment</title>
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
          <div class="text-xl font-bold tracking-wide">Rate &amp; Comment</div>
        </div>
      </nav>

      <main class="p-6 lg:p-10 max-w-7xl w-full mx-auto">

        <div class="mb-8 bg-base-200/60 p-6 rounded-2xl border border-base-300">
          <h1 class="text-2xl font-extrabold text-base-content">Rate &amp; Comment</h1>
          <p class="text-sm text-base-content/70 mt-1">Share your experience about the properties you have completed.</p>
        </div>

        <!-- Data Table Card -->
        <div class="bg-base-200/40 border border-base-300 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
            <table class="table table-zebra w-full text-left">
              <thead>
                <tr class="bg-[#0fab9e] text-white uppercase text-xs tracking-wider">
                  <th class="py-4 px-6 font-semibold">Property Name</th>
                  <th class="py-4 px-6 font-semibold">Type</th>
                  <th class="py-4 px-6 font-semibold">Rating</th>
                  <th class="py-4 px-6 font-semibold">Comment</th>
                  <th class="py-4 px-6 font-semibold">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-base-300">
                <?php
                $get_data = $conn->prepare("
                    SELECT i.int_id, l.property_name, l.type,
                           r.review_id, r.rating, r.comment
                    FROM `interested` AS i
                    LEFT JOIN landlord AS l ON l.landlord_id = i.landlord_id
                    LEFT JOIN reviews  AS r ON r.int_id = i.int_id
                    WHERE i.user_id = ? AND i.status = 'Completed'
                    ORDER BY i.date_sent DESC, i.time_sent DESC
                ");
                $get_data->bind_param("s", $user_id_login);
                $get_data->execute();
                $result = $get_data->get_result();
                $has_rows = false;

                while ($row = $result->fetch_assoc()) {
                    $has_rows = true;
                ?>
                  <tr>
                    <td><?= htmlspecialchars($row['property_name'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($row['type'] ?? ''); ?></td>
                    <?php if ($row['review_id']): ?>
                      <td class="text-warning text-lg whitespace-nowrap"><?= renderStars($row['rating']); ?></td>
                      <td class="max-w-xs"><p class="line-clamp-2 text-sm"><?= nl2br(htmlspecialchars($row['comment'])); ?></p></td>
                      <td><span class="badge badge-success text-white">Reviewed</span></td>
                    <?php else: ?>
                      <td class="text-base-content/40">Not rated yet</td>
                      <td class="text-base-content/40">—</td>
                      <td>
                        <button type="button" class="btn btn-warning btn-xs text-white"
                                data-int-id="<?= htmlspecialchars($row['int_id'], ENT_QUOTES); ?>"
                                data-name="<?= htmlspecialchars($row['property_name'] ?? '', ENT_QUOTES); ?>"
                                onclick="openRateModal(this)">RATE &amp; COMMENT</button>
                      </td>
                    <?php endif; ?>
                  </tr>
                <?php } ?>

                <?php if (!$has_rows): ?>
                  <tr>
                    <td colspan="5" class="text-center text-base-content/60 py-10">
                      No completed requests to rate yet.
                    </td>
                  </tr>
                <?php endif; ?>
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

  <!-- Rate & Comment Modal -->
  <dialog id="rate_modal" class="modal">
    <div class="modal-box max-w-md">
      <form method="dialog">
        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
      </form>

      <h3 class="text-xl font-bold">Rate &amp; Comment</h3>
      <p class="text-sm text-base-content/70 mt-1" id="rate_property"></p>

      <form id="rate_form" method="POST" action="comment_rate.php" class="mt-5 space-y-4">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="int_id" id="rate_int_id">
        <input type="hidden" name="submit_review" value="1">

        <!-- Stars 1 to 5 -->
        <div>
          <label class="text-xs text-base-content/60 uppercase tracking-wider">Your rating</label>
          <div class="rating rating-lg mt-2 flex" id="rate_stars">
            <input type="radio" name="rating" value="1" class="mask mask-star-2 bg-orange-400" aria-label="1 star" />
            <input type="radio" name="rating" value="2" class="mask mask-star-2 bg-orange-400" aria-label="2 stars" />
            <input type="radio" name="rating" value="3" class="mask mask-star-2 bg-orange-400" aria-label="3 stars" />
            <input type="radio" name="rating" value="4" class="mask mask-star-2 bg-orange-400" aria-label="4 stars" />
            <input type="radio" name="rating" value="5" class="mask mask-star-2 bg-orange-400" aria-label="5 stars" />
          </div>
          <p id="rate_error" class="text-error text-xs mt-1 hidden">Please select a rating.</p>
        </div>

        <!-- Comment -->
        <div>
          <label for="rate_comment" class="text-xs text-base-content/60 uppercase tracking-wider">Comment</label>
          <textarea id="rate_comment" name="comment" rows="4" maxlength="500" required
                    class="textarea textarea-bordered w-full mt-2 rounded-xl"
                    placeholder="Tell us about your experience..."></textarea>
          <p class="text-xs text-base-content/50 text-right mt-1"><span id="rate_count">0</span>/500</p>
        </div>

        <div class="modal-action mt-2">
          <button type="button" class="btn btn-ghost btn-sm"
                  onclick="document.getElementById('rate_modal').close()">Cancel</button>
          <button type="submit" class="btn btn-success btn-sm text-white">Submit</button>
        </div>
      </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
  </dialog>

  <script>
    function openRateModal(btn) {
      document.getElementById('rate_int_id').value = btn.dataset.intId;
      document.getElementById('rate_property').textContent = 'Property: ' + (btn.dataset.name || '');

      // reset
      document.querySelectorAll('#rate_stars input').forEach(r => r.checked = false);
      document.getElementById('rate_comment').value = '';
      document.getElementById('rate_count').textContent = '0';
      document.getElementById('rate_error').classList.add('hidden');

      document.getElementById('rate_modal').showModal();
    }

    document.getElementById('rate_comment').addEventListener('input', function () {
      document.getElementById('rate_count').textContent = this.value.length;
    });

    document.getElementById('rate_form').addEventListener('submit', function (e) {
      if (!document.querySelector('#rate_stars input:checked')) {
        e.preventDefault();
        document.getElementById('rate_error').classList.remove('hidden');
      }
    });

    document.querySelectorAll('#rate_stars input').forEach(r => {
      r.addEventListener('change', () => document.getElementById('rate_error').classList.add('hidden'));
    });
  </script>
</body>
</html>