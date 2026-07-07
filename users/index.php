<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome User!</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
</head>
<body class="bg-base-100">

  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Dashboard</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section>
              <h1 class="text-[2rem] font-bold">Welcome back, <span class="text-[#0fab9e] font-2xbold">DOM</span></h1>
              <p>Find Your RentSpace that Fit to your needs and Preferences.</p>

              
            </section>
        </main>
      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <div class="menu p-4 w-64 min-h-full bg-base-200 text-base-content flex flex-col justify-between bg-[#0d9488] ">
        <ul class="space-y-2">
            <li class="flex justify-center items-center text-center mb-10">
                <img src="../assets/images/logo-icon.png" class="w-[80px]  border border-white rounded-[20px] mb-3">
                <p class="nav_li  m-0 p-0 text-sm font-bold text-white">Dom joe</p>
            </li>
            <li>
                <a href="index.php" class="flex items-center gap-3 active">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" class="text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span class="text-white">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="accounts.php" class="flex items-center gap-3 active">
                 <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-plus-icon lucide-heart-plus"><path d="m14.479 19.374-.971.939a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5a5.2 5.2 0 0 1-.219 1.49"/><path d="M15 15h6"/><path d="M18 12v6"/></svg>
                <span class="text-white">My Favorite</span>
                </a>
            </li>
            <li>
                <a href="owners.php" class="flex items-center gap-3 active">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 24" class="text-white" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-plus-icon lucide-house-plus"><path d="M12.35 21H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 .71-1.53l7-6a2 2 0 0 1 2.58 0l7 6A2 2 0 0 1 21 10v2.35"/><path d="M14.8 12.4A1 1 0 0 0 14 12h-4a1 1 0 0 0-1 1v8"/><path d="M15 18h6"/><path d="M18 15v6"/></svg>
                <span class="text-white">Properties</span>
                </a>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                           
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-check-icon lucide-message-square-check"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.7.7 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="m9 11 2 2 4-4"/></svg>
                <span class="text-white">Messages</span>
                </a>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                           
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                <span class="text-white">My Account</span>
                </a>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                           
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                <span class="text-white">Notifications</span>
                </a>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                                                        
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                <span class="text-white">Landlord Centre</span>
                </a>
            </li>
            <div class="divider text-white"></div>
            <li>
                <a href="signout.php" class="flex items-center gap-3 active">                                                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                <span class="text-white">Sign Out</span>
                </a>
            </li>
        </ul>
      </div>
    </div>
  </div>

  <script src="./../assets/scripts/index.js"></script>
  <script src="./../assets/scripts/jquery.js"></script>
</body>
</html>


