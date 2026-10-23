<?php
    include '../config.php';

    // ---- Input handling -------------------------------------------------
    $location_back = basename($_GET['location_back'] ?? 'request_accounts.php');
    if ($location_back === '') {
        $location_back = 'request_accounts.php';
    }

    if (empty($_GET['id'])) {
        header("location:" . $location_back);
        exit;
    }
    $user_id = $_GET['id'];

    $show_actions = ($location_back === 'request_accounts.php');
    $location_back_e = htmlspecialchars($location_back, ENT_QUOTES);

    // ---- Account ----------------------------------------------------------
    $account = null;
    $request = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ?");
    $request->bind_param("s", $user_id);
    $request->execute();
    $result_request = $request->get_result();
    if ($result_request->num_rows > 0) {
        $account = $result_request->fetch_assoc();
    }

    if (!$account) {
        header("location:" . $location_back);
        exit;
    }

    $e = fn($v) => htmlspecialchars($v ?? '', ENT_QUOTES);

    $middlename     = $e($account['middlename']);
    $lastname       = $e($account['lastname']);
    $firstname      = $e($account['firstname']);
    $suffix         = $e($account['suffix']);
    $fullname       = trim($lastname . ' ' . $firstname . ' ' . $middlename . ' ' . $suffix);
    $email          = $e($account['email']);
    $contact_number = $e($account['contact_number']);
    $province       = $e($account['province']);
    $municipality   = $e($account['municipality']);
    $barangay       = $e($account['barangay']);
    $zipcode        = $e($account['zipcode']);
    $id_type        = $e($account['id_type']);
    $id_number      = $e($account['id_number']);
    $id_photo       = $e($account['id_photo']);
    $selfie_photo   = $e($account['selfie_photo']);
    $occupation     = $e($account['occupation']);
    $date_request   = $e($account['date_request']);
    $status         = $e($account['status']);
    $user_type      = (string)($account['user_type'] ?? '');
    $type_label     = ($user_type === '2') ? 'Landlord' : (($user_type === '3') ? 'Tenant' : 'N/A');

    $date_label = '';
    if ($date_request && ($ts = strtotime($date_request))) {
        $date_label = date('M d, Y h:i A', $ts);
    }

    $status_lc = strtolower($status);
    $status_class = match (true) {
        str_contains($status_lc, 'approve') && !str_contains($status_lc, 'dis') => 'badge-success',
        str_contains($status_lc, 'disapprove') || str_contains($status_lc, 'reject') => 'badge-error',
        default => 'badge-warning',
    };

    // ---- Landlord property (only for landlords) ---------------------------
    $property = null;
    $owner_docs_array = [];
    $photo_gallery_array = [];

    if ($user_type === '2') {
        $get = $conn->prepare("SELECT
                l.landlord_id, l.province, l.municipality, l.barangay, l.type,
                l.property_name, l.date_request, l.longitude, l.latitude, l.status,
                d.doc_name, d.doc_id,
                g.image_name, g.gallery_id
            FROM `landlord` AS l
            LEFT JOIN documents AS d ON l.landlord_id = d.landlord_id
            LEFT JOIN gallery AS g ON l.landlord_id = g.landlord_id
            WHERE l.user_id = ?");
        $get->bind_param("s", $user_id);
        $get->execute();
        $result_get = $get->get_result();

        while ($row_get = $result_get->fetch_assoc()) {
            if ($property === null) {
                $property = [
                    'name'         => $e($row_get['property_name']),
                    'type'         => $e($row_get['type']),
                    'province'     => $e($row_get['province']),
                    'municipality' => $e($row_get['municipality']),
                    'barangay'     => $e($row_get['barangay']),
                    'longitude'    => is_numeric($row_get['longitude']) ? (float)$row_get['longitude'] : null,
                    'latitude'     => is_numeric($row_get['latitude'])  ? (float)$row_get['latitude']  : null,
                ];
            }
            if (!empty($row_get['doc_id'])) {
                $owner_docs_array[$row_get['doc_id']] = $e($row_get['doc_name']);
            }
            if (!empty($row_get['gallery_id'])) {
                $photo_gallery_array[$row_get['gallery_id']] = $e($row_get['image_name']);
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Request Details</title>
    <link rel="shortcut icon" href="./../assets/images/logo-icon.png" type="image/x-icon">
    <link rel="stylesheet" href="./../assets/styles/daisy_ui.css">
    <link rel="stylesheet" href="./../assets/styles/index.css">
    <script src="./../assets/scripts/tailwind.js"></script>
    <script src="./../assets/scripts/daisy_ui.js"></script>
    <script src="../assets/scripts/cool_alert.js"></script>
    <?php if ($property && $property['latitude'] !== null && $property['longitude'] !== null): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>
</head>
<body class="bg-base-200 min-h-screen">
    <?php include '../alerts.php'; ?>

    <div class="container mx-auto px-4 py-6 max-w-6xl">

        <!-- Page header -->
        <div class="mb-6">
            <a href="<?php echo $location_back_e; ?>" class="btn btn-ghost btn-sm mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
                Back to List
            </a>
            <h1 class="text-2xl font-semibold text-base-content">Account Request Details</h1>
            <p class="text-sm text-base-content/60 mt-1">Review applicant information before approval</p>
        </div>

        <div class="grid gap-6">

            <!-- Summary card (single set of actions) -->
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="avatar">
                                <div class="w-16 h-16 rounded-full">
                                    <img src="../assets/uploads/<?php echo $selfie_photo; ?>" alt="<?php echo $fullname; ?>" onerror="this.src='../assets/images/default-avatar.png'" />
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-xl font-semibold text-base-content truncate"><?php echo $fullname ?: '-'; ?></h2>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="badge <?php echo ($user_type === '3') ? 'badge-primary' : 'badge-info'; ?> badge-sm"><?php echo $type_label; ?></span>
                                    <span class="badge <?php echo $status_class; ?> badge-sm"><?php echo $status ?: 'Pending'; ?></span>
                                    <?php if ($date_label): ?>
                                    <span class="text-xs text-base-content/60">Requested <?php echo $date_label; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($show_actions): ?>
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="button" id="btn-approve" class="btn bg-[#0d9488] hover:bg-[#0f766e] text-white border-none flex-1 md:flex-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
                                Approve
                            </button>
                            <button type="button" id="btn-disapprove" class="btn btn-outline btn-error flex-1 md:flex-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
                                Disapprove
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="card bg-base-100 shadow-sm border border-base-300">
                    <div class="card-body p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-[#0d9488]/10 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-base-content">Personal Information</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">First Name</p>
                                <p class="text-sm font-medium text-base-content"><?php echo $firstname ?: '-'; ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Middle Name</p>
                                <p class="text-sm font-medium text-base-content"><?php echo $middlename ?: '-'; ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Last Name</p>
                                <p class="text-sm font-medium text-base-content"><?php echo $lastname ?: '-'; ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Suffix</p>
                                <p class="text-sm font-medium text-base-content"><?php echo $suffix ?: '-'; ?></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Occupation</p>
                                <p class="text-sm font-medium text-base-content"><?php echo $occupation ?: '-'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Address -->
                <div class="space-y-6">
                    <div class="card bg-base-100 shadow-sm border border-base-300">
                        <div class="card-body p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-lg bg-[#0d9488]/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-base-content">Contact Information</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Email Address</p>
                                    <p class="text-sm font-medium text-base-content break-all"><?php echo $email ?: '-'; ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Contact Number</p>
                                    <p class="text-sm font-medium text-base-content"><?php echo $contact_number ?: '-'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-base-100 shadow-sm border border-base-300">
                        <div class="card-body p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-lg bg-[#0d9488]/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-base-content">Address</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Province</p>
                                    <p class="text-sm font-medium text-base-content"><?php echo $province ?: '-'; ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Municipality</p>
                                    <p class="text-sm font-medium text-base-content"><?php echo $municipality ?: '-'; ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Barangay</p>
                                    <p class="text-sm font-medium text-base-content"><?php echo $barangay ?: '-'; ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">ZIP Code</p>
                                    <p class="text-sm font-medium text-base-content"><?php echo $zipcode ?: '-'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ID Verification -->
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#0d9488]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-base-content">ID Verification</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">ID Type</p>
                            <p class="text-sm font-medium text-base-content"><?php echo $id_type ?: '-'; ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">ID Number</p>
                            <p class="text-sm font-medium text-base-content"><?php echo $id_number ?: '-'; ?></p>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php foreach ([['ID Photo', $id_photo], ['Selfie Photo', $selfie_photo]] as [$label, $file]): ?>
                        <div>
                            <p class="text-sm font-medium text-base-content mb-3"><?php echo $label; ?></p>
                            <a href="../assets/uploads/<?php echo $file; ?>" target="_blank" rel="noopener" class="block group">
                                <div class="border border-base-300 rounded-lg overflow-hidden group-hover:border-[#0d9488] transition-colors">
                                    <img src="../assets/uploads/<?php echo $file; ?>" alt="<?php echo $label; ?>" class="w-full h-64 object-contain bg-base-200" onerror="this.src='../assets/images/no-image.png'" />
                                </div>
                                <p class="text-xs text-base-content/60 mt-2 text-center group-hover:text-[#0d9488] transition-colors">Click to view full size</p>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($property): ?>
            <!-- Property (landlords only) -->
            <div class="card bg-base-100 shadow-sm border border-base-300">
                <div class="card-body p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#0d9488]/10 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-base-content">Property Information</h3>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Property Name</p>
                            <p class="text-sm font-medium text-base-content"><?php echo $property['name'] ?: '-'; ?></p>
                        </div>
                        <div>
                            <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Type</p>
                            <p class="text-sm font-medium text-base-content"><?php echo $property['type'] ?: '-'; ?></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-base-content/60 uppercase tracking-wide mb-1">Location</p>
                            <p class="text-sm font-medium text-base-content">
                                <?php echo trim(implode(', ', array_filter([$property['barangay'], $property['municipality'], $property['province']]))) ?: '-'; ?>
                            </p>
                        </div>
                    </div>

                    <?php if ($property['latitude'] !== null && $property['longitude'] !== null): ?>
                    <div id="map" class="w-full h-64 rounded-lg border border-base-300 mb-6 z-0"></div>
                    <?php endif; ?>

                    <?php if ($owner_docs_array): ?>
                    <p class="text-sm font-medium text-base-content mb-3">Ownership Documents</p>
                    <div class="flex flex-wrap gap-2 mb-6">
                        <?php foreach ($owner_docs_array as $doc_name): ?>
                        <a href="../assets/uploads/<?php echo $doc_name; ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5z"></path><path d="M14 2v6h6"></path></svg>
                            <?php echo $doc_name; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($photo_gallery_array): ?>
                    <p class="text-sm font-medium text-base-content mb-3">Photo Gallery</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <?php foreach ($photo_gallery_array as $image_name): ?>
                        <a href="../assets/uploads/<?php echo $image_name; ?>" target="_blank" rel="noopener" class="block border border-base-300 rounded-lg overflow-hidden hover:border-[#0d9488] transition-colors">
                            <img src="../assets/uploads/<?php echo $image_name; ?>" alt="Property photo" class="w-full h-32 object-cover bg-base-200" onerror="this.src='../assets/images/no-image.png'" />
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ($property && $property['latitude'] !== null && $property['longitude'] !== null): ?>
    <script>
        (function () {
            var lat = <?php echo json_encode($property['latitude']); ?>;
            var lng = <?php echo json_encode($property['longitude']); ?>;
            var map = L.map('map').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map);
        })();
    </script>
    <?php endif; ?>

    <?php if ($show_actions): ?>
    <script>
    (function () {
        var userId = <?php echo json_encode($user_id); ?>;
        var locationBack = <?php echo json_encode($location_back); ?>;

        function runAction(fieldName, confirmText, waitingText) {
            CoolAlert.show({
                icon: "question",
                title: "Important!",
                text: confirmText,
                confirmButtonText: "Confirm",
                showCancelButton: true,
                cancelButtonText: "Cancel"
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                CoolAlert.show({
                    title: 'Please Wait...',
                    text: waitingText,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: function () {
                        if (typeof CoolAlert.showLoading === 'function') {
                            CoolAlert.showLoading();
                        }
                    }
                });

                var formData = new FormData();
                formData.append('id', userId);
                formData.append(fieldName, '1');

                fetch('../functions.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                    return response.text();
                })
                .then(function () {
                    CoolAlert.show({
                        icon: "success",
                        title: "Done!",
                        text: "The request has been updated.",
                        showConfirmButton: true,
                        confirmButtonText: "OK"
                    }).then(function () {
                        location.href = locationBack;
                    });
                })
                .catch(function () {
                    CoolAlert.show({
                        icon: "error",
                        title: "Something went wrong",
                        text: "Please try again.",
                        showConfirmButton: true,
                        confirmButtonText: "OK"
                    });
                });
            });
        }

        document.getElementById('btn-approve').addEventListener('click', function () {
            runAction('approved_request_account', 'Are you sure Approved?', 'Sending email to user.');
        });

        document.getElementById('btn-disapprove').addEventListener('click', function () {
            runAction('disapproved_request_account', 'Are you sure Disapproved?', 'Sending email to user.');
        });
    })();
    </script>
    <?php endif; ?>

    <script src="./../assets/scripts/index.js"></script>
    <script src="./../assets/scripts/query_filter.js"></script>
</body>
</html>