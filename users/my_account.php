

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
    <script src="./../assets/scripts/email_animation.js"></script>
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
      <div class="">
        <!--main content-->
      <main class="bg-gray-50/50 min-h-screen pb-12">
        <!-- Cover Section -->
        <section class="relative w-full h-[320px] lg:h-[380px] overflow-hidden">
          <img src="../assets/images/background_cover.png" class="w-full h-full object-cover" alt="Cover Image">
          <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </section>

        <!-- Main Content Container -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-[-150px] relative z-10">
          <div class="flex flex-col lg:flex-row gap-6 items-stretch">
            
            <!-- LEFT COLUMN: Profile Card -->
            <div class="w-full lg:w-[32%] bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-8 flex flex-col items-center justify-center text-center">
              <!-- Avatar with Floating Camera Button -->
              <div class="relative group mb-4">
                <img src="<?php echo $final_profileko; ?>" 
                    class="w-32 h-32 object-cover rounded-full ring-4 ring-white shadow-lg transition duration-300 group-hover:scale-[1.02]" 
                    alt="User Profile">
                
                <button onclick="location.href='change_profile.php'" 
                        class="absolute bottom-1 right-1 cursor-pointer p-2.5 rounded-full bg-white text-gray-700 hover:text-emerald-600 shadow-md border border-gray-100 transition-all active:scale-95 hover:scale-110" 
                        title="Change Profile Picture">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera">
                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                    <circle cx="12" cy="13" r="3"/>
                  </svg>
                </button>
              </div>

              <!-- Name and Role/Badge -->
              <h3 class="font-bold text-xl text-gray-800 tracking-tight"><?php echo $fullnameko; ?></h3>
              <p class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full mt-2 uppercase tracking-wider">Verified Account</p>
            </div>

            <!-- RIGHT COLUMN: Tabs & Info Sheets -->
            <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-100/50 p-6 lg:p-8">
              <div class="tabs tabs-lift">

                <!-- TAB 1: Personal Info -->
                <label class="tab flex items-center gap-2 cursor-pointer pb-3 h-auto">
                  <input type="radio" name="my_tabs_3" class="peer hidden" checked="checked" />
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                      class="text-gray-400 peer-checked:text-emerald-600 transition-colors" 
                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                  </svg>
                  <span class="font-medium text-gray-500 peer-checked:text-emerald-600 peer-checked:font-bold transition-all text-sm sm:text-base">Personal Info</span>
                </label>
                
                <div class="tab-content bg-base-100 border-base-300 rounded-b-2xl p-6 lg:p-8">
                  <div class="border-b border-gray-100 pb-4 mb-6">
                    <h4 class="font-bold text-gray-800 text-lg">Basic Information</h4>
                    <p class="text-xs text-gray-400 mt-1">Your registered identity credentials.</p>
                  </div>
                  
                  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">First Name</p>
                      <p class="text-sm font-semibold text-gray-800"><?php echo $firstnameko; ?></p>
                    </div>
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Middle Name</p>
                      <p class="text-sm font-semibold text-gray-800"><?php echo !empty($middlenameko) ? $middlenameko : '—'; ?></p>
                    </div>
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Last Name</p>
                      <p class="text-sm font-semibold text-gray-800"><?php echo $lastnameko; ?></p>
                    </div>
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                      <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Suffix</p>
                      <p class="text-sm font-semibold text-gray-800"><?php echo !empty($suffixko) ? $suffixko : 'None'; ?></p>
                    </div>
                  </div>
                </div>

                <!-- TAB 2: Contact & Address -->
                <label class="tab flex items-center gap-2 cursor-pointer pb-3 h-auto">
                  <input type="radio" name="my_tabs_3" class="peer hidden" />
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                      class="text-gray-400 peer-checked:text-emerald-600 transition-colors" 
                      viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                  </svg>
                  <span class="font-medium text-gray-500 peer-checked:text-emerald-600 peer-checked:font-bold transition-all text-sm sm:text-base">Contact & Address</span>
                </label>
                
                <div class="tab-content bg-base-100 border-base-300 rounded-b-2xl p-6 lg:p-8">
                  <!-- Contact section -->
                  <div class="border-b border-gray-100 pb-4 mb-6">
                    <h4 class="font-bold text-gray-800 text-lg">Contact Information</h4>
                    <p class="text-xs text-gray-400 mt-1">Ways people can reach you.</p>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex items-center gap-3">
                      <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                      </div>
                      <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Email Address</p>
                        <p class="text-sm font-semibold text-gray-800"><?php echo $emailko; ?></p>
                      </div>
                    </div>
                    
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex items-center gap-3">
                      <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                      </div>
                      <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Contact Number</p>
                        <p class="text-sm font-semibold text-gray-800"><?php echo $contact_numberko; ?></p>
                      </div>
                    </div>
                  </div>

                  <!-- Address Section -->
                  <div class="border-b border-gray-100 pb-4 mb-6">
                    <h4 class="font-bold text-gray-800 text-lg">Address Details</h4>
                    <p class="text-xs text-gray-400 mt-1">Your registered location.</p>
                  </div>
                  <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Residential Address</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo !empty($addressko) ? $addressko : 'No address provided yet.'; ?></p>
                  </div>
                </div>

                <!-- TAB 3: Messages -->
                <label class="tab flex items-center gap-2 cursor-pointer pb-3 h-auto">
                  <input type="radio" name="my_tabs_3" class="peer hidden" />
                  <svg xmlns="http://www.w3.org/2000/svg"width="18" height="18" 
                      class="text-gray-400 peer-checked:text-emerald-600 transition-colors"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-user-icon lucide-shield-user"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="M6.376 18.91a6 6 0 0 1 11.249.003"/><circle cx="12" cy="11" r="4"/></svg>
                  <span class="font-medium text-gray-500 peer-checked:text-emerald-600 peer-checked:font-bold transition-all text-sm sm:text-base">Security</span>
                </label>
                
                <div class="tab-content bg-base-100 border-base-300 rounded-b-2xl p-6 lg:p-8">
                  <div class="border-b border-gray-100 pb-4 mb-6">
                    <h4 class="font-bold text-gray-800 text-lg">Account Security Details</h4>
                    <p class="text-xs text-gray-400 mt-1">Manage Username and Password.</p>
                  </div>
                  <div class="text-center py-12">
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex items-center gap-3 mb-4">
                      <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-cog-icon lucide-user-round-cog"><path d="m14.305 19.53.923-.382"/><path d="m15.228 16.852-.923-.383"/><path d="m16.852 15.228-.383-.923"/><path d="m16.852 20.772-.383.924"/><path d="m19.148 15.228.383-.923"/><path d="m19.53 21.696-.382-.924"/><path d="M2 21a8 8 0 0 1 10.434-7.62"/><path d="m20.772 16.852.924-.383"/><path d="m20.772 19.148.924.383"/><circle cx="10" cy="8" r="5"/><circle cx="18" cy="18" r="3"/></svg>
                      </div>
                      <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Username</p>
                        <p class="text-sm font-semibold text-gray-800">********</p>
                      </div>
                      
                    </div>
                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 flex items-center gap-3 mb-4">
                      <div class="p-2.5 bg-emerald-50 rounded-lg text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-lock-icon lucide-user-lock"><path d="M19 16v-2a2 2 0 0 0-4 0v2"/><path d="M9.5 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><rect x="13" y="16" width="8" height="5" rx=".899"/></svg>
                      </div>
                      <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Password</p>
                        <p class="text-sm font-semibold text-gray-800">********</p>
                      </div>     
                    </div>
                    <div >
                      <button onclick="location.href='change_credentials.php'" class="btn btn-success">Change Credentials</button>
                    </div>
                  </div>
                </div>

              </div>
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



  <script src="./../assets/scripts/index.js"></script>
</body>
</html>


