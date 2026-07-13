<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE - Forgot Password</title>
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
                <h2 class="text-white font-bold">FORGOT PASSWORD</h2>
            </div>
            <form action="functions.php" method="POST" class="w-full md:w-[500px] block box-border px-4 md:px-0" id="myForm">
                <!-- Username Field -->
                <div class="w-full block mb-4">
                    <p class="mb-2 text-white">Email</p>
                    <div class="input w-full flex items-center gap-2 box-border p-2">
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
                        <input type="email" class="grow w-full bg-transparent focus:outline-none text-black" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" placeholder="Enter Email" required />
                    </div>
                </div>
                <div class="flex w-full gap-4 justify-between items-center box-border">
                    <button type="submit" name="forgot_password" class="flex-1 min-w-0 bg-white hover:bg-gray-200 hover:text-black  transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                        REQUEST CODE
                    </button>
                    <button type="button" onclick="location.href='index.php'" class="flex-1 min-w-0 bg-gray-500 hover:bg-gray-400 text-white transition-colors cursor-pointer p-2 rounded-[10px] font-semibold text-center border-0 block">
                        CANCEL
                    </button>
                </div>
            </form>
        </section>
    </main> 



    <script src="assets/scripts/email_animation.js"></script>
    <script src="assets/scripts/index.js"></script>
    <script src="assets/scripts/address.js"></script>
</body>
</html>