<?php
include 'notifications.php';

$noti_id = isset($_GET['noti_id']) ? $_GET['noti_id'] : null;

$text_noti = "Notification not found.";
$date_sent = "";
$time_sent = "";
$sender    = "System";

if ($noti_id) {
    $get_information = $conn->prepare("SELECT * FROM `notifications` WHERE `noti_id` = ?");
    $get_information->bind_param("i", $noti_id);
    $get_information->execute();
    $result_notifications = $get_information->get_result();

    if ($result_notifications && $result_notifications->num_rows > 0) {
        $row_noti = $result_notifications->fetch_assoc();
        $text_noti = $row_noti['text_noti'];
        $date_sent = $row_noti['date_sent'];
        $time_sent = $row_noti['time_sent'];
        $sender    = $row_noti['sender'];
    }
}
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-lg p-0 overflow-hidden shadow-2xl rounded-2xl bg-white border border-gray-100">
    
    <!-- Modal Header -->
    <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-b border-gray-100">
      <div class="flex items-center space-x-3">
        <!-- Icon Indicator -->
        <div class="p-2 bg-blue-50 text-green-600 rounded-xl">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        </div>
        <div>
          <h3 class="font-bold text-gray-800 text-base">Notification Details</h3>
          <p class="text-xs text-gray-400">From: <?= htmlspecialchars($sender, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
      </div>

      <!-- Close Button (Redirects back or closes modal) -->
      <form method="dialog">
        <a href="notifications.php" class="btn btn-sm btn-circle btn-ghost text-gray-400 hover:text-gray-600">✕</a>
      </form>
    </div>

    <!-- Modal Body Content -->
    <div class="p-6 space-y-4">
      <!-- Notification Message Box -->
      <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 text-sm leading-relaxed">
        <?= nl2br(htmlspecialchars($text_noti, ENT_QUOTES, 'UTF-8')) ?>
      </div>

      <!-- Timestamp Information -->
      <div class="flex items-center justify-between text-xs text-gray-400 pt-2 border-t border-gray-100">
        <span class="flex items-center gap-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <?= htmlspecialchars($date_sent . ' ' . $time_sent, ENT_QUOTES, 'UTF-8') ?>
        </span>
      </div>
    </div>



  </div>
</dialog>