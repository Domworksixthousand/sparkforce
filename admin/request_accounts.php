
<?php
  include '../config.php'; 
  if(!isset($_SESSION['admin_login'])){
    echo "<script>location.href='../index.php';</script>";
  }
 ?>


 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
</head>
<body class="bg-base-100">

  <!---alert-->
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
        <div class="flex-1 font-bold text-white">Request Accounts</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section class="my-container">
                <div class="flex justify-end items-end mb-5  w-[100%]">
                   <label class="input validator  w-[100%] lg:w-[30%]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>
                        <input type="text" placeholder="Type" class="input w-[100%]"  />
                    </label>
                </div>
                <div class="flex items-center gap-2 mb-10">
                    <select class="select w-fit">
                        <option value="8">8</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="All">All</option>
                    </select>
                    <p>Entiries per Page</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <!-- head -->
                        <thead>
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                            </th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Favorite Color</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- row 1 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                            </th>
                            <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                <div class="mask mask-squircle h-12 w-12">
                                    <img
                                    src="https://img.daisyui.com/images/profile/demo/2@94.webp"
                                    alt="Avatar Tailwind CSS Component" />
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">Hart Hagerty</div>
                                <div class="text-sm opacity-50">United States</div>
                                </div>
                            </div>
                            </td>
                            <td>
                            Zemlak, Daniel and Leannon
                            <br />
                            <span class="badge badge-ghost badge-sm">Desktop Support Technician</span>
                            </td>
                            <td>Purple</td>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 2 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                            </th>
                            <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                <div class="mask mask-squircle h-12 w-12">
                                    <img
                                    src="https://img.daisyui.com/images/profile/demo/3@94.webp"
                                    alt="Avatar Tailwind CSS Component" />
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">Brice Swyre</div>
                                <div class="text-sm opacity-50">China</div>
                                </div>
                            </div>
                            </td>
                            <td>
                            Carroll Group
                            <br />
                            <span class="badge badge-ghost badge-sm">Tax Accountant</span>
                            </td>
                            <td>Red</td>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 3 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                            </th>
                            <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                <div class="mask mask-squircle h-12 w-12">
                                    <img
                                    src="https://img.daisyui.com/images/profile/demo/4@94.webp"
                                    alt="Avatar Tailwind CSS Component" />
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">Marjy Ferencz</div>
                                <div class="text-sm opacity-50">Russia</div>
                                </div>
                            </div>
                            </td>
                            <td>
                            Rowe-Schoen
                            <br />
                            <span class="badge badge-ghost badge-sm">Office Assistant I</span>
                            </td>
                            <td>Crimson</td>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        <!-- row 4 -->
                        <tr>
                            <th>
                            <label>
                                <input type="checkbox" class="checkbox" />
                            </label>
                            </th>
                            <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                <div class="mask mask-squircle h-12 w-12">
                                    <img
                                    src="https://img.daisyui.com/images/profile/demo/5@94.webp"
                                    alt="Avatar Tailwind CSS Component" />
                                </div>
                                </div>
                                <div>
                                <div class="font-bold">Yancy Tear</div>
                                <div class="text-sm opacity-50">Brazil</div>
                                </div>
                            </div>
                            </td>
                            <td>
                            Wyman-Ledner
                            <br />
                            <span class="badge badge-ghost badge-sm">Community Outreach Specialist</span>
                            </td>
                            <td>Indigo</td>
                            <th>
                            <button class="btn btn-ghost btn-xs">details</button>
                            </th>
                        </tr>
                        </tbody>
                        <!-- foot -->
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Job</th>
                            <th>Favorite Color</th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
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
  <script src="./../assets/scripts/jquery.js"></script>
</body>
</html>
