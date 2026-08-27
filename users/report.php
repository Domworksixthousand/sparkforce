<?php

$location_back = basename($_GET['location_back'] ?? '');


include "$location_back";

$user_id     = $_GET['user_id'] ?? '';
$rent_id     = $_GET['id'] ?? '';
$report_type = $_GET['report_type'] ?? '';
?>

<dialog id="my_modal_3" class="modal" open>
  <div class="modal-box ">
    <form method="dialog" class="mb-5">
           <button type="button" onclick="location.href='<?php echo htmlspecialchars($location_back); ?>?id=<?php echo htmlspecialchars($rent_id); ?>'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form method="POST" action="../functions.php" enctype="multipart/form-data">
        <input type="hidden" name="report_type" value="<?php echo htmlspecialchars($report_type); ?>">
        <input type="hidden" name="user_id_reported" value="<?php echo htmlspecialchars($user_id); ?>">
        <input type="hidden" name="rent_id" value="<?php echo htmlspecialchars($rent_id); ?>">
        <input type="hidden" name="location_back" value="<?php echo htmlspecialchars($location_back); ?>">


        <div class="w-[100%] mb-3">
                <p class="mb-2 text-sm"> Photos  *</p>

                <?php if (!empty($_SESSION['gallery'])): ?>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-2 mb-3">
                        <?php foreach ($_SESSION['gallery'] as $img): ?>
                            <div class="relative">
                                <img src="../assets/uploads/<?php echo htmlspecialchars($img); ?>" 
                                    class="w-full h-16 object-cover rounded border border-success/30">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="alert alert-success bg-success/10 text-success border border-success/20 p-2 mb-2 text-xs rounded-lg">
                        <?php echo count($_SESSION['gallery']); ?> photo(s) retained. Upload new photos below to replace them.
                    </div>
                <?php endif; ?>

                <label class="input w-[100%] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16"/><path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2"/><circle cx="13" cy="7" r="1" fill="currentColor"/><rect x="8" y="2" width="14" height="14" rx="2"/></svg>
                    <input 
                        type="file" 
                        class="file-input grow w-[100%]" 
                        id="gallery" 
                        name="gallery[]" 
                        accept="image/jpeg, image/png, image/jpg" 
                        multiple
                        <?php echo !empty($_SESSION['gallery']) ? '' : 'required'; ?>
                    />
                </label>
                <p class="text-xs text-gray-400 mt-1">Please select 3–10 photos.</p>
            </div>               
        <textarea class="w-[100%] border border-gray-400 rounded-lg h-[15rem] p-[10px]" name="reason" placeholder="Enter your Concern" required></textarea>
        <div class="text-end mt-3">
            <button type="submit" name="save_report" class="btn btn-success text-white">Report</button>
        </div>
    </form>
  </div>
</dialog>
