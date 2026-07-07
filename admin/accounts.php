<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
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
        <div class="flex-1 font-bold text-white">Accounts</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section>

            </section>
        </main>
      </div>
    </div>
    <div class="drawer-side z-40">
      <label for="my-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
      <div class="menu p-4 w-64 min-h-full bg-base-200 text-base-content flex flex-col justify-between bg-[#0d9488] ">
        <ul class="space-y-2">
            <li class="flex justify-center items-center text-center mb-10">
                <img src="../assets/images/logo-icon.png" class="w-[80px] ">
                <p class="nav_li  m-0 p-0 text-sm font-bold text-white">RP ADMIN</p>
            </li>
            <li>
                <a href="index.php" class="flex items-center gap-3 active">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" class="text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <span class="text-white">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="accounts.php" class="flex items-center gap-3 active">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" class="text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-star-icon lucide-user-star"><path d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z"/><path d="M8 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg>
                <span class="text-white">Accounts</span>
                </a>
            </li>
            <li>
                <a href="owners.php" class="flex items-center gap-3 active">
                   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 24" class="text-white" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-plus-icon lucide-house-plus"><path d="M12.35 21H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 .71-1.53l7-6a2 2 0 0 1 2.58 0l7 6A2 2 0 0 1 21 10v2.35"/><path d="M14.8 12.4A1 1 0 0 0 14 12h-4a1 1 0 0 0-1 1v8"/><path d="M15 18h6"/><path d="M18 15v6"/></svg>
                <span class="text-white">Owners</span>
                </a>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                           
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag-icon lucide-flag"><path d="M4 22V4a1 1 0 0 1 .4-.8A6 6 0 0 1 8 2c3 0 5 2 7.333 2q2 0 3.067-.8A1 1 0 0 1 20 4v10a1 1 0 0 1-.4.8A6 6 0 0 1 16 16c-3 0-5-2-8-2a6 6 0 0 0-4 1.528"/></svg>
                <span class="text-white">Reports</span>
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

