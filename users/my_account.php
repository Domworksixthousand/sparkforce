

<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">

  <?php 
      include '../alerts.php'; 
  ?>

  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">My Account</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section class="my-container flex gap-5 flex-col lg:flex-row">
              <div class="shadow-lg w-[100%] lg:w-[30%] p-[30px] flex justify-center items-center  flex-col border border-gray-100 rounded-[20px]">
                <img src="<?php echo $final_profileko; ?>" class="w-[80px] rounded-full ">
                <button onclick="my_modal_1.showModal()" class="cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="mb-3 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera-icon lucide-camera"><path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/><circle cx="12" cy="13" r="3"/></svg>
                </button>
                <h3 class="font-bold text-sm"><?php echo $fullnameko; ?></h3>
              </div>
              <div  class="shadow-lg  w-[100%]  p-[30px] rounded-[20px] border border-gray-100 ">
                <!-- name of each tab group should be unique -->
<div class="tabs tabs-lift">
  <input type="radio" name="my_tabs_3" class="tab" aria-label="Tab 1" />
  <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 1</div>

  <input type="radio" name="my_tabs_3" class="tab" aria-label="Tab 2" checked="checked" />
  <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 2</div>

  <input type="radio" name="my_tabs_3" class="tab" aria-label="Tab 3" />
  <div class="tab-content bg-base-100 border-base-300 p-6">Tab content 3</div>
</div>
              </div>
            </section>
        </main>
      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <?php include 'drawer.php'; ?>
    </div>
  </div>


  <!--change profile modal-->
  <dialog id="my_modal_1" class="modal ">
    <div class="modal-box bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)]">
       <form method="dialog" class=" flex justify-end items-end" >
          <button class="cursor-pointer">
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

  <script src="./../assets/scripts/index.js"></script>
</body>
</html>

<!-- JavaScript para sa Instant Preview -->
<script>
    const fileInput = document.getElementById('profile-upload');
    const preview = document.getElementById('profile-preview');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            // Gumamit ng FileReader para basahin ang file local sa browser
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Palitan ang src ng img tag gamit ang base64 data ng larawan
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(file);
        }
    });
</script>