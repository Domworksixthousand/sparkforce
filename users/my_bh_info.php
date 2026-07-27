<?php
include 'my_property.php';
if(isset($_GET['id']) && isset($_GET['property_id'])){
    $rent_id = $_GET['id'] ?? '';
    $landlord_id = $_GET['property_id'] ?? '';
}else{
    echo "<script>location.href='index.php';</script>";
    exit;
}

//RENTSPACE DETAILS
$get_rent = $conn->prepare("
    SELECT name, price, image_cover, other_info 
    FROM rentspace 
    WHERE rent_id = ?
");
$get_rent->bind_param("s", $rent_id);
$get_rent->execute();
$rent_res = $get_rent->get_result();

$name = "";
$price = "";
$image_cover = "";
$other_info = "";

if ($rent_row = $rent_res->fetch_assoc()) {
    $name        = $rent_row['name'];
    $price       = $rent_row['price'];
    $image_cover = $rent_row['image_cover'];
    $other_info  = $rent_row['other_info'];
}

// BOARDING HOUSES
$get_bh = $conn->prepare("
    SELECT boarding_id, status, num_decks, image, bed_number 
    FROM boarding_house 
    WHERE rent_id = ? ORDER BY bed_number
");
$get_bh->bind_param("s", $rent_id);
$get_bh->execute();
$bh_res = $get_bh->get_result();

$boarding_houses = [];
while ($row = $bh_res->fetch_assoc()) {
    $boarding_houses[$row['boarding_id']] = $row;
}

// AMENITIES FROM DATABASE
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

// Helper: badge color per bed status
function bedStatusBadge($status) {
    switch ($status) {
        case 'Available':      return 'badge-success';
        case 'Not Available':  return 'badge-warning';
        case 'Out of Order':   return 'badge-error';
        default:                return 'badge-ghost';
    }
}

$totalBeds     = count($boarding_houses);
$availableBeds = count(array_filter($boarding_houses, fn($b) => $b['status'] === 'Available'));
?>

<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-4xl p-0 overflow-hidden">

    <!-- Close button -->
    <button 
      type="button" 
      onclick="window.location.href='my_property.php?property_id=<?php echo htmlspecialchars($landlord_id); ?>';" 
      class="btn btn-sm btn-circle bg-white/90 hover:bg-white border-none shadow-md absolute right-3 top-3 z-20">
      ✕
    </button>

    <!-- HERO -->
    <div class="relative w-full h-56 sm:h-64">
      <img 
        src="../assets/uploads/<?php echo htmlspecialchars($image_cover); ?>" 
        alt="<?php echo htmlspecialchars($name); ?>"
        class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
      <div class="absolute bottom-0 left-0 p-5 text-white">
        <h2 class="text-2xl font-bold drop-shadow-sm"><?php echo htmlspecialchars($name); ?></h2>
        <p class="text-lg font-semibold text-success-content">
          <span class="text-white">&#x20B1;<?php echo htmlspecialchars(number_format((float)$price, 2)); ?> <span class="text-sm font-normal opacity-80">/ month</span></span>
        </p>
      </div>
      <div class="absolute top-3 left-3 flex gap-2">
        <span class="badge badge-neutral bg-black/50 border-none text-white backdrop-blur-sm">
          <?php echo $totalBeds; ?> Bed<?php echo $totalBeds !== 1 ? 's' : ''; ?>
        </span>
        <span class="badge badge-success text-white border-none">
          <?php echo $availableBeds; ?> Available
        </span>
      </div>
    </div>

    <!-- TABS -->
    <div role="tablist" class="tabs tabs-bordered px-5 pt-3 bg-base-100">
      <a role="tab" class="tab tab-active room-tab" data-tab="overview">Overview</a>
      <a role="tab" class="tab room-tab" data-tab="beds">Beds (<?php echo $totalBeds; ?>)</a>
      <a role="tab" class="tab room-tab" data-tab="amenities">Amenities (<?php echo count($saved_amenities); ?>)</a>
    </div>

    <!-- TAB CONTENT -->
    <div class="p-5 max-h-[50vh] overflow-y-auto">

      <!-- OVERVIEW -->
      <div class="tab-panel" data-panel="overview">
        <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wide mb-2">About this space</h3>
        <p class="text-sm leading-relaxed text-gray-700 whitespace-pre-line">
          <?php echo !empty($other_info) ? htmlspecialchars($other_info) : 'No additional information provided.'; ?>
        </p>
      </div>

      <!-- BEDS -->
      <div class="tab-panel hidden" data-panel="beds">
        <?php if (!empty($boarding_houses)): ?>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <?php foreach ($boarding_houses as $bh): ?>
              <div class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-md transition-shadow">
                <figure class="h-32 bg-base-200">
                  <?php if (!empty($bh['image'])): ?>
                    <img 
                      src="../assets/uploads/<?php echo htmlspecialchars($bh['image']); ?>" 
                      alt="<?php echo htmlspecialchars($bh['bed_number']); ?>"
                      class="w-full h-full object-cover">
                  <?php else: ?>
                    <div class="flex items-center justify-center w-full h-full text-gray-400 text-xs">No image</div>
                  <?php endif; ?>
                </figure>
                <div class="card-body p-3">
                  <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-sm"><?php echo htmlspecialchars($bh['bed_number']); ?></h4>
                    <span class="badge badge-sm <?php echo bedStatusBadge($bh['status']); ?> text-white">
                      <?php echo htmlspecialchars($bh['status']); ?>
                    </span>
                  </div>
                  <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                    <?php echo htmlspecialchars($bh['num_decks']); ?> Deck<?php echo $bh['num_decks'] != 1 ? 's' : ''; ?>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="text-sm text-gray-500 italic">No beds found for this room.</p>
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
          <p class="text-sm text-gray-500 italic">No amenities listed for this room.</p>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</dialog>
