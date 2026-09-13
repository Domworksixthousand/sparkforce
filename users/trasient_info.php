<?php
include 'my_property.php';

if (isset($_GET['id']) && isset($_GET['property_id'])) {
    $rent_id     = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
} else {
    echo "<script>location.href='index.php';</script>";
    exit;
}

// RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info, rate
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
$rate        = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
    $rate        = $rent_row['rate'];
}

// TRANSIENT DETAILS
$gt_transient = $conn->prepare("SELECT * FROM `transient` WHERE `rent_id` = ?");
$gt_transient->bind_param("s", $rent_id);
$gt_transient->execute();
$result_transient = $gt_transient->get_result();

$transient_id   = "";
$square_area    = "";
$type_transient = "";
$status         = "";

if ($result_transient->num_rows > 0) {
    $row_transient  = $result_transient->fetch_assoc();
    $transient_id   = $row_transient['transient_id'] ?? '';
    $square_area    = $row_transient['square_area']  ?? '';
    $type_transient = $row_transient['type']         ?? '';
    $status         = $row_transient['status']       ?? '';
}

// GALLERY IMAGES
$get_gallery = $conn->prepare("SELECT * FROM `gallery2` WHERE `rent_id` = ?");
$get_gallery->bind_param("s", $rent_id);
$get_gallery->execute();
$result_gallery = $get_gallery->get_result();

$gallery_images = [];
if ($result_gallery->num_rows > 0) {
    while ($row_gallery = $result_gallery->fetch_assoc()) {
        $gallery_images[] = $row_gallery['image'];
    }
}

// AMENITIES FROM DATABASE
$get_saved_amen = $conn->prepare("
    SELECT a.amen_id, a.amenity, ra.rent_amen_id
    FROM rentspace_amenities AS ra
    INNER JOIN amenities AS a ON a.amen_id = ra.amen_id
    WHERE ra.rent_id = ?
");
$get_saved_amen->bind_param("s", $rent_id);
$get_saved_amen->execute();
$saved_amen_res = $get_saved_amen->get_result();

$saved_amenities = [];
while ($row = $saved_amen_res->fetch_assoc()) {
    $saved_amenities[] = [
        "amen_id"      => $row['amen_id'],
        "amenity"      => $row['amenity'],
        "rent_amen_id" => $row['rent_amen_id']
    ];
}

// Helper for status badges (reused pattern from condo_info.php)
function transientStatusBadge($status) {
    switch (strtolower($status ?? '')) {
        case 'available':
            return 'badge-success';
        case 'occupied':
            return 'badge-error';
        case 'out of order':
        case 'maintenance':
            return 'badge-warning';
        default:
            return 'badge-neutral';
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

    <!-- HERO -->
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
          <span class="text-white">&#x20B1;<?php echo htmlspecialchars(number_format((float) $price, 2)); ?> <span class="text-sm font-normal opacity-80">/ <?php echo htmlspecialchars($rate); ?></span></span>
        </p>
      </div>
      <div class="absolute top-3 left-3 flex gap-2">
        <span class="badge badge-neutral bg-black/50 border-none text-white backdrop-blur-sm">
          Transient Unit
        </span>
        <span class="badge <?php echo transientStatusBadge($status); ?> text-white border-none">
          <?php echo htmlspecialchars($status !== '' ? $status : 'Unknown'); ?>
        </span>
      </div>
    </div>

    <!-- TABS -->
    <div role="tablist" class="tabs tabs-bordered px-5 pt-3 bg-base-100">
      <a role="tab" class="tab tab-active room-tab" data-tab="overview">Overview</a>
      <a role="tab" class="tab room-tab" data-tab="details">Unit Details</a>
      <a role="tab" class="tab room-tab" data-tab="gallery">Gallery (<?php echo count($gallery_images); ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="amenities">Amenities (<?php echo count($saved_amenities); ?>)</a>
    </div>

    <!-- TAB CONTENT -->
    <div class="p-5 max-h-[50vh] overflow-y-auto">

      <!-- OVERVIEW -->
      <div class="tab-panel" data-panel="overview">
        <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-2">About this space</h3>
        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
          <?php echo !empty($other_info) ? $other_info : 'No additional information provided.'; ?>
        </p>
      </div>

      <!-- UNIT DETAILS (single transient record) -->
      <div class="tab-panel hidden" data-panel="details">
        <?php if (!empty($transient_id)): ?>
          <div class="card bg-base-100 border border-base-200 shadow-sm p-4">
            <div class="flex items-start justify-between mb-2">
              <div>
                <h4 class="font-bold text-base text-gray-800">
                  <?php echo htmlspecialchars($type_transient !== '' ? $type_transient : 'Standard Transient Unit'); ?>
                </h4>
                <p class="text-xs text-gray-500">
                  Unit ID: <?php echo htmlspecialchars($transient_id); ?>
                </p>
              </div>
              <span class="badge <?php echo transientStatusBadge($status); ?> text-white">
                <?php echo htmlspecialchars($status !== '' ? $status : 'Unknown'); ?>
              </span>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-2 pt-2 border-t border-base-200 text-xs text-gray-600">
              <div>
                <span class="font-semibold text-gray-700">Area:</span>
                <?php echo htmlspecialchars($square_area !== '' ? $square_area : 'N/A'); ?> sqm
              </div>
              <div>
                <span class="font-semibold text-gray-700">Type:</span>
                <?php echo htmlspecialchars($type_transient !== '' ? $type_transient : 'N/A'); ?>
              </div>
              <div>
                <span class="font-semibold text-gray-700">Rate:</span>
                &#x20B1;<?php echo htmlspecialchars(number_format((float) $price, 2)); ?> / <?php echo htmlspecialchars($rate); ?>
              </div>
              <div>
                <span class="font-semibold text-gray-700">Status:</span>
                <?php echo htmlspecialchars($status !== '' ? $status : 'N/A'); ?>
              </div>
            </div>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No transient unit registered for this property.</p>
        <?php endif; ?>
      </div>

      <!-- GALLERY -->
      <div class="tab-panel hidden" data-panel="gallery">
        <?php if (!empty($gallery_images)): ?>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php foreach ($gallery_images as $img): ?>
              <div class="h-32 bg-base-200 rounded-lg overflow-hidden border border-base-200">
                <a href="../assets/uploads/<?php echo htmlspecialchars($img); ?>" target="_blank" >
                  <img 
                  src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" 
                  alt="Gallery Image" 
                  class="w-full h-full object-cover">
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No gallery images uploaded for this space.</p>
        <?php endif; ?>
      </div>

      <!-- AMENITIES -->
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
          <p class="text-sm text-gray-500 italic">No amenities listed for this space.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>