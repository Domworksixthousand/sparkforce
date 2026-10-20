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
<body class="min-h-screen">

    <!---alert-->
    <?php 
        include 'config.php';
        include 'alerts.php';
        include 'modal_terms_and_reg_signup_landlord.php';
        include 'modal_map.php';
        include 'requirements_landlord.php';
        include 'selfie_modal.php';
        // Safe "old value" helper (escapes session values before printing)
        function rs_old($key, $default = '') {
            return htmlspecialchars((string)($_SESSION[$key] ?? $default), ENT_QUOTES, 'UTF-8');
        }
    ?>

    <main>
        <section class="flex">

            <!-- ============================================ -->
            <!-- LEFT PANEL (branding) -->
            <!-- ============================================ -->
            <div class="bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)] hidden md:flex w-[40%] h-screen sticky top-0 self-start flex-col justify-center items-center text-center p-10">
                <img src="assets/images/logo-icon.png" class="w-[60px] mb-4 drop-shadow-md" alt="RentSpace Logo">
                <h1 class="cursive-text text-white font-black tracking-wider text-[1rem] mb-4 drop-shadow-sm">RENTHUB</h1>
                <div class="w-16 h-[3px] bg-white/50 rounded-full mb-6"></div>
                <p class="text-white text-base lg:text-lg font-medium leading-relaxed max-w-sm opacity-95">
                    Your trusted partner in listing premium properties and finding your perfect rental space.
                </p>
            </div>

            <!-- ============================================ -->
            <!-- RIGHT PANEL (form) -->
            <!-- ============================================ -->
            <form action="functions.php" method="POST" id="myForm" enctype="multipart/form-data"
                  class="w-full md:w-[60%] min-h-screen bg-slate-50 px-4 py-8 sm:px-8 lg:px-14 lg:py-12">

                <input type="hidden" id="lat" name="latitude" value="<?= rs_old('latitud') ?>" readonly required>
                <input type="hidden" id="lng" name="longitude" value="<?= rs_old('longitude') ?>" readonly required>
                <input type="hidden" name="type_register" value="landlord">

                <div class="max-w-3xl mx-auto space-y-6">

                    <!-- Header -->
                    <div class="text-center mb-2">
                        <img src="assets/images/logo-icon.png" class="w-[52px] mx-auto mb-3 drop-shadow-md md:hidden" alt="RentSpace Logo">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Create Landlord Account</h2>
                        <p class="text-sm text-slate-500 mt-1">Fill in your details to start listing your properties.</p>
                        <div class="w-14 h-[3px] bg-[#14b8a6]/90 rounded-full mx-auto mt-4"></div>
                    </div>

                    <!-- ============================================ -->
                    <!-- 1. PERSONAL INFORMATION -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">1</span>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 leading-tight">Personal Information</h3>
                                <p class="text-xs text-slate-500">Your full name as shown on your ID.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Last Name <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" name="lastname" value="<?= rs_old('lastname') ?>" placeholder="Enter Last Name" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">First Name <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" name="firstname" value="<?= rs_old('firstname') ?>" placeholder="Enter First Name" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Middle Name</label>
                                <input type="text" class="autoInput input input-bordered w-full" name="middlename" value="<?= rs_old('middlename') ?>" placeholder="Leave blank if none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Suffix</label>
                                <input type="text" class="autoInput input input-bordered w-full" name="suffix" value="<?= rs_old('suffix') ?>" placeholder="Leave blank if none" />
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 2. CONTACT & ADDRESS -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">2</span>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 leading-tight">Contact &amp; Address</h3>
                                <p class="text-xs text-slate-500">How we can reach you and where you live.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email Address <span class="text-error">*</span></label>
                                <input type="email" class="input input-bordered w-full" name="email" value="<?= rs_old('email') ?>" placeholder="Enter Email" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Contact Number <span class="text-error">*</span></label>
                                <input type="text" id="contactNumber" class="number_only input input-bordered w-full" name="contact_number" value="<?= rs_old('contact_number') ?>" placeholder="09123456789" maxlength="11" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Province <span class="text-error">*</span></label>
                                <select name="province" id="province" onchange="updateMunicipalities()" class="select select-bordered w-full cursor-pointer" required>
                                    <option value="<?= rs_old('province', 'Select') ?>"><?= rs_old('province', 'Select') ?></option>
                                    <option value="Albay">Albay</option>
                                    <option value="CamarinesNorte">Camarines Norte</option>
                                    <option value="CamarinesSur">Camarines Sur</option>
                                    <option value="Catanduanes">Catanduanes</option>
                                    <option value="Masbate">Masbate</option>
                                    <option value="Sorsogon">Sorsogon</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Municipality <span class="text-error">*</span></label>
                                <select name="municipality" id="municipality" onchange="updateBarangays()" class="select select-bordered w-full cursor-pointer" required>
                                    <option value="<?= rs_old('municipality', 'Select') ?>"><?= rs_old('municipality', 'Select') ?></option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Barangay <span class="text-error">*</span></label>
                                <select name="barangay" id="barangay" class="select select-bordered w-full cursor-pointer" required>
                                    <option value="<?= rs_old('barangay', 'Select') ?>"><?= rs_old('barangay', 'Select') ?></option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">ZIP Code <span class="text-error">*</span></label>
                                <input type="text" class="input input-bordered w-full bg-slate-100" name="zipcode" id="zipcode" value="<?= rs_old('zipcode') ?>" placeholder="Auto-filled" readonly>
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 3. ACCOUNT INFORMATION -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">3</span>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 leading-tight">Account Information</h3>
                                <p class="text-xs text-slate-500">Username 7+ characters. Password 7+ characters with a number and a symbol.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Username <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" name="username" value="<?= rs_old('username') ?>" placeholder="Enter Username" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password <span class="text-error">*</span></label>
                                <label class="input input-bordered flex items-center gap-2 w-full">
                                    <input type="password" class="grow" id="password" name="password" value="<?= rs_old('password') ?>" placeholder="Enter Password" required />
                                    <img src="assets/images/hide-icon.png" class="w-5 h-5 cursor-pointer" id="pass" onclick="togglePasswords()" alt="Show / hide password">
                                </label>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Repeat Password <span class="text-error">*</span></label>
                                <label class="input input-bordered flex items-center gap-2 w-full">
                                    <input type="password" class="grow" id="repeat_password" name="repeat_password" value="<?= rs_old('repeat_password') ?>" placeholder="Repeat Password" />
                                    <img src="assets/images/hide-icon.png" class="w-5 h-5 cursor-pointer" id="repeat_pass" onclick="toggleRepeatPassword()" alt="Show / hide password">
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 4. IDENTITY VERIFICATION -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">4</span>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 leading-tight">Identity Verification</h3>
                                <p class="text-xs text-slate-500">A valid government ID and a clear selfie.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">ID Type <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" name="id_type" value="<?= rs_old('id_type') ?>" placeholder="e.g. Driver's License" required />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">ID Number <span class="text-error">*</span></label>
                                <input type="text" class="input input-bordered w-full" id="id_number" name="id_number" value="<?= rs_old('id_number') ?>" placeholder="Enter ID Number" required />
                            </div>

                            <!-- ID Photo -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">ID Photo <span class="text-error">*</span></label>
                                <?php if (isset($_SESSION['id_photo_name'])): ?>
                                    <input type="hidden" name="old_id_photo" value="<?= rs_old('id_photo_name') ?>">
                                    <div class="flex items-center justify-between gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 mb-2">
                                        <span class="truncate">Saved: <strong><?= rs_old('id_photo_name') ?></strong></span>
                                        <span class="badge badge-success badge-sm text-white shrink-0">Saved</span>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="file-input file-input-bordered w-full" id="id_photo" name="id_photo" accept="image/jpeg, image/jpg" <?= isset($_SESSION['id_photo_name']) ? '' : 'required' ?> />
                                <p class="text-[11px] text-slate-400 mt-1">JPG / JPEG only.</p>
                            </div>

                            <!-- Selfie -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Selfie <span class="text-error">*</span></label>
                                <?php if (isset($_SESSION['selfie_photo_name'])): ?>
                                    <input type="hidden" name="old_selfie_photo" value="<?= rs_old('selfie_photo_name') ?>">
                                    <div class="flex items-center justify-between gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 mb-2">
                                        <span class="truncate">Saved: <strong><?= rs_old('selfie_photo_name') ?></strong></span>
                                        <span class="badge badge-success badge-sm text-white shrink-0">Saved</span>
                                    </div>
                                <?php endif; ?>
                                <input type="file" id="selfieInput" name="selfie_photo" class="file-input file-input-bordered w-full select-none" />
                                <button type="button" class="btn btn-success btn-sm text-white mt-2 gap-2" onclick="selfie_modal.showModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/><circle cx="12" cy="13" r="3"/></svg>
                                    Take Selfie
                                </button>
                                <canvas id="snapshot" width="640" height="480" class="hidden"></canvas>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Occupation <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" name="occupation" value="<?= rs_old('occupation') ?>" placeholder="Enter Occupation" required />
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 5. PROPERTY OWNERSHIP & MEDIA -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start sm:items-center justify-between gap-3 flex-col sm:flex-row mb-5">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">5</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 leading-tight">Property Ownership &amp; Media</h3>
                                    <p class="text-xs text-slate-500">Photos of the property and proof that you own it.</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline btn-success btn-sm gap-2" onclick="requirements_modal.showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/><path d="M14 2v5a1 1 0 0 0 1 1h5"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                Document requirements
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Photo Gallery -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Property Photo Gallery <span class="text-error">*</span></label>
                                <?php if (isset($_SESSION['photo_gallery']) && is_array($_SESSION['photo_gallery']) && !empty($_SESSION['photo_gallery'])): ?>
                                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 mb-2">
                                        ✓ Already uploaded (<?= count($_SESSION['photo_gallery']) ?> photo/s)
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="file-input file-input-bordered w-full" id="photo_gallery" name="photo_gallery[]" multiple accept="image/jpeg, image/jpg" <?= isset($_SESSION['photo_gallery']) ? '' : 'required' ?> />
                                <p class="text-[11px] text-slate-400 mt-1">You can select multiple photos. JPG / JPEG only.</p>
                            </div>

                            <!-- Proof of Ownership -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Proof of Ownership Document <span class="text-error">*</span></label>
                                <?php if (isset($_SESSION['owner_docs']) && is_array($_SESSION['owner_docs']) && !empty($_SESSION['owner_docs'])): ?>
                                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800 mb-2">
                                        ✓ Already uploaded (<?= count($_SESSION['owner_docs']) ?> file/s)
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="file-input file-input-bordered w-full" id="owner_docs" name="owner_docs[]" multiple accept="image/jpeg, image/jpg" <?= isset($_SESSION['owner_docs']) ? '' : 'required' ?> />
                                <p class="text-[11px] text-slate-400 mt-1">Title, tax declaration, or authorization letter.</p>
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 6. PROPERTY LOCATION -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-start sm:items-center justify-between gap-3 flex-col sm:flex-row mb-5">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">6</span>
                                <div>
                                    <h3 class="text-base font-bold text-slate-800 leading-tight">Property Location</h3>
                                    <p class="text-xs text-slate-500">Click Locate, then pin your property on the map.</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm text-white gap-2" onclick="my_modal_maps.showModal()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8c0 3.613-3.869 7.429-5.393 8.795a1 1 0 0 1-1.214 0C9.87 15.429 6 11.613 6 8a6 6 0 0 1 12 0"/><circle cx="12" cy="8" r="2"/><path d="M8.714 14h-3.71a1 1 0 0 0-.948.683l-2.004 6A1 1 0 0 0 3 22h18a1 1 0 0 0 .948-1.316l-2-6a1 1 0 0 0-.949-.684h-3.712"/></svg>
                                Locate
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Province <span class="text-error">*</span></label>
                                <input type="text" class="input input-bordered w-full bg-slate-100" id="province1" name="province1" value="<?= rs_old('province1') ?>" placeholder="Pin on map" readonly />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Municipality <span class="text-error">*</span></label>
                                <input type="text" class="input input-bordered w-full bg-slate-100" id="municipality1" name="municipality1" value="<?= rs_old('municipality1') ?>" placeholder="Pin on map" readonly />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Barangay <span class="text-error">*</span></label>
                                <input type="text" class="input input-bordered w-full bg-slate-100" id="barangay1" name="barangay1" value="<?= rs_old('barangay1') ?>" placeholder="Pin on map" readonly />
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- 7. PROPERTY INFORMATION -->
                    <!-- ============================================ -->
                    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="w-7 h-7 rounded-full bg-[#0d9488] text-white text-xs font-bold flex items-center justify-center">7</span>
                            <div>
                                <h3 class="text-base font-bold text-slate-800 leading-tight">Property Information</h3>
                                <p class="text-xs text-slate-500">What kind of property are you listing?</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Type of Property <span class="text-error">*</span></label>
                                <select class="select select-bordered w-full cursor-pointer" name="property_type">
                                    <option value="<?= rs_old('property_type', 'Select Property type') ?>"><?= rs_old('property_type', 'Select Property type') ?></option>
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
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Property Name <span class="text-error">*</span></label>
                                <input type="text" class="autoInput input input-bordered w-full" id="property_name" name="property_name" value="<?= rs_old('property_name') ?>" placeholder="Enter Property Name" />
                            </div>
                        </div>
                    </section>

                    <!-- ============================================ -->
                    <!-- TERMS -->
                    <!-- ============================================ -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="terms_agree" name="terms_agree" class="checkbox checkbox-primary mt-0.5 shrink-0"
                                   <?= (isset($_SESSION['terms_agree']) && $_SESSION['terms_agree'] == 1) ? 'checked' : '' ?> required />
                            <label for="terms_agree" class="text-sm text-slate-600 leading-relaxed cursor-pointer select-none">
                                I certify that all details and identity verification documents provided are accurate.
                                I explicitly agree to the
                                <span class="text-primary font-semibold underline cursor-pointer" onclick="policy_modal.showModal()">Terms of Service, Privacy Policy, and Platform Ban Regulations</span>. *
                            </label>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- ACTIONS -->
                    <!-- ============================================ -->
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <button type="button" onclick="location.href='index.php'" class="btn btn-ghost border border-slate-300 text-slate-600 rounded-xl sm:w-40">
                            Cancel
                        </button>
                        <button type="submit" name="register" class="btn bg-[#0d9488] hover:bg-[#0b7d73] border-none text-white rounded-xl sm:w-48 gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Sign Up
                        </button>
                    </div>

                    <p class="text-center text-sm text-slate-500 pt-2">
                        <a href="signin.php" class="link text-primary">Already have an account?</a>
                        <span class="mx-2">·</span>
                        <a href="forgot_password.php" class="link text-primary">Forgot password?</a>
                        <span class="mx-2">·</span>
                        <a href="signup.php" class="link text-primary">Sign up as Tenant</a>
                    </p>

                </div>
            </form>
        </section>
    </main>


  

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