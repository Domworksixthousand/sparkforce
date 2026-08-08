<?php
    include 'messages.php';
?>


<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-1xl">
    <a href="messages.php" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</a>
    
    <form action="../functions.php" method="POST" enctype="multipart/form-data" class="mt-6">
       
        <label class="input input-sm input-bordered flex items-center gap-2 rounded-full bg-base-200/50 focus-within:bg-base-100 w-[100%]">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" id="userSearchInput" class="grow text-xs w-[100%]" placeholder="Search User..." />
        </label>
        

        <div id="userList">
            <?php
                $user_status = "Approved";
                $role = "1";
                $people = $conn->prepare("SELECT * FROM `accounts` WHERE `status` = ? AND `user_type` != ? AND `user_id` != ?");
                $people->bind_param("sss", $user_status, $role, $user_id_login);
                $people->execute();
                $result_people = $people->get_result();

                if ($result_people->num_rows > 0) {
                    while ($row_people = mysqli_fetch_assoc($result_people)) {
                        $lastname = $row_people['lastname'] ?? '';
                        $firstname = $row_people['firstname'] ?? '';
                        $profile = $row_people['profile'] ?? '';
                        $user_id = $row_people['user_id'] ?? '';
                        $fullname = trim($firstname . ' ' . $lastname);
                        $final_photo = (empty($profile)) ? '../assets/images/logo-icon.png' : "../assets/uploads/$profile";
            ?>
                       
                        <a href="chat_portal.php?id=<?php echo htmlspecialchars($user_id); ?>" class="user-item flex items-center gap-4 p-4 hover:bg-base-200/60 rounded-xl transition-all duration-200 cursor-pointer relative my-2 hover:shadow-xl hover:border border-gray-200">
                            <div class="avatar online shrink-0">
                                <div class="w-12 h-12 rounded-full ring-2 ring-primary/10">
                                    <img src="<?php echo htmlspecialchars($final_photo); ?>" alt="<?php echo htmlspecialchars($fullname); ?>" class="object-cover" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                
                                    <h4 class="user-name font-semibold text-sm text-base-content truncate pr-2 group-hover:text-primary transition-colors">
                                        <?php echo htmlspecialchars($fullname); ?>
                                    </h4>
                                </div>
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs text-base-content/70 truncate">
                                        Click to start the conversation...
                                    </p>
                                </div>
                            </div>
                        </a>
            <?php
                    }
                } else {
            ?>
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M17.925 20.056a6 6 0 0 0-11.851.001"/><circle cx="12" cy="11" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                        </div>
                        <h3 class="text-base font-semibold text-gray-500">No User Found!</h3>
                    </div>
            <?php
                }
            ?>
            
      
            <div id="noSearchResults" class="hidden flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-16 h-16 mb-4 rounded-full bg-base-200/70 flex items-center justify-center text-base-content/40">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round"><path d="M17.925 20.056a6 6 0 0 0-11.851.001"/><circle cx="12" cy="11" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                </div>
                <h3 class="text-base font-semibold text-gray-500">No User Found!</h3>
            </div>
        </div>
    </form>
  </div>
</dialog>





