<?php
    include 'messages.php';

    if (isset($_GET['id'])) {
        $user_id_chat = $_GET['id'];
    } else {
        header("Location: messages.php");
        exit;
    }
   
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
$old = "unseen";
$middle = "viewed";
$new = "seen";
$update1 = $conn->prepare("UPDATE `messages` SET `status` = ? WHERE `sender_id` = ? AND `receiver_id` = ? AND (`status` = ? OR `status` = ?)");
$update1->bind_param("sssss", $new, $user_id_chat, $user_id_login, $old, $middle);
$update1->execute();
    
?>

<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-5xl h-[85vh] p-0 overflow-hidden flex flex-col bg-base-100 rounded-2xl relative">
    
    <!-- Close Button -->
    <a href="messages.php" class="btn btn-sm btn-circle btn-ghost absolute right-3 top-3 z-20">✕</a>

    <div class="flex h-full w-full flex-col lg:flex-row">
      
      <!-- LEFT SIDEBAR: User Search & List -->
      <aside class="w-1/3 min-w-[260px] border-r border-base-200  flex-col h-full bg-base-100/50 hidden lg:block">
        
        <!-- Header & Search -->
        <div class="p-4 border-b border-base-200">
          <h2 class="font-bold text-lg mb-3">Messages</h2>
          <label class="input input-sm input-bordered flex items-center gap-2 rounded-full bg-base-200/50 focus-within:bg-base-100 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="userSearchInput" class="grow text-xs" placeholder="Search user..." />
          </label>
        </div>

        <!-- User List Container -->
        <div id="userList" class="flex-1 overflow-y-auto  p-2 space-y-1">
       <?php

            $query = "
                SELECT DISTINCT a.* 
                FROM accounts a
                JOIN messages m ON (a.user_id = m.sender_id OR a.user_id = m.receiver_id)
                WHERE (m.sender_id = ? OR m.receiver_id = ?)
                AND a.user_id != ?
                AND a.status = ?
                AND a.user_type != ?
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

                    $highlight = ($p_user_id === $user_id_chat) ? 'bg-success hover:bg-success hover:text-white' : '';
                    ?>
                    <a href="chat_portal.php?id=<?php echo htmlspecialchars($p_user_id); ?>" class="user-item flex items-center gap-3 p-3 rounded-xl hover:bg-base-200 transition-colors cursor-pointer group <?php echo $highlight; ?>">
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
                    </a>
                    <?php
                }
            } else {
                ?>
                <div class="p-6 text-center text-base-content/50 text-sm">No conversations found.</div>
                <?php 
            } 
            ?>
            
            <div id="noSearchResults" class="hidden flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M17.925 20.056a6 6 0 0 0-11.851.001"/><circle cx="12" cy="11" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-500 text-sm" >No User Found!</h3>
            </div>
        </div>
      </aside>

      <!-- RIGHT PANEL: Chat Container -->
      <main class="flex-1 flex flex-col h-full bg-base-200/30">

        <!-- Header -->
        <header class="p-3 px-6 bg-base-100 border-b border-base-200 flex items-center gap-3 shadow-xs">
          <div class="avatar online">
            <div class="w-9 h-9 rounded-full">
              <img src="<?php echo htmlspecialchars($final_photo); ?>" alt="<?php echo htmlspecialchars($fullname); ?>" class="object-cover" />
            </div>
          </div>
          <div>
            <h3 class="font-bold text-sm"><?php echo htmlspecialchars($fullname); ?></h3>
          </div>
        </header>

        <!-- Messages Body -->
        <div id="message_body" class="flex-1 overflow-y-auto p-4 space-y-4">
         
        </div>


        <!-- Chat Form Input Section -->
        <div class="p-3 bg-base-100 border-t border-base-200">
          
          <!-- Dynamic File Preview List -->
          <div id="filePreviewContainer" class="hidden mb-2 p-2 bg-base-200/60 rounded-xl flex flex-wrap gap-2 max-h-28 overflow-y-auto"></div>

          <form action="../functions.php" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($user_id_chat); ?>" />
            
            <!-- Added multiple attribute and array bracket naming -->
            <input type="file" name="chat_attachments[]" id="chatFileInput" class="hidden" accept="image/*,.pdf,.doc,.docx" multiple />

            <button type="button" onclick="document.getElementById('chatFileInput').click()" class="btn btn-circle btn-ghost btn-sm text-base-content/60 hover:text-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
              </svg>
            </button>

            <input type="text" name="message" placeholder="Type a message..." class="input input-sm input-bordered grow rounded-full bg-base-200/50 focus:bg-base-100 text-xs focus:outline-none" autocomplete="off" />

            <button type="submit" name="send_message" class="btn btn-success btn-sm btn-circle">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-4 rotate-90 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
              </svg>
            </button>
          </form>
        </div>

      </main>
    </div>

  </div>
</dialog>

<!-- JavaScript for Multiple File Handling & Removal -->
<script>
  const fileInput = document.getElementById('chatFileInput');
  const previewContainer = document.getElementById('filePreviewContainer');
  let fileListStore = new DataTransfer();

  fileInput.addEventListener('change', function (e) {
    // Append newly selected files to the DataTransfer store
    Array.from(e.target.files).forEach(file => {
      fileListStore.items.add(file);
    });

    // Sync state back to standard input element
    fileInput.files = fileListStore.files;
    renderFilePreviews();
  });

  function renderFilePreviews() {
    previewContainer.innerHTML = '';

    if (fileListStore.files.length === 0) {
      previewContainer.classList.add('hidden');
      return;
    }

    previewContainer.classList.remove('hidden');

    Array.from(fileListStore.files).forEach((file, index) => {
      const fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
      
      const badge = document.createElement('div');
      badge.className = 'flex items-center gap-1.5 bg-base-100 px-2.5 py-1 rounded-lg border border-base-200 text-xs shadow-xs';
      
      badge.innerHTML = `
        <span class="badge badge-success badge-xs truncate max-w-[120px]">${file.name}</span>
        <span class="text-[10px] text-base-content/60">${fileSize}</span>
        <button type="button" onclick="removeSelectedFile(${index})" class="btn btn-ghost btn-xs btn-circle h-4 w-4 min-h-0 text-base-content/60 hover:text-error">✕</button>
      `;

      previewContainer.appendChild(badge);
    });
  }

  function removeSelectedFile(index) {
    const updatedStore = new DataTransfer();
    
    Array.from(fileListStore.files).forEach((file, i) => {
      if (i !== index) {
        updatedStore.items.add(file);
      }
    });

    fileListStore = updatedStore;
    fileInput.files = fileListStore.files;
    renderFilePreviews();
  }
</script>
<script>
    const chatUserId = "<?php echo htmlspecialchars($user_id_chat); ?>";

    async function pollMessages() {
        try {
            let res = await fetch(`fetch_chat.php?id=${chatUserId}`);
            let html = await res.text();
            
            const messageBody = document.getElementById('message_body');
            messageBody.innerHTML = html;
            
            // Optional: Auto scroll to bottom on new message
            messageBody.scrollTop = messageBody.scrollHeight;
        } catch (e) {
            console.error('Polling Error:', e);
        }
    }

    // Run immediately, then poll every 3 seconds
    pollMessages();
    setInterval(pollMessages, 3000);
</script>