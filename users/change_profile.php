<?php

include 'my_account.php';
?>



  <!--change profile modal-->
  <dialog id="my_modal_3" class="modal ">
    <div class="modal-box bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)]">
       <form method="dialog" class=" flex justify-end items-end" >
          <button type="button" onclick="location.href='my_account.php'" class="cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class=text-white viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </form>
        <form method="POST" action="../functions.php" class="flex flex-col items-center justify-center p-4 " enctype="multipart/form-data">
          <div class="relative group w-36 h-36">
              <img  id="profile-preview"  src="<?php echo $final_profileko; ?>" alt="Profile Preview" class="w-full h-full object-cover rounded-full border-4 border-white shadow-md transition duration-300 group-hover:scale-105"
              >
              <label for="profile-upload" class="absolute inset-0 bg-black/50 rounded-full flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 cursor-pointer"
              >
                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"></path>
                </svg>
                <span class="text-xs font-semibold">Change Profile Picture</span>
            </label>
              <input type="file" id="profile-upload" name="profile_image" accept="image/*" class="hidden" required>
          </div>
          <p class="mt-2 text-xs text-white ">Click the circle to change profile (Max 2MB)</p>
          <button type="submit" name="change_profile" class="btn mt-4">Change Profile</button>
        </form>
      </div>
  </dialog>
 