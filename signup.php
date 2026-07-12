<?php
    include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE - Sign Up</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <script src="assets/scripts/cool_alert.js"></script>

</head>
<body class=" min-h-screen ">

    <!---alert-->
    <?php 
        include 'alerts.php'; 
        include 'loading_animation.php';
    ?>






    <main>
        <section class=" flex justify-between">
           <!--left-->
            <div class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] min-h-screen w-[40%] hidden md:flex flex flex-col justify-center items-center text-center p-8 sticky top-0 self-start">
                <img src="assets/images/logo-icon.png" class="w-[60px] mb-3 drop-shadow-md mb-5" alt="RentSpace Logo">
                <h1 class="cursive-text text-white font-black tracking-wider text-[1rem] mb-4 drop-shadow-sm">
                    RENTSPACE
                </h1>
                <div class="w-16 h-[3px] bg-white/50 rounded-full mb-6"></div>
                <p class="text-white text-base md:text-lg font-medium leading-relaxed max-w-sm opacity-95">
                    Your trusted partner in listing premium properties and finding your perfect rental space.
                </p>
            </div>
           <!--right-->
           <form action="functions.php" method="POST" id="myForm" class="min-h-screen w-[100%] py-[20px] lg:py-[100px] px-[10px] lg:px-[150px] " enctype="multipart/form-data">
                <div class="flex flex-col gap-3 justify-center items-center ">
                    <img src="assets/images/logo-icon.png" class="w-[60px] mb-3 drop-shadow-md mb-5 text md:hidden" alt="RentSpace Logo">
                    <h2 class="text-xl font-bold mb-[50px]">CREATE ACCOUNT</h2>
                </div>
                <p class="mb-3">Personal Information</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2">Last Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="lastname" value="<?php echo $_SESSION['lastname'] ?? ''; ?>" placeholder="Enter Last Name" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">First Name *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>

                            <input type="text" class="autoInput grow w-[100%]" name="firstname"  value="<?php echo $_SESSION['firstname'] ?? ''; ?>" placeholder="Enter First Name" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">Middle Name </p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="middlename"  value="<?php echo $_SESSION['middlename'] ?? ''; ?>" placeholder="Enter Middle Name (Make it blank if None)" />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">Suffix </p>
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
                        <p class="mb-2">Email Address *</p>
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
                        <p class="mb-2">Contact Number *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                           <input 
                                type="text" 
                                id="contactNumber"
                                class="grow w-[100%]" 
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
                        <p class="mb-2">Province *</p>
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
                        <p class="mb-2">Municipality  *</p>
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
                        <p class="mb-2">Barangay *</p>
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
                        <p class="mb-2">ZIP Code *</p>
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
                        <p class="mb-2">Username *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>" placeholder="Enter Username" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">Paassword *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="password" name="password"  value="<?php echo $_SESSION['password'] ?? ''; ?>" placeholder="Enter Password " required />
                            <img src="assets/images/hide-icon.png" class="cursor-pointer" id="pass" onclick="toggletPassword()">
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">Repeat Password *</p>
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
                        <p class="mb-2">ID Type *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="id_type" value="<?php echo $_SESSION['id_type'] ?? ''; ?>" placeholder="Enter ID Type" required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">ID Number *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class=" grow w-[100%]" id="id_number" name="id_number"  value="<?php echo $_SESSION['id_number'] ?? ''; ?>" placeholder="Enter ID Number " required />
                        </label>
                    </span>
                    <span class="w-[100%]">
                        <p class="mb-2">ID Photo *</p>
                        
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
                </div>
                <p class="mb-3">Other Informations</p>
                <div class="w-[100%] flex flex-col lg:flex-row gap-3 mb-5">
                    <span class="w-[100%]">
                        <p class="mb-2">Occupation *</p>
                        <label class="input w-[100%]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                            </svg>
                            <input type="text" class="autoInput grow w-[100%]" name="occupation" value="<?php echo $_SESSION['occupation'] ?? ''; ?>" placeholder="Enter Occupation " required />
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
                    <p><a href="forgot_password.php" class="link">Forgot Password?</a> / <a href="signin.php" class="link">Already have Account?</a></p>
                </div>
           </form>
        </section>
    </main>



    <!--modal-->
    <dialog id="policy_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl bg-base-100">
        <h3 class="text-lg font-bold text-error flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            RENTSPACE Terms, Privacy & Account Ban Policy
        </h3>
        <div class="space-y-4 text-sm text-gray-600 max-h-[60vh] overflow-y-auto pr-2">
            <div>
                <h4 class="font-bold text-base-content mb-1">1. Truthful Information & Account Accuracy</h4>
                <p>The user promises that their complete name, address, and login credentials are real. Fake accounts, duplicate profiles, or falsified profile information are strictly prohibited.</p>
            </div>
            
            <div>
                <h4 class="font-bold text-base-content mb-1">2. Data & Identity Verification</h4>
                <p>By uploading an ID Type, ID Number, and ID Photo, the user gives RENTSPACE the explicit right to process and verify their identity. This data is handled securely and used solely to protect the platform against rental fraud, scams, and identity theft.</p>
            </div>
            
            <div class="p-3 bg-error/10 border border-error/20 rounded-lg">
                <h4 class="font-bold text-error mb-1">3. Immediate Account Ban for Violations</h4>
                <p class="mb-2">RENTSPACE enforces a strict zero-tolerance policy for illegal, malicious, or deceptive behaviors. Your account will be **permanently banned, suspended, or terminated immediately without prior notice** if caught engaging in:</p>
                <ul class="list-disc list-inside space-y-1 ml-2 font-medium">
                    <li>Posting fake rental listings or misleading property details.</li>
                    <li>Financial scams, payment fraud, extortion, or phishing.</li>
                    <li>Uploading fake, heavily modified, or stolen government identification cards.</li>
                    <li>Violating local housing laws, tenancy regulations, or community safety rules.</li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-base-content mb-1">4. Liability & Platform Rights</h4>
                <p>The user assumes full responsibility for all activities, bookings, and messages sent through their account. If an account is banned for a policy violation, the user forfeits access to their profile and any active listings. RENTSPACE reserves the right to report fraudulent activities directly to local law enforcement.</p>
            </div>
        </div>
        <div class="modal-action border-t border-base-300 pt-3">
        <form method="dialog">
            <button class="btn btn-sm btn-ghost mr-2">Close</button>
            <button class="btn btn-sm btn-error text-white" onclick="document.getElementById('terms_agree').checked = true">I Understand & Agree</button>
        </form>
        </div>
    </div>
    </dialog>

    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/chaoticOrbit.js"></script>
    <script src="assets/scripts/index.js"></script>
    <script src="assets/scripts/address.js"></script>
</body>
</html>


