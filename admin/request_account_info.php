<?php
    $location_back = $_GET['location_back'];
    include "$location_back";
    if(isset($_GET['id'])){
        $user_id = $_GET['id'] ?? '';
    }

    if($location_back === "request_accounts.php"){
        $hidden = "";  
    }else{
        $hidden = "hidden";
    }

   $request = $conn->prepare("SELECT * FROM `accounts` WHERE `user_id` = ? ");
    $request->bind_param("s", $user_id);
    $request->execute();
    $result_request = $request->get_result();
    if ($result_request->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result_request)) {
            $middlename = htmlspecialchars($row['middlename'] ?? '');
            $lastname = htmlspecialchars($row['lastname'] ?? '');
            $firstname = htmlspecialchars($row['firstname'] ?? '');
            $suffix = htmlspecialchars($row['suffix'] ?? '');
            $fullname = $lastname . ' ' . $firstname . ' ' . $middlename . ' ' . $suffix;
            $email = htmlspecialchars($row['email'] ?? '');
            $contact_number = htmlspecialchars($row['contact_number'] ?? '');
            $province = htmlspecialchars($row['province'] ?? '');
            $id_type = htmlspecialchars($row['id_type'] ?? '');
            $id_number = htmlspecialchars($row['id_number'] ?? '');
            $municipality = htmlspecialchars($row['municipality'] ?? '');
            $barangay = htmlspecialchars($row['barangay'] ?? '');
            $zipcode = htmlspecialchars($row['zipcode'] ?? '');
            $address = $barangay . ', ' . $municipality . ', ' . $province;
            $id_photo = htmlspecialchars($row['id_photo'] ?? '');
            $date_request = htmlspecialchars($row['date_request'] ?? '');
            $occupation = htmlspecialchars($row['occupation'] ?? '');
            $selfie_photo = $row['selfie_photo'] ?? '';
            $timestamp = strtotime($date_request);
        }
    }
?>



<dialog id="my_modal_3" class="modal">
  <div class="modal-box w-11/12 max-w-3xl">
    <form method="dialog">
      <button type="button" onclick="location.href='<?php echo $location_back; ?>'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form >
        <input type="hidden" value="<?php echo $user_id; ?>" name="id">
        <p class="text-sm text-gray-500 mb-4">Date Request : <?php echo  date('F j, Y', $timestamp);; ?></p>
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <p class="text-sm font-bold">Personal Information</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <h4 class="text-gray-500 text-sm">FIRST NAME</h4>
                <p class="font-bold text-sm"><?php echo $firstname; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">MIDDLE NAME</h4>
                <p class="font-bold text-sm"><?php echo $middlename; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">LAST NAME</h4>
                <p class="font-bold text-sm"><?php echo $lastname; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">SUFFIX</h4>
                <p class="font-bold text-sm"><?php echo $suffix; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">OCCUPATION</h4>
                <p class="font-bold text-sm"><?php echo $occupation; ?></p>
            </div>
        </div>
         <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"  width="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
            <p class="text-sm font-bold">Contact Information</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <h4 class="text-gray-500 text-sm">CONTACT NUMBER</h4>
                <p class="font-bold text-sm"><?php echo $contact_number; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">EMAIL</h4>
                <p class="font-bold text-sm"><?php echo $email; ?></p>
            </div>          
        </div>
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900" height="30"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-house-icon lucide-map-pin-house"><path d="M15 22a1 1 0 0 1-1-1v-4a1 1 0 0 1 .445-.832l3-2a1 1 0 0 1 1.11 0l3 2A1 1 0 0 1 22 17v4a1 1 0 0 1-1 1z"/><path d="M18 10a8 8 0 0 0-16 0c0 4.993 5.539 10.193 7.399 11.799a1 1 0 0 0 .601.2"/><path d="M18 22v-3"/><circle cx="10" cy="10" r="3"/></svg>
            <p class="text-sm font-bold">Address</p>
        </div>
         <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
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
                <h4 class="text-gray-500 text-sm">ZIPCODE</h4>
                <p class="font-bold text-sm"><?php echo $zipcode; ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"   width="30" class="bg-emerald-200 p-1 rounded-[10px] text-green-900" height="30"  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card-lanyard-icon lucide-id-card-lanyard"><path d="M13.5 8h-3"/><path d="m15 2-1 2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3"/><path d="M16.899 22A5 5 0 0 0 7.1 22"/><path d="m9 2 3 6"/><circle cx="12" cy="15" r="3"/></svg>
            <p class="text-sm font-bold">ID Verification</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <h4 class="text-gray-500 text-sm">ID TYPE</h4>
                <p class="font-bold text-sm"><?php echo $id_type; ?></p>
            </div>
            <div>
                <h4 class="text-gray-500 text-sm">ID NUMBER</h4>
                <p class="font-bold text-sm"><?php echo $id_number; ?></p>
            </div>          
        </div>
        <div class="mb-4 ">
            <div class="w-[100%] flex flex-col justify-center items-center mb-5">
                <p class="text-sm text-gray-500  mb-4">ID PHOTO</p>
                <img src="./../assets/uploads/<?php echo $id_photo; ?>" class="w-[100%] lg:w-[50%] rounded-[20px] border-2 p-3 " >
            </div>
            <div class="w-[100%] flex flex-col justify-center items-center mb-5">
                 <p class="text-sm text-gray-500  mb-4">SELFIE PHOTO</p>
                <img src="./../assets/uploads/<?php echo $selfie_photo; ?>" class="w-[100%] lg:w-[50%] rounded-[20px] border-2 p-3 " >
            </div>
        </div>
        <div class="flex justify-center gap-3 w-[100%] flex-col md:flex-row <?php echo $hidden; ?>">
            <a href="request_account_info_approved.php?id=<?php echo $user_id; ?>&location_back=<?php echo $location_back; ?>"  class="btn btn-success text-white ">Approved</a>
            <a href="request_account_info_disapproved.php?id=<?php echo $user_id; ?>&location_back=<?php echo $location_back; ?>"   class="btn bg-gray-400 text-white">Disapproved</a>
        </div>
    </form>
  </div>
</dialog>
