<?php
include "reports.php";

// ---------------------------------------------------------------
// 1. Read the report id safely
// ---------------------------------------------------------------
$report_id = isset($_GET['id']) ? $_GET['id'] : null;

// Defaults so we never reference undefined variables further down
$report_type      = null;
$user_id_reporter = null;
$user_id_reported = null;
$reason           = null;
$post_id          = null;
$status           = null;
$date_reported    = null;

$fullname_reporter = 'Unknown user';
$fullname_reported  = 'Unknown user';
$profile_reporter   = '../assets/images/logo-icon.png';
$profile_reported   = '../assets/images/logo-icon.png';

$post_caption = null;
$post_image   = null;


$get_details = $conn->prepare("SELECT * FROM `report` WHERE `report_id` = ?");
$get_details->bind_param("s", $report_id);
$get_details->execute();
$result_details = $get_details->get_result();

if ($result_details && $result_details->num_rows > 0) {
    $row_details       = $result_details->fetch_assoc();
    $report_type       = $row_details['report_type'];
    $user_id_reporter  = $row_details['user_id_reporter'];
    $user_id_reported  = $row_details['user_id_reported'];
    $reason            = $row_details['reason'];
    $post_id           = $row_details['post_id'];
    $status            = $row_details['status'];
    $date_reported     = $row_details['date_reported'];
}


function get_account_display($conn, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $parts = array_filter([
            $row['firstname']  ?? '',
            $row['middlename'] ?? '',
            $row['lastname']   ?? '',
            $row['suffix']     ?? '',
        ], fn($p) => trim($p) !== '');

        return [
            'fullname' => implode(' ', $parts),
            'profile'  => !empty($row['profile']) ? $row['profile'] : '../assets/images/logo-icon.png',
        ];
    }
    return null;
}

if ($user_id_reporter) {
    $reporter = get_account_display($conn, $user_id_reporter);
    if ($reporter) {
        $fullname_reporter = $reporter['fullname'];
        $profile_reporter  = $reporter['profile'];
    }
}

if ($user_id_reported) {
    $reported = get_account_display($conn, $user_id_reported);
    if ($reported) {
        $fullname_reported = $reported['fullname'];
        $profile_reported  = $reported['profile'];
    }
}

$status_styles = [
    'pending'   => ['bg-amber-100 text-amber-700',   'bg-amber-500'],
    'reviewing' => ['bg-blue-100 text-blue-700',    'bg-blue-500'],
    'resolved'  => ['bg-emerald-100 text-emerald-700','bg-emerald-500'],
    'dismissed' => ['bg-gray-200 text-gray-600',    'bg-gray-400'],
];
$status_key   = strtolower($status ?? 'pending');
[$status_badge_class, $status_dot_class] = $status_styles[$status_key] ?? $status_styles['pending'];


$stmt = $conn->prepare("SELECT COUNT(user_id_reported) as total_reported FROM report WHERE `post_id` = ?");
$stmt->bind_param("s", $post_id);
$stmt->execute();
$result = $stmt->get_result(); 
$row = $result->fetch_assoc();

$total_reported = (int)$row['total_reported'];



