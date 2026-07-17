

<?php
  include '../config.php'; 
  if(!isset($_SESSION['user_login'])){
    echo "<script>location.href='../index.php';</script>";
  }

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome User!</title>
     <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon"> 
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <script src="./../assets/scripts/jquery.js"></script>
</head>
<body class="bg-base-100">



  <div class="drawer lg:drawer-open ">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col ">
      <nav class="navbar w-full bg-base-300 px-4 bg-[#0fab9e]">
        <label for="my-drawer" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5 text-white"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </label>
        <div class="flex-1 font-bold text-white">Landlord Registration</div>
      </nav>
      <div class="p-6">
        <!--main content-->
        <main>
            <section class="my-container">
              
              <div class="w-full max-w-3xl bg-base-100 shadow-xl rounded-2xl p-6 md:p-8">
    
    <!-- Header -->
    <div class="text-center mb-10">
      <h1 class="text-3xl font-bold text-primary mb-2">Property Verification & Setup</h1>
      <p class="text-base-content/70">Complete your profile setup by verifying your identity and registering your property.</p>
    </div>

    <form class="space-y-8" onsubmit="event.preventDefault(); alert('Form submitted successfully!');">
      
      <!-- SECTION 1: Identity Verification -->
      <div class="card bg-base-100 border border-base-300 p-6 rounded-xl space-y-4">
        <div class="flex items-center gap-3 border-b border-base-200 pb-3">
          <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">1</div>
          <h2 class="text-xl font-bold text-base-content">Identity Verification</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs sm:text-sm">Government ID (Front Side)</span><span class="label-text-alt text-error">Required</span></div>
            <input type="file" class="file-input file-input-bordered file-input-primary w-full" accept=".jpg,.jpeg,.png,.pdf" required />
          </label>
          
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs sm:text-sm">Government ID (Back Side)</span><span class="label-text-alt text-error">Required</span></div>
            <input type="file" class="file-input file-input-bordered file-input-primary w-full" accept=".jpg,.jpeg,.png,.pdf" required />
          </label>
        </div>

        <label class="form-control w-full">
          <div class="label">
            <span class="label-text font-medium text-xs sm:text-sm">Selfie with ID</span>
            <span class="label-text-alt text-error">Required</span>
          </div>
          <input type="file" class="file-input file-input-bordered file-input-primary w-full" accept=".jpg,.jpeg,.png" required />
          <div class="label">
            <span class="label-text-alt text-base-content/60 text-xs">Hold your ID next to your face clearly for our liveness security verification step.</span>
          </div>
        </label>
      </div>

      <!-- SECTION 2: Property Ownership & Media -->
      <div class="card bg-base-100 border border-base-300 p-6 rounded-xl space-y-4">
        <div class="flex items-center gap-3 border-b border-base-200 pb-3">
          <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">2</div>
          <h2 class="text-xl font-bold text-base-content">Property Ownership & Media</h2>
        </div>
        
        <label class="form-control w-full">
          <div class="label">
            <span class="label-text font-medium text-xs sm:text-sm">Proof of Ownership Document</span>
            <span class="label-text-alt text-error">Required</span>
          </div>
          <input type="file" class="file-input file-input-bordered file-input-primary w-full" accept=".pdf,.jpg,.jpeg,.png" required />
          <div class="label">
            <span class="label-text-alt text-base-content/60 text-xs">Upload your Land Title, Deed of Absolute Sale, or notarized Management Authorization.</span>
          </div>
        </label>

        <label class="form-control w-full">
          <div class="label">
            <span class="label-text font-medium text-xs sm:text-sm">Property Photo Gallery</span>
            <span class="label-text-alt text-error">Required</span>
          </div>
          <input type="file" class="file-input file-input-bordered w-full" multiple accept=".jpg,.jpeg,.png" required />
          <div class="label">
            <span class="label-text-alt text-base-content/60 text-xs">Upload 3 to 10 high-quality photos showing the exterior and interior spaces.</span>
          </div>
        </label>
      </div>

      <!-- SECTION 3: Property Location -->
      <div class="card bg-base-100 border border-base-300 p-6 rounded-xl space-y-4">
        <div class="flex items-center gap-3 border-b border-base-200 pb-3">
          <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">3</div>
          <h2 class="text-xl font-bold text-base-content">Property Location</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div class="md:col-span-2">
            <label class="form-control w-full">
              <div class="label"><span class="label-text font-medium text-xs">Street Address / Unit / Bldg</span></div>
              <input type="text" placeholder="House 123, Maple Street" class="input input-bordered w-full" required />
            </label>
          </div>
          <div>
            <label class="form-control w-full">
              <div class="label"><span class="label-text font-medium text-xs">Barangay</span></div>
              <input type="text" placeholder="San Lorenzo" class="input input-bordered w-full" required />
            </label>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs">City / Municipality</span></div>
            <input type="text" placeholder="Makati City" class="input input-bordered w-full" required />
          </label>
          
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs">Province</span></div>
            <input type="text" placeholder="Metro Manila" class="input input-bordered w-full" required />
          </label>
          
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs">ZIP Code</span></div>
            <input type="text" placeholder="1223" class="input input-bordered w-full" required />
          </label>
        </div>

        <!-- Optional Map Pin Box Placeholder -->
        <div class="pt-2">
          <div class="label">
            <span class="label-text font-medium text-xs">Geographical Map Location</span>
            <span class="label-text-alt text-base-content/40">Optional</span>
          </div>
          <div class="w-full h-32 bg-base-200 rounded-xl border-2 border-dashed border-base-300 flex flex-col items-center justify-center text-center p-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-base-content/40 mb-1"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span class="text-xs text-base-content/60">Integrate Map Pin Component Here (Google Maps / Leaflet)</span>
          </div>
        </div>
      </div>

      <!-- SECTION 4: Payments & Tax -->
      <div class="card bg-base-100 border border-base-300 p-6 rounded-xl space-y-4">
        <div class="flex items-center gap-3 border-b border-base-200 pb-3">
          <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center font-bold text-primary">4</div>
          <h2 class="text-xl font-bold text-base-content">Payments & Tax Settlement</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs sm:text-sm">Receiving Bank Account / E-Wallet</span></div>
            <select class="select select-bordered w-full" required>
              <option disabled selected>Choose Bank / Wallet</option>
              <option>BDO Unibank</option>
              <option>BPI</option>
              <option>Metrobank</option>
              <option>GCash</option>
              <option>Maya</option>
            </select>
          </label>
          
          <label class="form-control w-full">
            <div class="label"><span class="label-text font-medium text-xs sm:text-sm">Account Number</span></div>
            <input type="text" placeholder="001234567890" class="input input-bordered w-full" required />
          </label>
        </div>

        <label class="form-control w-full">
          <div class="label">
            <span class="label-text font-medium text-xs sm:text-sm">Tax Identification Number (TIN)</span>
            <span class="label-text-alt text-base-content/40">Optional</span>
          </div>
          <input type="text" placeholder="123-456-789-000" class="input input-bordered w-full" />
          <div class="label">
            <span class="label-text-alt text-base-content/60 text-xs">Recommended if registering as a corporate landlord entity or property business.</span>
          </div>
        </label>
      </div>

      <!-- Submit Block -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-base-300">
        <div class="form-control">
          <label class="label cursor-pointer justify-start gap-3">
            <input type="checkbox" class="checkbox checkbox-primary checkbox-sm" required />
            <span class="label-text text-xs text-base-content/80">I swear the uploaded legal assets and financial documentation are completely accurate.</span>
          </label>
        </div>
        <button type="submit" class="btn btn-primary w-full sm:w-auto px-10">Verify Property</button>
      </div>

    </form>
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
  <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>
