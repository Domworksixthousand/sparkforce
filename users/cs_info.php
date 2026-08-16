<?php
include 'my_property.php';

if (isset($_GET['id']) && isset($_GET['property_id'])) {
    $rent_id     = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    echo "<script>location.href='index.php';</script>";
    exit;
}

// 1. FETCH MAIN RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info 
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name        = "";
$price       = 0;
$image_cover = "";
$other_info  = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
}

// 2. FETCH COMMERCIAL SPACE SPECIFICATIONS
$get_cs = $conn->prepare("SELECT * FROM `commercial_space` WHERE `rent_id` = ?");
$get_cs->bind_param("s", $rent_id);
$get_cs->execute();
$result_execute_cs = $get_cs->get_result();

$cs_id  = "";
$type   = "";
$area   = "";
$status = "";

if ($result_execute_cs->num_rows > 0) {
    if ($row_cs = $result_execute_cs->fetch_assoc()) {
        $cs_id  = $row_cs['cs_id'] ?? '';
        $type   = $row_cs['type'] ?? '';
        $area   = $row_cs['area'] ?? '';
        $status = $row_cs['status'] ?? '';
    }
}

// 3. FETCH GALLERY IMAGES
$apartment_images = [];
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

if ($result_gallery->num_rows > 0) {
    while ($row_gallery = $result_gallery->fetch_assoc()) {
        $apartment_images[] = $row_gallery['image'];
    }
}

