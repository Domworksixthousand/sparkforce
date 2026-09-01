<?php

  $data_status = "Banned";
  $select = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id`= ? AND `status` = ?");
  $select->bind_param("ss", $user_id_login, $data_status);
  $select->execute();
  $result_selected = $select->get_result();

  $is_banned = false;
  if($result_selected->num_rows>0){
    $is_banned = true;
    $banned_row = $result_selected->fetch_assoc();
  }

?>

<?php if($is_banned): ?>

<div id="bannedModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>

  <!-- Card -->
  <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">

    <!-- Header -->
    <div class="bg-rose-600 px-6 py-5 flex items-start gap-4">
      <div class="flex-shrink-0 w-11 h-11 rounded-full bg-white/15 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 105.636 5.636a9 9 0 0012.728 12.728zM5.636 5.636l12.728 12.728" />
        </svg>
      </div>
      <div>
        <h2 class="text-lg font-semibold text-white">Account Banned</h2>
        <p class="text-rose-100 text-sm mt-0.5">Your access to this account has been suspended</p>
      </div>
    </div>

    <!-- Body -->
    <div class="px-6 py-5 space-y-4">
      <p class="text-slate-600 text-sm leading-relaxed">
        This account has been banned for violating our Terms of Service. If you believe this is a mistake, you can appeal by contacting our support team.
      </p>


      <!-- Ban details -->
      <div class="flex items-center justify-between text-sm border-t border-slate-100 pt-4">
        <span class="text-slate-500">Banned on</span>
        <span class="text-slate-800 font-medium">
          <?= isset($banned_row['banned_at']) ? date('M j, Y', strtotime($banned_row['banned_at'])) : date('M j, Y') ?>
        </span>
      </div>
     
    </div>

    <!-- Footer -->
    <div class="px-6 py-4 bg-slate-50 flex flex-col sm:flex-row gap-3 sm:justify-end">
      <form action="../functions.php" method="POST">
            <button type="submit" name="sigout_user"
            class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-sm font-medium hover:bg-slate-100 transition">
            Sign Out
          </button>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>