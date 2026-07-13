<?php
    include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE - Confirm Forgot Password</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <script src="assets/scripts/cool_alert.js"></script>
</head>
<body class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] min-h-screen  ">

    <!---alert-->
    <?php 
        include 'alerts.php'; 
    ?>
    
    <main>
        <section class="my-container flex justify-center items-center flex-col min-h-screen">
            <div class="flex flex-col justify-center items-center mb-5">
                <img src="assets/images/logo-icon.png" class="w-[60px] mb-5 drop-shadow-md" alt="RentSpace Logo">
                <h1 class="cursive-text text-white font-black tracking-wider text-[1rem] mb-4 drop-shadow-sm">
                    RENTSPACE
                </h1>
                <div class="w-16 h-[3px] bg-white/50 rounded-full mb-6"></div>
                <h2 class="text-white font-bold">CODE CONFIRMATION</h2>
            </div>
            
            <form action="functions.php" method="POST" class="w-full md:w-[500px] block box-border px-4 md:px-0">
                <!-- Username Field -->
                <div class="w-full block mb-4">
                    <p class="mb-2 text-white">Received Code </p>
                    <div class="input w-full flex items-center gap-2 box-border p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-code-icon lucide-code"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></svg>
                        <input type="text" class="grow w-full bg-transparent focus:outline-none text-black" name="code"  placeholder="Enter Code" required />
                    </div>
                </div>
                <div class="w-[100%]  mb-4">
                    <p class="mb-2 text-white">New Password</p>
                    <label class="input w-[100%]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <input type="password" class=" grow w-[100%]" id="password" name="password"   placeholder="Enter Password " required />
                        <img src="assets/images/hide-icon.png" class="cursor-pointer" id="pass" onclick="toggletPassword()">
                    </label>
                </div>
                 <div class="w-[100%] mb-4">
                        <p class="mb-2 text-white">Repeat Password </p>
                        <label class="input w-[100%]">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"  class="size-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input type="password" class=" grow w-[100%]" id="repeat_password" name="repeat_password"   placeholder="Enter Repeat Password" />
                            <img src="assets/images/hide-icon.png" id="repeat_pass" class="cursor-pointer" onclick="toggleRepeatPassword()">
                        </label>
                    </div>

              
                <div class="flex w-full gap-4 justify-between items-center box-border">
                    <button type="submit" name="confirm_forgot_pass" class="flex-1 min-w-0 bg-white hover:bg-gray-200 hover:text-black  transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                        Confirm
                    </button>
                    <button type="button" onclick="location.href='index.php'" class="flex-1 min-w-0 bg-gray-500 hover:bg-gray-400 text-white transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                        CANCEL
                    </button>
                </div>
            </form>
        </section>
    </main> 


    
    <script src="assets/scripts/index.js"></script>
    <script src="assets/scripts/address.js"></script>
</body>
</html>