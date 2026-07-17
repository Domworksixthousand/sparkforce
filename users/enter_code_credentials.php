<?php

include 'my_account.php';
?>



  <!--change profile modal-->
  <dialog id="my_modal_3" class="modal ">
    <div class="modal-box">
       <form method="dialog" class=" flex justify-end items-end" >
          <button type="button" onclick="location.href='my_account.php'" class="cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
          </button>
        </form>
        <form method="POST" action="../functions.php" class="flex flex-col items-center justify-center p-4 " enctype="multipart/form-data">
           
             <!-- Username Field -->
                <div class="w-full block mb-4">
                    <p class="mb-2 ">Received Code </p>
                    <div class="input w-full flex items-center gap-2 box-border p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-icon lucide-code"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>
                        <input type="text" class="grow w-full bg-transparent focus:outline-none text-black" name="code"  placeholder="Enter Code" required />
                    </div>
                </div>
                <div class="flex gap-2 justify-end items-end">
                    <button type="submit" name="confirm_code_credentials" class="btn btn-success btn-sm">
                        Confirm
                    </button>

                </div>
        </form>
      </div>
  </dialog>