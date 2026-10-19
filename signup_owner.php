
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="u9XxwAs-OvAizH_6uuclWJ-izjdAxNuADcmPGo0UdQE" />
    <title>RENTHUB - Sign Up</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <script src="assets/scripts/cool_alert.js"></script>
    <script src="assets/scripts/face_api.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> 
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />  
</head>
<body class=" min-h-screen ">

    <!---alert-->
    <?php 
        include 'config.php';
        include 'alerts.php';
        include 'modal_terms_and_reg_signup_landlord.php';
        include 'modal_map.php';
        include 'requirements_landlord.php';
    ?>



    <main>
        <section class=" flex justify-between">
           <!--left-->
            <div class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] min-h-screen w-[40%] hidden md:flex flex flex-col justify-center items-center text-center p-8 sticky top-0 self-start">
                <img src="assets/images/logo-icon.png" class="w-[60px] mb-3 drop-shadow-md mb-5" alt="RentSpace Logo">
                <h1 class="cursive-text text-white font-black tracking-wider text-[1rem] mb-4 drop-shadow-sm">
                    RENTHUB
                </h1>
                <div class="w-16 h-[3px] bg-white/50 rounded-full mb-6"></div>
                <p class="text-white text-base md:text-lg font-medium leading-relaxed max-w-sm opacity-95">
                    Your trusted partner in listing premium properties and finding your perfect rental space.
                </p>
            </div>
           <!--right-->
           <form action="functions.php" method="POST" id="myForm" class="min-h-screen w-[100%] py-[20px] lg:py-[100px] px-[10px] lg:px-[150px] " enctype="multipart/form-data">
                <input type="hidden" id="lat" name="latitude" value="<?php echo $_SESSION['latitud'] ?? ';' ?>" readonly required placeholder="Loading...">
                <input type="hidden" id="lng" value="<?php echo $_SESSION['longitude'] ?? ';' ?>" name="longitude" readonly required placeholder="Loading...">
                <div class="flex flex-col  justify-center items-center  mb-[50px]">
                    <img src="assets/images/logo-icon.png" class="w-[60px]  drop-shadow-md mb-5 text md:hidden" alt="RentSpace Logo">
                    <h2 class="text-[30px] font-bold ">CREATE ACCOUNT</h2>
                    <small class="text-gray-500 mb-1">Create account as Landlord</small>
                    <div class="w-16 h-[3px] bg-[#14b8a6]/90 rounded-full mb-6"></div>
                </div>
                <p class="mb-3">Personal Information</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Last Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="lastname" value="<?php echo $_SESSION['lastname'] ?? ''; ?>" placeholder="Enter Last Name" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">First Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                            <input type="text" class="autoInput grow w-[100%]" name="firstname"  value="<?php echo $_SESSION['firstname'] ?? ''; ?>" placeholder="Enter First Name" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Middle Name </p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="middlename"  value="<?php echo $_SESSION['middlename'] ?? ''; ?>" placeholder="Enter Middle Name (Make it blank if None)" />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Suffix </p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="suffix"  value="<?php echo $_SESSION['suffix'] ?? ''; ?>" placeholder="Enter Suffix (Make it blank if None)" />
                        </label>
                    </span>
                </div>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Email Address *</p>
                        <label class="input w-[100%]">
                            <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            stroke-width="2.5"
                            fill="none"
                            stroke="currentColor"
                            >
                            <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </g>
                        </svg>
                            <input type="email" class=" grow w-[100%]" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" placeholder="Enter Email" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Contact Number *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                           <input 
                                type="text" 
                                id="contactNumber"
                                class="number_only grow w-[100%]" 
                                name="contact_number"  
                                value="<?php echo $_SESSION['contact_number'] ?? ''; ?>" 
                                placeholder="Contact Number (e.g., 09123456789)" 
                                maxlength="11"
                                required 
                            />
                        </label>
                    </span>
                </div>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Province *</p>
                        <label class="input w-[100%]  ">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <select name="province" id="province" onchange="updateMunicipalities()" class="select  w-[100%] border-0 h-fit cursor-pointer" required>
                                <option value="<?php echo $_SESSION['province'] ?? 'Select'; ?>"><?php echo $_SESSION['province'] ?? 'Select' ?></option>
                                <option value="Albay">Albay</option>
                                <option value="CamarinesNorte"> Camarines Norte</option>
                                <option value="CamarinesSur"> Camarines Sur</option>
                                <option value="Catanduanes">Catanduanes</option>
                                <option value="Masbate">Masbate</option>
                                <option value="Sorsogon">Sorsogon</option>
                            </select>
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Municipality  *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <select  name="municipality" id="municipality" onchange="updateBarangays()" class="select  w-[100%] border-0 h-fit cursor-pointer" required>
                                <option value="<?php echo $_SESSION['municipality'] ?? 'Select'; ?>"><?php echo $_SESSION['municipality'] ?? 'Select'; ?></option>
                            </select>
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Barangay *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <select name="barangay"  id="barangay" class="select  w-[100%] border-0 h-fit cursor-pointer" required>
                                    <option value="<?php echo $_SESSION['barangay'] ?? 'Select'; ?>"><?php echo $_SESSION['barangay'] ?? 'Select'; ?></option>
                            </select>
                        </label>
                    </span>
                     <span class="w-[100%]">
                        <p class="mb-2 text-sm">ZIP Code *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <input type="text" class="grow w-[100%]" name="zipcode" value="<?php echo $_SESSION['zipcode'] ?? ''; ?>" id="zipcode" readonly>
                        </label>
                    </span>
                </div>
                <p class="mb-3">Account Information</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Username *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>" placeholder="Enter Username" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Password *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="password" name="password"  value="<?php echo $_SESSION['password'] ?? ''; ?>" placeholder="Enter Password " required />
                            <img src="assets/images/hide-icon.png" class="cursor-pointer" id="pass" onclick="togglePasswords()">
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Repeat Password *</p>
                        <label class="input w-[100%]">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="repeat_password" name="repeat_password"  value="<?php echo $_SESSION['repeat_password'] ?? ''; ?>" placeholder="Enter Repeat Password" />
                            <img src="assets/images/hide-icon.png" id="repeat_pass" class="cursor-pointer" onclick="toggleRepeatPassword()">
                        </label>
                    </span>
                </div>
                <p class="mb-3">Verification Information</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">ID Type *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="id_type" value="<?php echo $_SESSION['id_type'] ?? ''; ?>" placeholder="Enter ID Type" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">ID Number *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class=" grow w-[100%]" id="id_number" name="id_number"  value="<?php echo $_SESSION['id_number'] ?? ''; ?>" placeholder="Enter ID Number " required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">ID Photo *</p>                       
                        <?php if (isset($_SESSION['id_photo_name'])): ?>
                            <input type="hidden" name="old_id_photo" value="<?php echo htmlspecialchars($_SESSION['id_photo_name']); ?>">
                            <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                                <span> old upload: <strong class="underline"><?php echo htmlspecialchars($_SESSION['id_photo_name']); ?></strong></span>
                                <span class="badge badge-success text-white">Saved</span>
                            </div>
                        <?php endif; ?>
                        <label class="input w-[100%] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            
                            <input 
                                type="file" 
                                class="file-input grow w-[100%]" 
                                id="id_photo" 
                                name="id_photo" 
                                accept="image/jpeg, image/jpg" 
                                <?php echo isset($_SESSION['id_photo_name']) ? '' : 'required'; ?> 
                            />
                        </label>
                    </span>
                    <span class="w-[100%]">
                    <p class="mb-2 text-sm">Selfie*</p>
                    <?php if (isset($_SESSION['selfie_photo_name'])): ?>
                        <input type="hidden" name="old_selfie_photo" value="<?php echo htmlspecialchars($_SESSION['selfie_photo_name']); ?>">
                        <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs flex items-center justify-between rounded-lg">
                            <span>old upload: <strong class="underline"><?php echo htmlspecialchars($_SESSION['selfie_photo_name']); ?></strong></span>
                            <span class="badge badge-success text-white">Saved</span>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="selfieInput" name="selfie_photo" class=" file-input grow w-[100%] mb-2 select-none " >
                    <div class="text-end">
                        <button type="button" class="btn btn-success text-white btn-sm " onclick="selfie_modal.showModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera-icon lucide-camera"><path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/><circle cx="12" cy="13" r="3"/></svg>
                            Take Selfie
                        </button>
                    </div>
                    <canvas id="snapshot" width="640" height="480" class="hidden"></canvas>
                </span>
                </div>
                <p class="mb-3 text-sm">Other Informations</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Occupation *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="occupation" value="<?php echo $_SESSION['occupation'] ?? ''; ?>" placeholder="Enter Occupation " required />
                        </label>
                    </span>
                </div>
                 <p class="mb-3  ">Property Ownership & Media</p>
                  <div class="flex flex-col lg:flex-row gap-3 mb-5">
                      <!-- Photo Gallery -->
                    <span class="w-[100%]">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-semibold">Property Photo Gallery *</p>
                            <?php if (isset($_SESSION['photo_gallery']) && is_array($_SESSION['photo_gallery']) && !empty($_SESSION['photo_gallery'])): ?>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-medium border border-green-200">
                                    ✓ Already Uploaded (<?php echo count($_SESSION['photo_gallery']); ?> photo/s)
                                </span>
                            <?php endif; ?>
                        </div>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-[100%]" id="photo_gallery" name="photo_gallery[]" multiple accept="image/jpeg, image/jpg" <?php echo isset($_SESSION['photo_gallery']) ? '' : 'required'; ?> />
                        </label>
                        
                    </span>
                    <!-- Proof of Ownership -->
                    <span class="w-[100%]">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-sm font-semibold">Proof of Ownership Document *</p>
                            <?php if (isset($_SESSION['owner_docs']) && is_array($_SESSION['owner_docs']) && !empty($_SESSION['owner_docs'])): ?>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-medium border border-green-200">
                                    ✓ Already Uploaded (<?php echo count($_SESSION['owner_docs']); ?> file/s)
                                </span>
                            <?php endif; ?>
                        </div>
                        <label class="input w-[100%] mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-[100%]" id="owner_docs" name="owner_docs[]" multiple accept="image/jpeg, image/jpg" <?php echo isset($_SESSION['owner_docs']) ? '' : 'required'; ?> />
                        </label>
                        <div class="flex justify-end mb-5">
                            <button type="button" class="btn btn-success text-white flex" onclick="requirements_modal.showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text preview-icon"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                Documents requirements
                            </button>
                        </div>
                    </span>

                  
                  </div>
                  <p class="mb-3  ">Property Location</p>  
                  <div class="flex flex-col lg:flex-row gap-3 mb-1">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Province *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class=" text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="province1" 
                                name="province1"  
                                readonly   
                                value="<?php echo $_SESSION['province1'] ?? '' ?>"                
                            />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Municipality *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class=" text-gray-500"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="municipality1" 
                                name="municipality1"  
                                readonly   
                              value="<?php echo $_SESSION['municipality1'] ?? '' ?>"
                            />
                        </label>
                    </span>
                     <span class="w-[100%]">
                        <p class="mb-2 text-sm">Barangay *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
                            
                            <input 
                                type="text" 
                                class="file-input grow w-[100%]" 
                                id="barangay1" 
                                name="barangay1"  
                                readonly   
                                  value="<?php echo $_SESSION['barangay1'] ?? '  ' ?>"                 
                            />
                        </label>
                    </span>
                  </div>
                  <div class="flex justify-end mb-5">
                    <button type="button" class="btn btn-success text-white flex" onclick="my_modal_maps.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pinned-icon lucide-map-pinned"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                    Locate
                  </button>
                  </div>
                   <p class="mb-3  ">Property Information</p>  
                  <div class="flex flex-col lg:flex-row gap-3 mb-10">
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Type of Property *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-land-plot-icon lucide-land-plot"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                            <select class="select cursor-pointer w-[100%]" name="property_type">
                              <option value="<?php echo $_SESSION['property_type'] ?? 'Select Property type' ?>"><?php echo $_SESSION['property_type'] ?? 'Select Property type' ?></option>
                              <option value="Boarding House / Bedspace">Boarding House / Bedspace</option>
                              <option value="Apartment">Apartment</option>
                              <option value="Condominium">Condominium (Condo)</option>
                              <option value="House">House (Single-Family, Townhouse, Duplex)</option>
                              <option value="Commercial Space">Commercial Space (Retail, Shophouse, Cloud Kitchen)</option>
                              <option value="Event Space">Event Space (Function Hall, Venue)</option>
                              <option value="Transient House">Transient House (Vacation Rental, Airbnb)</option>
                              <option value="Parking Space">Parking Space (Garage, Carport)</option>
                              <option value="Vacant Lot">Vacant Lot (Residential, Commercial, Agricultural)</option>
                            </select>
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2 text-sm">Property Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" class=" text-gray-500"  height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-land-plot-icon lucide-land-plot"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
                            <input 
                                type="text" 
                                class="autoInput grow w-[100%]" 
                                id="property_name" 
                                name="property_name"  
                                value="<?php echo $_SESSION['property_name'] ?? ''; ?>"   
                                placeholder="Enter Property Name"                  
                            />
                        </label>
                    </span>
                  </div>
                <div class="mb-8 p-4 bg-base-200/50 border border-base-300 rounded-xl">
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms_agree" 
                            name="terms_agree" 
                            class="checkbox checkbox-primary me-3 mt-1 size-5 shrink-0" 
                            <?= (isset($_SESSION['terms_agree']) && $_SESSION['terms_agree'] == 1) ? 'checked' : ''; ?>
                            required 
                        />
                        <label for="terms_agree" class="text-sm text-gray-600 leading-relaxed cursor-pointer select-none">
                            I certify that all details and identity verification documents provided are accurate. 
                            I explicitly agree to the 
                            <span class="text-primary font-semibold underline cursor-pointer" onclick="policy_modal.showModal()">Terms of Service, Privacy Policy, and Platform Ban Regulations</span>. *
                        </label>
                    </div>
                </div>
                <div class="text-center">
                    <div class="flex gap-3 flex-col md:flex-row justify-center mb-3">
                        <button type="submit" name="register" class="btn bg-[#0d9488] hover:bg-success text-white rounded-[10px]  py-[20px] px-[10px] w-[100%] md:w-[30%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 ">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            SIGN UP 
                        </button>
                        <button type="button" onclick="location.href='index.php'" class="btn bg-gray-500 hover:bg-gray-400 text-white rounded-[10px]  py-[20px] px-[10px]  w-[100%] md:w-[30%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 ">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            CANCEL
                        </button>
                    </div>
                    <div class="divider">OR</div>
                    <p>
                        <a href="forgot_password.php" class="link text-primary">Forgot Password?</a>
                        /
                        <a href="signin.php" class="link text-primary">Already have Account?</a>
                         /
                        <a href="signup.php" class="link text-primary">Sign up as Tenant</a>
                    </p>
                </div>
           </form>
        </section>
    </main>


        <!--modal selfie-->
    <dialog id="selfie_modal" class="modal">
        <div class="modal-box bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div class="form-group">
                <label class="block font-bold mb-2 text-white">Take a Selfie (Requirement):</label>
                <div class="relative w-full max-w-[640px] mx-auto overflow-hidden rounded-lg bg-black" id="camArea">
                    <video id="webcam" autoplay muted playsinline class="w-full h-auto block -scale-x-100"></video>
                    <div id="guide" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-1/2 h-[75%] border-2 border-dashed border-rose-500 rounded-[50%] pointer-events-none shadow-[0_0_0_9999px_rgba(0,0,0,0.5)] transition-all duration-200">
                        <div id="eyeLine" class="absolute top-[35%] left-0 w-full border-t border-dashed border-rose-500/60 transition-all duration-200"></div>
                        <div id="crossHairV" class="absolute top-0 left-1/2 h-full border-l border-dashed border-rose-500/40 -translate-x-1/2 transition-all duration-200"></div>
                    </div>
                </div>
                
                <!-- Selfie Preview -->
                <img id="preview" alt="Selfie Preview" class="w-full max-w-[640px] h-auto mx-auto rounded-lg -scale-x-100" style="display:none;">
                
                <!-- Status and Button -->
                <div id="status" class="my-3 text-center font-semibold text-white">Loading....</div>
               <div class="text-center">
                <button type="button" id="captureBtn" class="btn bg-white  text-black " disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-aperture-icon lucide-aperture"><circle cx="12" cy="12" r="10"/><path d="m14.31 8 5.74 9.94"/><path d="M9.69 8h11.48"/><path d="m7.38 12 5.74-9.94"/><path d="M9.69 16 3.95 6.06"/><path d="M14.31 16H2.83"/><path d="m16.62 12-5.74 9.94"/></svg>
                    CAPTURE
                </button>
               </div>
            </div>
        </div>
    </dialog>

  


  

      <!--leaflet script-->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/bundle.min.js"></script>
    <script src="assets/scripts/email_animation.js"></script>
    <script src="assets/scripts/index.js"></script>
    <script src="assets/scripts/address.js"></script>
    <script src="assets/scripts/selfie_script.js"></script>
    <script src="assets/scripts/map.js"></script>
    <script src="assets/scripts/query_filter.js"></script>
</body>
</html>
  


  

   
