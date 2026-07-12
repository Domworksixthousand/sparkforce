<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="assets/scripts/cool_alert.js"></script>
    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
</head>
<body >

  <!---alert-->
  <?php 
      include 'alerts.php'; 
  ?>


<!--header ini-->
  <header class=" relative ">
    <nav
     class="navbar  lg:container  lg:px-[30px]   glass fixed top-0 left-1/2 -translate-x-1/2 z-50  transition-all mt-0  lg:mt-[20px] rounded-0 lg:rounded-lg">
      <div class="navbar-start">
        <div class="dropdown">
          <div tabindex="0" role="button" class="btn btn-ghost lg:hidden " aria-label="Toggle Navigation">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </div>
          <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-3 shadow-xl bg-base-100 rounded-box w-64 gap-2 border border-base-200">
            <li><a href="#properties">Properties</a></li>
            <li><a href="#aboutus">About Us</a></li>
            <li><a href="#works">How It Works</a></li>
            <div class="divider my-1"></div>
            <li><a href="signin.php" class="btn btn-ghost btn-sm">Sign In</a></li>
            <li><a href="signup.php" class="btn bg-[#14b8a6] btn-sm text-white">Get Started</a></li>
          </ul>
        </div>
        <a href="#" class="  flex flex-row">
          <img src="assets/images/logo-icon.png" class="w-[50px] me-2">
          <div class="flex flex-col mt-2">  
            <p class="nav_li cursive-text m-0 p-0 text-sm font-bold text-white">RENTSPACE</p>
            <p class="nav_li m-0 p-0 text-sm text-white">Find Your Places</p>
          </div>
        </a>
      </div>
      <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-1 text-[15px] font-medium text-base-content/80">
          <li><a href="#properties" class="nav_li hover:text-success text-white transition-colors">Properties</a></li>
          <li><a href="#aboutus" class="nav_li hover:text-success transition-colors text-white">About Us</a></li>
          <li><a href="#works" class=" nav_li hover:text-success text-white transition-colors">How It Works</a></li>
        </ul>
      </div>
      <div class="navbar-end gap-2 ">
        <a href="signin.php" class="nav_li btn btn-ghost p-[7px] rounded-sm text-sm bg-transparent text-white hover:bg-[#14b8a6] btn-sm hidden lg:inline-flex font-semibold border-0">
          Sign In
        </a>
        <a href="signup.php" class="btn bg-[#14b8a6] border-0  shadow-md shadow-primary/20 text-white font-bold px-5 btn-sm hidden lg:inline-flex ">
          Get Started
        </a>
      </div>
    </nav>
    <div class="banner w-full m-0 p-0 relative">
        <div class="absolute inset-0 bg-black/50 z-10 pointer-events-none"></div>
        <div class="absolute inset-0 z-20 flex items-center justify-center text-white px-4">
          <div class="container max-w-3xl flex flex-col items-center text-center gap-4">
            <p class="text-[#47e5d4] p-3 rounded-full glass font-semibold tracking-wide text-sm  bg-[#163b43]">
              Find Your Perfect Stay
            </p> 
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
              Discover Your Ideal Rental Space
            </h1>
            <p class="text-gray-200 text-base md:text-lg max-w-2xl font-light">
              From cozy boarding houses to luxury apartments — browse thousands of verified rental properties tailored to your needs and budget.
            </p>
            <form method="GET" action="" class="bg-white mt-[20px] rounded-[20px] w-[100%] py-[23px] px-[18px] ">
              <div class="flex flex-col lg:flex-row mb-3">
                <div class="text-start w-[100%] lg:me-3 mb-3 lg:mb-0 ">
                    <span class="flex  flex-col lg:flex-row">
                    <input type="text" name="min" class="numbers_only input border-gray-300  mb-3 lg:mb-0 lg:me-3 w-[100%] rounded-[10px]  placeholder:text-black text-black" placeholder="Price Min">
                    <input type="text" name="min" class="numbers_only input  border-gray-300 w-[100%] placeholder:text-black rounded-[10px] text-black" placeholder="Price Max">
                    </span>
                </div>
                <div class="text-start w-[100%]">
                    <span>
                    <select class="select w-[100%]  text-black rounded-[10px]" >
                        <option>Property Type</option>
                        <option>asda</option>
                    </select>
                    </span>
                </div>
              </div>
              <div>
                <button type="submit" name="" class="btn bg-[#0d9488] w-[100%] rounded-[10px] text-white">
                    <img src="assets/images/magnifier-icon.png " class="w-[20px]"> 
                    Filter
                </button>
              </div>
            </form>
          </div>
        </div>
        <img src="assets/images/banner-img.jpg" class="w-full object-cover block m-0 p-0 h-[50rem] ">
    </div>
  </header>


  <main>
    <section class="my-container py-[100px] px-[10px] lg:px-[0px]" id="properties">
      <div class="flex justify-center items-center flex-col   w-[100%]">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Property Categories</p>
        <h1 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-4">Find the Perfect Property Type</h1>
        <p>Whether you need a short-term stay or long-term rental, browse through our diverse range of verified property types.</p>
      </div>
      <div>
        <h1>sadsada</h1>
      </div>
    </section>
    <section class="my-container py-[100px] px-[10px] lg:px-[0px]" >
      <div class="flex justify-center items-center lg:justify-start lg:items-start flex-col   w-[100%]">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Featured Properties</p>
        <h1 class="font-bold text-[1.5rem]  w-fit mb-4">Hand-Picked Rental Spaces</h1>
        <p>Top-rated properties curated for quality and comfort</p>
      </div>
      <div>
        <h1>sadsada</h1>
      </div>
    </section>
    <section class=" py-[100px] px-[10px] lg:px-[0px] bg-gray-100" id="aboutus" >
      <div class="my-container">
        <div class="flex flex-col justify-center items-center">
          <h2 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-15">About Us</h2>
          <div class="flex flex-col lg:flex-row">
            <div class="w-[100%] p-[50px] lg:p-[100px] bg-[#0d9488] rounded-[20px]">
              <div class="flex items-center justify-center  lg:justify-start mb-5 flex-col lg:flex-row">
                <img src="assets/images/security.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Verified Listings</p>
                  <p class=" text-white">Every property is personally inspected before going live.</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/check.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">User Friendly</p>
                  <p class=" text-white">Easy Navigation and Filtering</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/time.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Quick Response</p>
                  <p class=" text-white">Admin Quick Response</p>
                </span>
              </div>
              <div class="flex items-center justify-center lg:justify-start  mb-5 flex-col lg:flex-row">
                <img src="assets/images/laugh.png" class=" mb-2 lg:mb-0 rounded-[10px] h-fit me-0 lg:me-5 bg-emerald-800 p-3">
                <span class="text-center lg:text-start">
                  <p class="font-bold text-white  mb-2 lg:mb-0">Happy</p>
                  <p class=" text-white">Tenants and Owners are satisfied</p>
                </span>
              </div>
            </div>
            <div class="w-[100%] p-[30px]">
              <p class="leading-relaxed mb-3">
                  RentSpace was born from a simple yet powerful idea — finding a rental property should not feel like a gamble. In a market often clouded by unverified listings, hidden fees, and unreliable arrangements, we recognized a critical need for change. We envisioned and built a modern platform where security meets convenience; a space where every listing is strictly verified, every price point is fully transparent, and every single renter feels absolute confidence and peace of mind before they even step foot inside their future home.
              </p>
              
              <p class="leading-relaxed mb-3">
                  Our platform bridges the gap between diverse lifestyles and the perfect spaces. From university students seeking safe, affordable, and study-friendly boarding houses near their campuses, to growing families searching for long-term, comfortable townhouses to establish their roots, we passionately connect people with environments that truly feel like home. We understand that a rental is not just a structural space; it is where your daily life unfolds, and we take that responsibility seriously.
              </p>

              <p class="leading-relaxed">
                  What truly sets RentSpace apart is our unwavering commitment to quality assurance and community trust. Our dedicated team goes beyond just hosting online advertisements; we actively review properties, verify landlord credentials, and perform rigorous quality checks to eliminate scams and misleading posts. By streamlining communication and securing data transactions, we ensure a seamless, hassle-free booking experience from the very first search click up to the exciting moment of your move-in day.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="my-container py-[100px] px-[10px] lg:px-[0px] " id="works" >
      <div class="flex justify-center items-center flex-col   w-[100%] mb-5">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">How It Works</p>
        <h2 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-15">Find Your Prefered 2 Easy Steps</h2>
      </div>
      <div class="flex gap-[20px] flex-col lg:flex-row">
        <div class="bg-emerald-100 p-[50px] lg:p-[100px] rounded-[20px] flex flex-col justify-center items-center text-center">
          <h3 class="text-gray-400 text-[5rem]">01</h3>
          <img src="assets/images/ai-technology.png" class="bg-white p-[20px] mb-[20px] rounded-[20px]">
          <h4 class="text-black font-bold text-[2rem] mb-[10px]">Search &amp; Filter</h4>
          <p>
            Browse through our extensive catalog of verified rental properties. Filter by type, location, budget, and amenities to find your perfect match.
          </p>
        </div>
         <div class="bg-blue-100 p-[50px] lg:p-[100px] rounded-[20px] flex flex-col justify-center items-center text-center">
          <h3  class="text-gray-400 text-[5rem]">02</h3>
          <img src="assets/images/check-mark.png" class="bg-white p-[20px] mb-[20px] rounded-[20px]">
          <h4 class="text-black font-bold text-[2rem] mb-[10px]">Check Availability</h4>
          <p>
            View real-time availability calendars, read detailed descriptions, check amenities, and see high-quality photos before making a decision.
          </p>
        </div>
      </div>
    </section>
  </main>


  <footer class=" pt-[100px]">
    <div class=" py-[100px]  bg-[#0da88a]">
      <div class="my-container flex flex-col justify-center items-center text-center">
          <h2 class="text-white font-bold text-[2rem] mb-3">Ready to Find Your Perfect Rental?</h2>
          <p class="text-white mb-7">
            Join thousands of satisfied tenants who found their ideal boarding house,<br class="hidden lg:flex"> apartment, or villa through our platform. Start    browsing verified properties today.
          </p>
          <div class="flex gap-3 flex-col lg:flex-row">
            <a href="#properties" class="btn btn-white rounded-[10px] p-[20px] text-[#0da88a]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
              </svg>
              Browse Properties
            </a>
            <a href="#" class="btn bg-transparent text-white rounded-[10px] p-[20px] bg-[#0da88a]">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
              Contact Support
            </a>
          </div>
      </div>
    </div>
    <div class=" bg-black">
      <div class="my-container py-[100px] flex justify-between flex-col lg:flex-row">
        <div class="mb-20 lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start">
          <div class="flex items-center mb-3">
            <img src="assets/images/logo-icon.png" class="w-[30px] me-2 ">
            <p class="text-white font-bold">RENTSPACE</p>
          </div>
          <p class="text-white mb-5">
            Your trusted platform for discovering  verified rental <br class="hidden lg:flex"> properties around Irosin Sorsogon
          </p>
          <ul class="flex gap-3">
            <li>
              <a href="#">
                <img src="assets/images/facebook.png" class="w-[25px]" >
              </a>
            </li>
            <li>
              <a href="#">
                <img src="assets/images/instagram.png" class="w-[25px]" >
              </a>
            </li>
            <li>
              <a href="#">
                <img src="assets/images/twitter.png" class="w-[25px]" >
              </a>
            </li>
          </ul>
        </div>
        <div class="mb-20 lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start"> 
          <p class="text-white font-bold mb-3">Quick Links</p>
          <ul>
            <li>
              <a href="#" class="text-white">Home</a>
            </li>
            <li>
              <a href="#properties" class="text-white">Properties</a>
            </li>
            <li>
              <a href="#aboutus" class="text-white">About Us</a>
            </li>
            <li>
              <a href="#works" class="text-white">How It Works</a>
            </li>
            <li>
              <a href="signin.php" class="text-white">Sign In</a>
            </li>
            <li>
              <a href="signup.php" class="text-white">Sign Up</a>
            </li>
          </ul>
        </div>
        <div class=" lg:mb-0 flex flex-col text-center lg:text-start justify-center lg:justify-start items-center lg:items-start">
          <p class="text-white font-bold mb-3">Contact</p>
          <ul>
            <li class="flex gap-3 mb-3">
              <img src="assets/images/phone-call.png" class="me-2">
              <p class="text-white">(639) 09095416200</p>
            </li>
            <li class="flex gap-3 mb-3">
              <img src="assets/images/email.png" class="me-2">
              <p class="text-white">sample@gmail.com</p>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </footer>


  <script src="assets/scripts/index.js"></script>
</body>
</html>