// Small escaping helper for output
function h($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box max-w-6xl">
    <form method="dialog" class="mb-2">
      <button type="button" onclick="location.href='reports.php'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-bold text-gray-800">Report #<?= h($report_id) ?></h3>
       <p class="text-sm text-gray-400"><?= h(date('F j, Y', strtotime($date_reported))) ?></p>
      </div>
      <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $status_badge_class ?>">
        <span class="inline-block w-1.5 h-1.5 rounded-full mr-1.5 align-middle <?= $status_dot_class ?>"></span><?= h(ucfirst($status ?? 'pending')) ?>
      </span>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-5">
      <div class="flex items-center gap-1" id="amenity_tabs">
        <button type="button" data-tab="details"
          class="amenity-tab-btn px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors border-[#0d9488] text-[#0d9488] cursor-pointer">
          Details
        </button>
        <button type="button" data-tab="images"
          class="amenity-tab-btn px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors border-transparent text-gray-500 hover:text-gray-700 cursor-pointer">
          Evidence 
        </button>
        <button type="button" data-tab="history"
          class="amenity-tab-btn px-4 py-2.5 text-sm font-semibold border-b-2 transition-colors border-transparent text-gray-500 hover:text-gray-700 cursor-pointer">
          History
        </button>
      </div>
    </div>

    <!-- Details tab -->
    <div class="amenity-tab-panel" data-tab-panel="details">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        <!-- Reporter card -->
        <div class="border border-gray-100 rounded-[8px] p-4 flex items-center gap-3">
          <img src="../assets/uploads/<?= h($profile_reporter) ?>" alt="Reporter" class="w-11 h-11 rounded-full object-cover bg-gray-100" />
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Reported by</p>
            <p class="text-sm font-semibold text-gray-800 truncate"><?= h($fullname_reporter) ?></p>
            <p class="text-xs text-gray-400">ID: <?= h($user_id_reporter) ?></p>
          </div>
        </div>

        <!-- Reported user card -->
        <div class="border border-gray-100 rounded-[8px] p-4 flex items-center gap-3">
          <img src="<?= h($profile_reported) ?>" alt="Reported user" class="w-11 h-11 rounded-full object-cover bg-gray-100" />
          <div class="min-w-0">
            <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Reported user</p>
            <p class="text-sm font-semibold text-gray-800 truncate"><?= h($fullname_reported) ?></p>
            <p class="text-xs text-gray-400">ID: <?= h($user_id_reported) ?></p>
          </div>
        </div>
      </div>

      <div class="mb-5">
        <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1">Report type</p>
        <p class="text-sm text-gray-700"><?= h($report_type ?? 'Not specified') ?></p>
      </div>

      <div class="mb-5">
        <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold mb-1">Reason</p>
        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed"><?= h($reason ?? 'No reason provided.') ?></p>
      </div>
    </div>

    <!-- Evidence tab -->
    <div class="amenity-tab-panel hidden" data-tab-panel="images">
      <?php 
      $get_images = $conn->prepare("SELECT `image_name` FROM `report_images` WHERE `report_id` = ?");
      $get_images->bind_param("s", $report_id);
      $get_images->execute();
      $result_images = $get_images->get_result();

      if ($result_images && $result_images->num_rows > 0): ?>
        <div class="columns-1 sm:columns-2 md:columns-3 gap-3 space-y-3">
          <?php while ($row_img = $result_images->fetch_assoc()): ?>
            <a href="../assets/uploads/<?= h($row_img['image_name']) ?>" target="_blank" class="block w-full rounded-[8px] overflow-hidden border border-gray-100 hover:opacity-90 transition-opacity break-inside-avoid">
              <img src="../assets/uploads/<?= h($row_img['image_name']) ?>" alt="Evidence" class="w-full h-auto object-cover" />
            </a>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="flex flex-col items-center justify-center py-10 text-gray-400">
          <p class="text-sm">No evidence images were attached to this report.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- History tab -->
    <div class="amenity-tab-panel hidden" data-tab-panel="history">
      <?php 
      $get_history = $conn->prepare("
          SELECT r.`report_id`, r.`report_type`, r.`status`, r.`date_reported`, r.`post_id`,r.`user_id_reported`,
                 a.`firstname`, a.`middlename`, a.`lastname`, a.`suffix`
          FROM `report` AS r
          LEFT JOIN `accounts` AS a ON r.`user_id_reporter` = a.`user_id`
          WHERE r.`user_id_reported` = ? 
          ORDER BY r.`date_reported` DESC
      ");
      $get_history->bind_param("s", $user_id_reported);
      $get_history->execute();
      $result_history = $get_history->get_result();

      if ($result_history && $result_history->num_rows > 0): ?>
        <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto pr-1">
          <?php while ($row_hist = $result_history->fetch_assoc()): 
              $name_parts = array_filter([
                  $row_hist['firstname']  ?? '',
                  $row_hist['middlename'] ?? '',
                  $row_hist['lastname']   ?? '',
                  $row_hist['suffix']     ?? '',
              ], fn($p) => trim($p) !== '');
              
              $reporter_fullname = !empty($name_parts) ? implode(' ', $name_parts) : 'Unknown user';
              $hist_status = strtolower($row_hist['status'] ?? 'pending');

              $badge_classes = [
                  'pending'   => 'bg-amber-100 text-amber-700',
                  'reviewing' => 'bg-blue-100 text-blue-700',
                  'resolved'  => 'bg-emerald-100 text-emerald-700',
                  'dismissed' => 'bg-gray-200 text-gray-600',
              ];
              $current_badge = $badge_classes[$hist_status] ?? 'bg-gray-100 text-gray-600';
          ?>
            <div class="border border-gray-100 rounded-[8px] p-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <span class="text-xs font-bold text-gray-800">Report #<?= h($row_hist['report_id']) ?></span>
                  <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold <?= $current_badge ?>">
                    <?= h(ucfirst($row_hist['status'] ?? 'pending')) ?>
                  </span>
                </div>
                <p class="text-xs text-gray-800 font-semibold mb-0.5">Reported by: <?= h($reporter_fullname) ?></p>
                <p class="text-xs text-gray-500"><?= h($row_hist['report_type'] ?? 'Not specified') ?></p>
              </div>
              <a href="?id=<?= h($row_hist['report_id']) ?>" class="btn btn-xs btn-outline border-[#0d9488] text-[#0d9488] hover:bg-[#0d9488] hover:text-white hover:border-[#0d9488] rounded-[5px]">
                View
              </a>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <div class="flex flex-col items-center justify-center py-10 text-gray-400">
          <p class="text-sm">No other reports found.</p>
        </div>
      <?php endif; ?>
    </div>

   <div class="modal-action mt-6">
      <?php
      if ($total_reported > 5 ) {
          ?>
          <a href="report_ban_form.php?report_id=<?= $report_id ?>" class="btn btn-error">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-x-icon lucide-shield-x"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m14.5 9.5-5 5"/><path d="m9.5 9.5 5 5"/></svg>
              Ban Account
          </a>
          <?php
      } else {
          ?>
          <a href="warning_form.php?report_id=<?= $report_id ?>" class="btn btn-warning text-black">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert-icon lucide-triangle-alert"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
              Send Warning
          </a>
          <?php
      }
      ?>
  </div>

  </div>
</dialog>