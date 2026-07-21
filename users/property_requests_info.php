<?php
 include "property_requests.php";
    if(isset($_GET['id'])){
        $landlord_id = $_GET['id'] ?? '';
    }
    

    $get = $conn->prepare("SELECT 
            l.user_id,
            l.landlord_id,
            l.province,
            l.municipality,
            l.barangay,
            l.type,
            l.property_name,
            l.date_request, 
            l.longitude,
            l.latitude,
            l.status,
            d.doc_name,
            d.doc_id, 
            g.image_name,
            g.gallery_id 
        FROM `landlord` as l 
        LEFT JOIN documents as d ON l.landlord_id = d.landlord_id 
        LEFT JOIN gallery as g ON l.landlord_id = g.landlord_id 
        WHERE l.landlord_id = ?");
        $get->bind_param("i", $landlord_id);
        $get->execute();
        $result_get = $get->get_result();
        if($result_get->num_rows>0){

        $owner_docs_array = [];
        $photo_gallery_array = [];


        while ($row_get = $result_get->fetch_assoc()) {
        

        $property_name = $row_get['property_name'];
        $province = $row_get['province'];
        $municipality = $row_get['municipality'];
        $barangay = $row_get['barangay'];
        $status = $row_get['status'];
        $landlord_id = $row_get['landlord_id'];
        $type = $row_get['type'];
        $date_request = $row_get['date_request'];
        $longitude = $row_get['longitude'];
        $latitude = $row_get['latitude'];
        $user_id = $row_get['user_id'];

        if (!empty($row_get['doc_id'])) {
            $doc_id = $row_get['doc_id'];
        
            $owner_docs_array[$doc_id] = [
                'doc_id'   => $row_get['doc_id'],
                'doc_name' => $row_get['doc_name']
            ];
        }

    
        if (!empty($row_get['gallery_id'])) {
            $gallery_id = $row_get['gallery_id'];

            $photo_gallery_array[$gallery_id] = [
                'gallery_id' => $row_get['gallery_id'],
                'image_name' => $row_get['image_name']
            ];
        }

        }
    }
 
?>



<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-3xl">
    <form method="dialog">
      <button type="button" onclick="location.href='property_requests.php'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <div>
       <p class="text-sm text-gray-500 mb-4">Date Request : <?php echo date('F j, Y', strtotime($date_request)); ?></p>
       <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"  class="bg-emerald-200 p-1 rounded-[10px] text-green-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-land-plot-icon lucide-land-plot"><path d="m12 8 6-3-6-3v10"/><path d="m8 11.99-5.5 3.14a1 1 0 0 0 0 1.74l8.5 4.86a2 2 0 0 0 2 0l8.5-4.86a1 1 0 0 0 0-1.74L16 12"/><path d="m6.49 12.85 11.02 6.3"/><path d="M17.51 12.85 6.5 19.15"/></svg>
            <p class="text-sm font-bold">Property Information</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <h4 class="text-gray-500 text-sm">PROVINCE</h4>
                <p class="font-bold text-sm"><?php echo $province; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">MUNICIPALITY</h4>
                <p class="font-bold text-sm"><?php echo $municipality; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">BARANGAY</h4>
                <p class="font-bold text-sm"><?php echo $barangay; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">Property Type</h4>
                <p class="font-bold text-sm"><?php echo $type; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">Property Name</h4>
                <p class="font-bold text-sm"><?php echo $property_name; ?></p>
            </div>
        </div>
        <div id="propertyMap" class="w-full h-[350px] rounded-xl shadow-md border mb-4"></div>
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text-icon lucide-scroll-text"><path d="M15 12h-5"/><path d="M15 8h-5"/><path d="M19 17V5a2 2 0 0 0-2-2H4"/><path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3"/></svg>
            <p class="text-sm font-bold">Documents</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <?php if(!empty($owner_docs_array)): ?>
            <?php foreach($owner_docs_array as $docs): ?>
                <?php $file_extension = strtolower(pathinfo($docs['doc_name'], PATHINFO_EXTENSION)); ?>
                <div class="mb-4">
                    <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'webp'])): ?>
                        <a href="./../assets/uploads/<?php echo $docs['doc_name']; ?>" target="_blank">
                            <img src="./../assets/uploads/<?php echo $docs['doc_name']; ?>" class="w-[100%] lg:w-[100%] lg:min-h-50 rounded-[20px] p-3 cursor-pointer hover:opacity-90 transition" alt="Document Image">
                        </a>
                    <?php else: ?>
                        <a href="./../assets/uploads/<?php echo $docs['doc_name']; ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 text-sm font-medium">
                            📄 View Document (<?php echo strtoupper($file_extension); ?>)
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-gray-400 italic">No documents available.</p>
        <?php endif; ?>
        </div>
         <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-images-icon lucide-images"><path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/></svg>
            <p class="text-sm font-bold">Gallery</p>
        </div>
       <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <?php if (!empty($photo_gallery_array)): ?>
                <?php foreach ($photo_gallery_array as $images): ?>
                    <?php 
                        $file_extension = strtolower(pathinfo($images['image_name'], PATHINFO_EXTENSION)); 
                    ?>
                    <div class="mb-4">
                        <?php if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'webp'])): ?>
                            <a href="./../assets/uploads/<?php echo $images['image_name']; ?>" target="_blank">
                                <img src="./../assets/uploads/<?php echo $images['image_name']; ?>" class="w-[100%] lg:w-[100%] lg:min-h-50 rounded-[20px] p-3 cursor-pointer hover:opacity-90 transition" alt="Gallery Image">
                            </a>
                        <?php else: ?>
                            <a href="./../assets/uploads/<?php echo $images['image_name']; ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 text-sm font-medium">
                                📄 View Document (<?php echo strtoupper($file_extension); ?>)
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-gray-400 italic col-span-1 lg:col-span-3">No gallery images uploaded.</p>
            <?php endif; ?>
        </div>
    </div>
  </div>
</dialog>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const lat = <?php echo !empty($latitude) ? floatval($latitude) : 12.703015; ?>;
    const lng = <?php echo !empty($longitude) ? floatval($longitude) : 124.037141; ?>;


    const map = L.map('propertyMap').setView([lat, lng], 16);

    // I-load ang OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // GUMAWA NG CUSTOM ICON HERE
    const customIcon = L.icon({
        iconUrl: '../assets/images/home.png', 
        iconSize: [30, 30], // Laki ng icon (pixels)
        iconAnchor: [19, 38], // Gitna at ilalim ng icon
        popupAnchor: [0, -38]
    });

   
    L.marker([lat, lng], { icon: customIcon })
        .addTo(map)
        .bindPopup('<b><?php echo htmlspecialchars($property_name ?? "Property Location"); ?></b>')
        .openPopup();
});
</script>