// 4. FETCH AMENITIES
$get_amen = $conn->prepare("
    SELECT a.amen_id, a.amenity, ra.rent_amen_id
    FROM rentspace_amenities AS ra
    INNER JOIN amenities AS a ON a.amen_id = ra.amen_id
    WHERE ra.rent_id = ?
");
$get_amen->bind_param("s", $rent_id);
$get_amen->execute();
$amen_res = $get_amen->get_result();

$saved_amenities = [];
while ($row = $amen_res->fetch_assoc()) {
    $saved_amenities[] = [
        "amen_id"      => $row['amen_id'],
        "amenity"      => $row['amenity'],
        "rent_amen_id" => $row['rent_amen_id']
    ];
}

// Helper badge class para sa status
function getStatusBadgeClass($status) {
    switch (strtolower($status ?? '')) {
        case 'available':
            return 'badge-success text-white';
        case 'occupied':
            return 'badge-error text-white';
        case 'maintenance':
        case 'out of order':
            return 'badge-warning text-white';
        default:
            return 'badge-neutral text-white';
    }
}
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden">

    <!-- Close button -->
    <a 
        href="my_property.php?property_id=<?php echo urlencode($landlord_id); ?>" 
        class="btn btn-sm btn-circle bg-white/90 hover:bg-white border-none shadow-md absolute right-3 top-3 z-20">
        ✕
    </a>

    <!-- HERO HEADER -->
    <div class="relative w-full h-56 sm:h-64">
      <img 
        src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>" 
        alt="<?php echo htmlspecialchars($name); ?>"
        class="w-full h-full object-cover"
        onerror="this.src='../assets/uploads/default.jpg';" />
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
      
      <div class="absolute bottom-0 left-0 p-5 text-white">
        <h2 class="text-2xl font-bold drop-shadow-sm"><?php echo htmlspecialchars($name); ?></h2>
        <p class="text-lg font-semibold text-success-content">
          <span class="text-white">&#x20B1;<?php echo htmlspecialchars(number_format((float)$price, 2)); ?> <span class="text-sm font-normal opacity-80">/ month</span></span>
        </p>
      </div>

      <div class="absolute top-3 left-3 flex gap-2">
        <?php if (!empty($type)): ?>
          <span class="badge badge-neutral bg-black/50 border-none text-white backdrop-blur-sm">
            <?php echo htmlspecialchars($type); ?>
          </span>
        <?php endif; ?>
        <?php if (!empty($status)): ?>
          <span class="badge <?php echo getStatusBadgeClass($status); ?> border-none">
            <?php echo htmlspecialchars(ucfirst($status)); ?>
          </span>
        <?php endif; ?>
      </div>
    </div>

    <!-- TABS NAVIGATION -->
    <div role="tablist" class="tabs tabs-bordered px-5 pt-3 bg-base-100">
      <a role="tab" class="tab tab-active room-tab" data-tab="overview">Overview</a>
      <a role="tab" class="tab room-tab" data-tab="details">Space Details</a>
      <a role="tab" class="tab room-tab" data-tab="gallery">Gallery (<?php echo count($apartment_images); ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="amenities">Amenities (<?php echo count($saved_amenities); ?>)</a>
    </div>

    <!-- TAB CONTENT PANELS -->
    <div class="p-5 max-h-[50vh] overflow-y-auto">

      <!-- 1. OVERVIEW PANEL -->
      <div class="tab-panel" data-panel="overview">
        <!-- Quick Specs Summary -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-5 p-3 bg-base-200 rounded-lg text-center text-xs">
          <div>
            <span class="block text-gray-500 font-semibold">Space Type</span>
            <span class="text-sm font-bold text-gray-800"><?php echo !empty($type) ? htmlspecialchars($type) : 'N/A'; ?></span>
          </div>
          <div>
            <span class="block text-gray-500 font-semibold">Floor Area</span>
            <span class="text-sm font-bold text-gray-800"><?php echo !empty($area) ? htmlspecialchars($area) . ' sqm' : 'N/A'; ?></span>
          </div>
          <div>
            <span class="block text-gray-500 font-semibold">Status</span>
            <span class="text-sm font-bold text-gray-800"><?php echo !empty($status) ? htmlspecialchars(ucfirst($status)) : 'N/A'; ?></span>
          </div>
        </div>

        <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-2">About this Property</h3>
        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
          <?php echo !empty($other_info) ? htmlspecialchars($other_info) : 'No additional information provided.'; ?>
        </p>
      </div>

      <!-- 2. COMMERCIAL SPACE DETAILS PANEL -->
      <div class="tab-panel hidden" data-panel="details">
        <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-3">Commercial Space Specifications</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          
          <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
            <span class="text-xs text-gray-500 font-semibold uppercase">Space Type</span>
            <p class="text-base font-bold text-gray-800 mt-1"><?php echo !empty($type) ? htmlspecialchars($type) : 'N/A'; ?></p>
          </div>

          <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
            <span class="text-xs text-gray-500 font-semibold uppercase">Floor Area</span>
            <p class="text-base font-bold text-gray-800 mt-1"><?php echo !empty($area) ? htmlspecialchars($area) . ' sqm' : 'N/A'; ?></p>
          </div>

          <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
            <span class="text-xs text-gray-500 font-semibold uppercase">Availability Status</span>
            <p class="text-base font-bold text-gray-800 mt-1"><?php echo !empty($status) ? htmlspecialchars(ucfirst($status)) : 'N/A'; ?></p>
          </div>

        </div>
      </div>

      <!-- 3. GALLERY PANEL -->
      <div class="tab-panel hidden" data-panel="gallery">
        <?php if (!empty($apartment_images)): ?>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php foreach ($apartment_images as $img): ?>
              <div class="h-32 bg-base-200 rounded-lg overflow-hidden border border-base-200">
                <img 
                  src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" 
                  alt="Gallery Image" 
                  class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                  onerror="this.src='../assets/uploads/default.jpg';">
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No gallery images uploaded for this commercial space.</p>
        <?php endif; ?>
      </div>

      <!-- 4. AMENITIES PANEL -->
      <div class="tab-panel hidden" data-panel="amenities">
        <?php if (!empty($saved_amenities)): ?>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($saved_amenities as $amen): ?>
              <span class="badge badge-outline badge-lg gap-2 py-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5 text-success" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <?php echo htmlspecialchars($amen['amenity']); ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No amenities listed for this commercial space.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>
