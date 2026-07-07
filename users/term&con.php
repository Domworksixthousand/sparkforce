<!-- 1. Ang Trigger Button sa Sidebar o Page -->
<button onclick="landlord_terms_modal.showModal()" class="btn btn-primary font-bold shadow-md">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="size-5"><path d="M8 9h8M8 13h6M12 3v18M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
  Become a Landlord
</button>

<!-- 2. DaisyUI Responsive Modal -->
<dialog id="landlord_terms_modal" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box max-w-2xl bg-base-100 p-6 rounded-xl border border-base-300">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b border-base-300 pb-3 mb-4">
      <h3 class="text-xl font-black text-primary tracking-wide">RENTSPACE</h3>
      <span class="badge badge-outline font-semibold">Landlord Terms</span>
    </div>

    <h2 class="text-lg font-extrabold text-base-content mb-2">Terms and Conditions for Landlords</h2>
    <p class="text-xs text-base-content/60 mb-4">Please read carefully before activating your Landlord Centre account.</p>

    <!-- Scrollable Terms Container -->
    <div class="max-h-72 overflow-y-auto pr-2 space-y-4 text-sm text-base-content/80 bg-base-200 p-4 rounded-lg border border-base-300">
      
      <div>
        <h4 class="font-bold text-base-content">1. Account Eligibility & Verification</h4>
        <p class="text-xs mt-1">To list properties on RentSpace, you must be at least 18 years old and provide a valid government ID upon request. You are fully responsible for all listings and actions managed under your account.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">2. Property Listings & Accuracy</h4>
        <p class="text-xs mt-1">All uploaded descriptions, room rates, and real-time imagery must be accurate. Uploading misleading content or wrong availability data will result in immediate room layout suspension.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">3. Booking & Conflict Prevention</h4>
        <p class="text-xs mt-1">You must process schedules diligently to avoid booking overlaps. Rejecting confirmed reservations repeatedly without valid reasons may penalize your listing visibility in our system finder.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">4. Conduct & Tenant Safety</h4>
        <p class="text-xs mt-1">You must strictly maintain clean, safe, and livable lodging standards following local building laws. RentSpace maintains strict rules against illegal profiling and any form of tenant discrimination.</p>
      </div>

      <div>
        <h4 class="font-bold text-base-content">5. Platform Policies & Direct Deals</h4>
        <p class="text-xs mt-1">RentSpace reserves the right to enforce transaction processing rules. Closing agreements offline deliberately to circumvent platform logic after discovering tenants via RentSpace is strictly prohibited.</p>
      </div>

    </div>

    <!-- Interactive Actions/Form Form -->
    <form method="dialog" class="mt-6 space-y-4">
      
      <!-- Checkbox Requirement -->
      <div class="form-control">
        <label class="label cursor-pointer justify-start gap-3 items-start">
          <input type="checkbox" id="agree_checkbox" class="checkbox checkbox-primary mt-0.5" onchange="toggleSubmitBtn()" />
          <span class="label-text text-xs font-medium text-base-content/70">
            I have read, understood, and accept the Landlord Terms and Conditions and agree to verify my data accurately.
          </span>
        </label>
      </div>

      <!-- Footer Buttons -->
      <div class="flex justify-end gap-2 border-t border-base-300 pt-4">
        <!-- Close button standard modal behavior -->
        <button class="btn btn-ghost btn-sm font-semibold">Cancel</button>
        
        <!-- Submit Button (Naka-disabled sa simula) -->
        <button id="submit_landlord_btn" type="submit" class="btn btn-primary btn-sm font-bold shadow-md" disabled onclick="handleUpgrade()">
          Accept & Activate
        </button>
      </div>
    </form>

  </div>
</dialog>

<!-- JavaScript Logic to Control Activation state -->
<script>
  function toggleSubmitBtn() {
    const checkbox = document.getElementById('agree_checkbox');
    const submitBtn = document.getElementById('submit_landlord_btn');
    submitBtn.disabled = !checkbox.checked;
  }

  function handleUpgrade() {
    // Ilagay dito ang iyong AJAX polling, form submit, o redirect para i-update ang role_id sa backend database.
    alert('Processing your request to join the Landlord Centre!');
  }
</script>