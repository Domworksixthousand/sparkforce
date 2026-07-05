<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENTSPACE</title>
    <link rel="shortcut icon" href="assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="assets/styles/index.css">
</head>
<body >


<!--header ini-->
  <header class=" relative ">
    <nav class="navbar  lg:container  lg:px-[30px]   glass fixed top-0 left-1/2 -translate-x-1/2 z-50 border-b border-base-200 transition-all mt-0  lg:mt-[20px] rounded-0 lg:rounded-lg">
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
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#faq">FAQ</a></li>
            <div class="divider my-1"></div>
            <li><a href="signin.php" class="btn btn-ghost btn-sm">Sign In</a></li>
            <li><a href="signup.php" class="btn bg-[#14b8a6] btn-sm text-white">Get Started</a></li>
          </ul>
        </div>
        <a href="#" class="  flex flex-row">
          <img src="assets/images/logo-icon.png" class="w-[50px] me-2">
          <div class="flex flex-col mt-2">  
            <p class="cursive-text m-0 p-0 text-sm font-bold text-white">RENTSPACE</p>
            <p class="m-0 p-0 text-sm text-white">Find Your Places</p>
          </div>
        </a>
      </div>
      <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-1 text-[15px] font-medium text-base-content/80">
          <li><a href="#accomodations" class="hover:text-success text-white transition-colors">Properties</a></li>
          <li><a href="#aboutus" class="hover:text-primary transition-colors text-white">About Us</a></li>
          <li><a href="#testimonials" class="hover:text-primary transition-colors">Testimonials</a></li>
          <li><a href="#faq" class="hover:text-primary transition-colors">FAQ</a></li>
        </ul>
      </div>
      <div class="navbar-end gap-2 ">
        <a href="signin.php" class=" btn btn-ghost p-[7px] rounded-sm text-sm bg-transparent text-white hover:bg-[#14b8a6] btn-sm hidden lg:inline-flex font-semibold border-0">
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
    <section class="my-container py-[50px] px-[10px] lg:px-[0px]" id="properties">
      <div class="flex justify-center items-center flex-col   w-[100%]">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Property Categories</p>
        <h1 class="font-bold text-[1.5rem] border-b-5 border-b-emerald-800 w-fit mb-4">Find the Perfect Property Type</h1>
        <p>Whether you need a short-term stay or long-term rental, browse through our diverse range of verified property types.</p>
      </div>
      <div>
        <h1>sadsada</h1>
      </div>
    </section>
    <section class="my-container py-[50px] px-[10px] lg:px-[0px]" >
      <div class="flex justify-center items-center lg:justify-start lg:items-start flex-col   w-[100%]">
        <p class="bg-[#f0fdfa] p-[10px] rounded-xl text-emerald-800 font-bold mb-3">Featured Properties</p>
        <h1 class="font-bold text-[1.5rem]  w-fit mb-4">Hand-Picked Rental Spaces</h1>
        <p>Top-rated properties curated for quality and comfort</p>
      </div>
      <div>
        <h1>sadsada</h1>
      </div>
    </section>
    <section class=" py-[50px] px-[10px] lg:px-[0px] bg-gray-100" >
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
  </main>

    <script src="assets/scripts/tailwind.js"></script>
    <script src="assets/scripts/daisy_ui.js"></script>
    <script src="assets/scripts/index.js"></script>
</body>
</html>

