<?php
include '../config.php';

$user_id_chat = $_GET['id'];


$fullname = "User Not Found";
$final_photo = "../assets/images/logo-icon.png";

$get_main_user = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
$get_main_user->bind_param("s", $user_id_chat);
$get_main_user->execute();
$result_main = $get_main_user->get_result();

if ($row_main = $result_main->fetch_assoc()) {
    $lastname = $row_main['lastname'] ?? '';
    $firstname = $row_main['firstname'] ?? '';
    $profile = $row_main['profile'] ?? '';
    $fullname = trim($firstname . ' ' . $lastname);
    $final_photo = (empty($profile)) ? '../assets/images/logo-icon.png' : "../assets/uploads/$profile";
}

$sql_messages = "SELECT * FROM `messages` 
                 WHERE (`sender_id` = ? AND `receiver_id` = ?) 
                    OR (`sender_id` = ? AND `receiver_id` = ?) 
                 ORDER BY `date_sent` ASC, `time_sent` ASC";

$stmt_msg = $conn->prepare($sql_messages);
$stmt_msg->bind_param("ssss", $user_id_login, $user_id_chat, $user_id_chat, $user_id_login);
$stmt_msg->execute();
$messages_result = $stmt_msg->get_result();
?>

<div class="text-center my-2">
    <span class="text-[10px] text-base-content/50 bg-base-100 px-3 py-1 rounded-full border border-base-200">Today</span>
</div>

<?php 
if ($messages_result->num_rows > 0) {
    while ($msg = $messages_result->fetch_assoc()) {
        $is_me = ($msg['sender_id'] == $user_id_login);
        $msg_id = $msg['message_id']; 
        
        $sql_files = "SELECT * FROM `messages_uploaded` WHERE `message_id` = ?";
        $stmt_files = $conn->prepare($sql_files);
        $stmt_files->bind_param("s", $msg_id);
        $stmt_files->execute();
        $files_result = $stmt_files->get_result();
?>
        <?php if (!$is_me): ?>
            <!-- Received Message (chat-start) -->
            <div class="chat chat-start">
                <div class="chat-image avatar">
                    <div class="w-8 h-8 rounded-full">
                        <img src="<?php echo htmlspecialchars($final_photo); ?>" alt="Sender" />
                    </div>
                </div>
                <div class="chat-header text-xs text-base-content/50 mb-1">
                    <?php echo htmlspecialchars($fullname); ?> 
                    <time class="text-[10px] opacity-70"><?php echo htmlspecialchars($msg['time_sent']); ?></time>
                </div>
                
                <div class="chat-bubble chat-bubble-neutral text-xs space-y-2">
                    <?php while ($file = $files_result->fetch_assoc()): ?>
                        <?php 
                            $file_ext = strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION));
                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $file_path = '../assets/uploads/' . $file['file_name'];
                        ?>

                        <?php if ($is_image): ?>
                            <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank" rel="noopener noreferrer" class="inline-block">
                                <img src="<?php echo htmlspecialchars($file_path); ?>" alt="Uploaded Image" class="max-w-xs rounded-lg max-h-48 object-cover cursor-pointer" />
                            </a>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($file_path); ?>" download class="flex items-center gap-2 underline text-current">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span><?php echo htmlspecialchars($file['file_name']); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endwhile; ?>

                    <!-- Render Text Message if available -->
                    <?php if (!empty($msg['message'])): ?>
                        <p><?php echo htmlspecialchars($msg['message']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Sent Message (chat-end) -->
            <div class="chat chat-end">
                <div class="chat-header text-xs text-base-content/50 mb-1">
                    <time class="text-[10px] opacity-70"><?php echo htmlspecialchars($msg['time_sent']); ?></time>
                </div>
                
                <div class="chat-bubble chat-bubble-success text-xs space-y-2">
                    <?php while ($file = $files_result->fetch_assoc()): ?>
                        <?php 
                            $file_ext = strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION));
                            $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $file_path = '../assets/uploads/' . $file['file_name'];
                        ?>

                        <?php if ($is_image): ?>
                            <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank" rel="noopener noreferrer" class="inline-block">
                                <img src="<?php echo htmlspecialchars($file_path); ?>" alt="Uploaded Image" class="max-w-xs rounded-lg max-h-48 object-cover cursor-pointer" />
                            </a>
                        <?php else: ?>
                            <a href="<?php echo htmlspecialchars($file_path); ?>" download class="flex items-center gap-2 underline text-current">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <span><?php echo htmlspecialchars($file['file_name']); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endwhile; ?>

                    <!-- Render Text Message if available -->
                    <?php if (!empty($msg['message'])): ?>
                        <p class="font-bold"><?php echo htmlspecialchars($msg['message']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="chat-footer opacity-50 text-[10px] capitalize">
                    <?php echo htmlspecialchars($msg['status']); ?>
                </div>
            </div>
        <?php endif; ?>
<?php
    }
} else {
?>
    <div class="p-6 flex justify-center items-center flex-col text-base-content/50 text-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/></svg>
        No messages yet. Start the conversation!
    </div>
<?php
} 
?>