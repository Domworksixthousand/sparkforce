<?php
 include "amenities.php";

?>



<dialog id="my_modal_3" class="modal">
  <div class="modal-box ">
    <form method="dialog" class="mb-5">
      <button type="button" onclick="location.href='amenities.php'" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
    </form>
    <form method="POST" action="../functions.php">
        <div class="w-[100%] mb-5">
            <p class="mb-2 text-sm">Amenity Name *</p>
            <label class="input w-[100%]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-squares-intersect-icon lucide-squares-intersect"><path d="M10 22a2 2 0 0 1-2-2"/><path d="M14 2a2 2 0 0 1 2 2"/><path d="M16 22h-2"/><path d="M2 10V8"/><path d="M2 4a2 2 0 0 1 2-2"/><path d="M20 8a2 2 0 0 1 2 2"/><path d="M22 14v2"/><path d="M22 20a2 2 0 0 1-2 2"/><path d="M4 16a2 2 0 0 1-2-2"/><path d="M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/><path d="M8 2h2"/></svg>
                <input type="text" class="autoInput grow w-[100%]" name="amenity" value="<?php echo $_SESSION['amenity'] ?? ''; ?>" placeholder="Enter Amenity" required />
            </label>
        </div>
        <div class="w-[100%] mb-5">
            <p class="mb-2 text-sm">Description (Optional)</p>
            <textarea name="desc" id="" class="h-50  w-[100%] border border-gray-300 p-5 input" placeholder="Enter Description"><?php echo $_SESSION['desc'] ?? ''; ?></textarea>
        </div>
        <div class="text-end">
            <button type="submit" name="save_amenity" class="btn btn-success text-white">Save</button>
        </div>
    </form>
  </div>
</dialog>

