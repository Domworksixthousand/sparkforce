<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE - signin</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>

</head>
<body class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] min-h-screen  ">
    
<main>
    <section class="my-container flex justify-center items-center flex-col min-h-screen">
        <div class="flex flex-col justify-center items-center">
            <img src="assets/images/logo-icon.png" class="w-[60px] mb-5 drop-shadow-md" alt="RentSpace Logo">
            <h1 class="cursive-text text-white font-black tracking-wider text-[1rem] mb-4 drop-shadow-sm">
                RENTSPACE
            </h1>
            <div class="w-16 h-[3px] bg-white/50 rounded-full mb-6"></div>
        </div>
        
        <form class="w-full md:w-[500px] block box-border px-4 md:px-0">
            <!-- Username Field -->
            <div class="w-full block mb-4">
                <p class="mb-2 text-white">Username</p>
                <div class="input w-full flex items-center gap-2 box-border p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <input type="text" class="grow w-full bg-transparent focus:outline-none text-black" name="username" value="<?php echo $_SESSION['username'] ?? ''; ?>" placeholder="Enter Username" required />
                </div>
            </div>

            <!-- Password Field -->
            <div class="w-full block mb-8">
                <p class="mb-2 text-white">Password</p>
                <div class="input w-full flex items-center gap-2 box-border p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <input type="password" class="grow w-full bg-transparent focus:outline-none text-black" id="password" name="password" value="<?php echo $_SESSION['password'] ?? ''; ?>" placeholder="Enter Password" required />
                    <img src="assets/images/view-icon.png" class="cursor-pointer size-5 shrink-0" id="pass" onclick="togglePassword()">
                </div>
                <div class="text-end mt-2">
                    <a href="" class="text-white link ">Forgot Password?</a>
                </div>
            </div>
            <div class="flex w-full gap-4 justify-between items-center box-border">
                <button type="submit" class="flex-1 min-w-0 bg-white hover:bg-gray-200 hover:text-black  transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                    SIGN IN
                </button>
                <button type="button" onclick="location.href='index.php'" class="flex-1 min-w-0 bg-gray-500 hover:bg-gray-400 text-white transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                    CANCEL
                </button>
            </div>
            <div class="divider text-white">OR</div>
            <p class="text-center"><a href='signup.php' class="link text-white ">Don't have account yet?</a></p>
        </form>
    </section>
</main> 



    <script src="assets/scripts/index.js"></script>
    <script src="assets/scripts/address.js"></script>
</body>
</html>