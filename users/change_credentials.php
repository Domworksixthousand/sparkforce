<?php

include 'my_account.php';
?>



  <!--change profile modal-->
  <dialog id="my_modal_3" class="modal ">
    <div class="modal-box ">
       <form method="dialog" class=" flex justify-end items-end" >
          <button type="button" onclick="location.href='my_account.php'" class="cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </form>
        <form method="POST" id="myForm" action="../functions.php" class="flex flex-col items-center justify-center p-4 " enctype="multipart/form-data">
            <p class="text-sm mb-5 bg-error p-3 text-black rounded-lg animate-pulse font-medium">
                Make Username Blank if Password only / Make password blank if Username only
            </p>
            <div class="w-[100%] flex flex-col  gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2">New Username *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>" placeholder="Enter Username"  />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">Paassword *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="password" name="password"  value="<?php echo $_SESSION['password'] ?? ''; ?>" placeholder="Enter Password "  />
                            <img src="../assets/images/hide-icon.png" class="cursor-pointer" id="pass" onclick="toggletPassword_user()">
                        </label>
                    </span>
                    <span class="w-[100%] mb-3">
                        <p class="mb-2">Repeat Password *</p>
                        <label class="input w-[100%]">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="repeat_password" name="repeat_password"  value="<?php echo $_SESSION['repeat_password'] ?? ''; ?>" placeholder="Enter Repeat Password" />
                            <img src="../assets/images/hide-icon.png" id="repeat_pass" class="cursor-pointer" onclick="toggleRepeatPassword_user()">
                        </label>
                    </span>
                    <span class="text-center">
                        <button type="submit" name="change_credentials" class="btn btn-success">Confirm</button>
                    </span>
                </div>
        </form>
      </div>
  </dialog>