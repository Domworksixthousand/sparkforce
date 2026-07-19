
      <?php  $current_page = basename($_SERVER['SCRIPT_NAME']); ?>
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
            <li class="relative">
                <button id="dropdownBtn1" class="w-full flex items-center justify-between gap-3 text-white active focus:outline-none">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" class="text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M16.051 12.616a1 1 0 0 1 1.909.024l.737 1.452a1 1 0 0 0 .737.535l1.634.256a1 1 0 0 1 .588 1.806l-1.172 1.168a1 1 0 0 0-.282.866l.259 1.613a1 1 0 0 1-1.541 1.134l-1.465-.75a1 1 0 0 0-.912 0l-1.465.75a1 1 0 0 1-1.539-1.133l.258-1.613a1 1 0 0 0-.282-.866l-1.156-1.153a1 1 0 0 1 .572-1.822l1.633-.256a1 1 0 0 0 .737-.535z"/><path d="M8 15H7a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/></svg>
                        <span>
                            Accounts
                            <div class="request_data indicator  ms-5">
                                <!--count-->
                            </div>
                        </span>
                    </div>
                    <svg id="arrowIcon1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" class="transition-transform duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <ul id="dropdownMenu1" class="hidden mt-2 ml-6 space-y-1 rounded-md p-2">
                    <li>
                        <a href="accounts.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Verified Accounts
                        </a>
                    </li>
                    <li>
                        <a href="request_accounts.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Pending Requests
                            <div class="request_data indicator  ms-5">
                                <!--count-->
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="disapproved_accounts.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Disapproved Accounts                     
                        </a>
                    </li>
                    <li>
                        <a href="blocked_accounts.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Blocked Accounts                   
                        </a>
                    </li>
                </ul>
            </li>
            <li class="relative">
                <button id="dropdownBtn2" class="w-full flex items-center justify-between gap-3 text-white active focus:outline-none">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 24" class="text-white" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-plus-icon lucide-house-plus"><path d="M12.35 21H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 .71-1.53l7-6a2 2 0 0 1 2.58 0l7 6A2 2 0 0 1 21 10v2.35"/><path d="M14.8 12.4A1 1 0 0 0 14 12h-4a1 1 0 0 0-1 1v8"/><path d="M15 18h6"/><path d="M18 15v6"/></svg>
                        <span>
                            Properties
                        </span>
                    </div>
                    <svg id="arrowIcon2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" class="transition-transform duration-200">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <ul id="dropdownMenu2" class="hidden mt-2 ml-6 space-y-1 rounded-md p-2">
                    <li>
                        <a href="accounts.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Verified Properties
                        </a>
                    </li>
                    <li>
                        <a href="pending_properties.php" class="block text-sm text-white hover:text-white py-1 px-2">
                            Pending Requests
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="reports.php" class="flex items-center gap-3 active">                           
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-flag-icon lucide-flag"><path d="M4 22V4a1 1 0 0 1 .4-.8A6 6 0 0 1 8 2c3 0 5 2 7.333 2q2 0 3.067-.8A1 1 0 0 1 20 4v10a1 1 0 0 1-.4.8A6 6 0 0 1 16 16c-3 0-5-2-8-2a6 6 0 0 0-4 1.528"/></svg>
                <span class="text-white">Reports</span>
                </a>
            </li>
            <div class="divider text-white"></div>
            <li>
                <a href="signout.php?location_back=<?php echo $current_page; ?>" class="flex items-center gap-3 active">                                                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" class="text-white" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                <span class="text-white">Sign Out</span>
                </a>
            </li>
        </ul>
      </div>



      