<?php
include '../config.php';

// Siguraduhing naka-set ang session at may $user_id_login ka na galing sa config o session
// session_start(); 
// $user_id_login = $_SESSION['user_id'] ?? '';

$query = "
    SELECT a.*, MAX(CONCAT(m.date_sent, ' ', m.time_sent)) AS latest_message_time 
    FROM accounts a
    JOIN messages m ON (a.user_id = m.sender_id OR a.user_id = m.receiver_id)
    WHERE (m.sender_id = ? OR m.receiver_id = ?)
    AND a.user_id != ?
    AND a.status = ?
    AND a.user_type != ?
    GROUP BY a.user_id
    ORDER BY latest_message_time DESC
";

$stmt = $conn->prepare($query);
$user_status = "Approved";
$role = "1";

$stmt->bind_param("sssss", $user_id_login, $user_id_login, $user_id_login, $user_status, $role);
$stmt->execute();
$result_people = $stmt->get_result();

if ($result_people->num_rows > 0) {
    while ($row_people = $result_people->fetch_assoc()) {
        $p_lastname = $row_people['lastname'] ?? '';
        $p_firstname = $row_people['firstname'] ?? '';
        $p_profile = $row_people['profile'] ?? '';
        $p_user_id = $row_people['user_id'] ?? '';
        $p_fullname = trim($p_firstname . ' ' . $p_lastname);
        $p_photo = (empty($p_profile)) ? '../assets/images/logo-icon.png' : "../assets/uploads/$p_profile";

        // Kuhanin ang bilang ng mga hindi pa nababasang mensahe (lahat ng hindi 'seen')
        $unseen_sql = "SELECT COUNT(*) as total_unseen FROM messages WHERE sender_id = ? AND receiver_id = ? AND `status` != 'seen'";
        $unseen_stmt = $conn->prepare($unseen_sql);
        $unseen_stmt->bind_param("ss", $p_user_id, $user_id_login);
        $unseen_stmt->execute();
        $unseen_res = $unseen_stmt->get_result()->fetch_assoc();
        $unseen_count = $unseen_res['total_unseen'] ?? 0;

        // I-highlight ang active chat kung sakaling nakabukas
        $highlight = (isset($user_id_chat) && $p_user_id === $user_id_chat) ? 'bg-success hover:bg-success hover:text-white' : '';
        ?>
        <a href="chat_portal.php?id=<?php echo htmlspecialchars($p_user_id); ?>" class="user-item border border-success mb-2 flex items-center justify-between p-3 rounded-xl hover:bg-base-200 transition-colors cursor-pointer group <?php echo $highlight; ?>">
            <div class="flex items-center gap-3 min-w-0">
                <div class="avatar online shrink-0">
                    <div class="w-10 h-10 rounded-full ring-1 ring-success/20">
                        <img src="<?php echo htmlspecialchars($p_photo); ?>" alt="<?php echo htmlspecialchars($p_fullname); ?>" class="object-cover" />
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="user-name font-semibold text-sm truncate group-hover:text-success transition-colors">
                        <?php echo htmlspecialchars($p_fullname); ?>
                    </h4>
                    <p class="text-xs text-base-content/60 truncate">Click to open chat...</p>
                </div>
            </div>

            <!-- Unseen Count Badge -->
            <?php if ($unseen_count > 0): ?>
                <span class="badge badge-success badge-sm text-white font-bold ml-2 shrink-0">
                    <?php echo $unseen_count; ?>
                </span>
            <?php endif; ?>
        </a>
        <?php
    }
} else {
    ?>
    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
        <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 10a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 14.286V4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
                <path d="M20 9a2 2 0 0 1 2 2v10.286a.71.71 0 0 1-1.212.502l-2.202-2.202A2 2 0 0 0 17.172 19H10a2 2 0 0 1-2-2v-1"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-base-content">No messages yet</h3>
        <p class="text-xs text-base-content/60 max-w-xs mt-1">
            Your inbox is clear. When you start a chat or receive inquiries, they'll show up here.
        </p>
    </div>
    <?php 
} 
?>