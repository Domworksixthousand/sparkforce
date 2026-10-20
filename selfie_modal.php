  <!--modal selfie-->
    <dialog id="selfie_modal" class="modal">
        <div class="modal-box bg-[linear-gradient(to_right,#2A7B9B_0%,#57C785_100%,#EDDD53_100%)]">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <div class="form-group">
                <label class="block font-bold mb-2 text-white">Take a Selfie (Requirement):</label>
                <div class="relative w-full max-w-[640px] mx-auto overflow-hidden rounded-lg bg-black" id="camArea">
                    <video id="webcam" autoplay muted playsinline class="w-full h-auto block -scale-x-100"></video>
                    <div id="guide" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-1/2 h-[75%] border-2 border-dashed border-rose-500 rounded-[50%] pointer-events-none shadow-[0_0_0_9999px_rgba(0,0,0,0.5)] transition-all duration-200">
                        <div id="eyeLine" class="absolute top-[35%] left-0 w-full border-t border-dashed border-rose-500/60 transition-all duration-200"></div>
                        <div id="crossHairV" class="absolute top-0 left-1/2 h-full border-l border-dashed border-rose-500/40 -translate-x-1/2 transition-all duration-200"></div>
                    </div>
                </div>
                
                <!-- Selfie Preview -->
                <img id="preview" alt="Selfie Preview" class="w-full max-w-[640px] h-auto mx-auto rounded-lg -scale-x-100" style="display:none;">
                
                <!-- Status and Button -->
                <div id="status" class="my-3 text-center font-semibold text-white">Loading....</div>
               <div class="text-center">
                <button type="button" id="captureBtn" class="btn bg-white  text-black " disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-aperture-icon lucide-aperture"><circle cx="12" cy="12" r="10"/><path d="m14.31 8 5.74 9.94"/><path d="M9.69 8h11.48"/><path d="m7.38 12 5.74-9.94"/><path d="M9.69 16 3.95 6.06"/><path d="M14.31 16H2.83"/><path d="m16.62 12-5.74 9.94"/></svg>
                    CAPTURE
                </button>
               </div>
            </div>
        </div>
    </dialog>