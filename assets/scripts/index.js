document.addEventListener('DOMContentLoaded', () => {


    const inputFields = document.querySelectorAll('.numbers_only');
    inputFields.forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    });


    const modal = document.getElementById('my_modal_3');
    if (modal) {
        modal.showModal();
    }

  
    const btn = document.getElementById('dropdownBtn');
    const menu = document.getElementById('dropdownMenu');
    const arrow = document.getElementById('arrowIcon');
    if (btn && menu && arrow) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
        document.addEventListener('click', () => {
            menu.classList.add('hidden');
            arrow.classList.remove('rotate-180'); 
        });
    }


    const btn1 = document.getElementById('dropdownBtn1');
    const menu1 = document.getElementById('dropdownMenu1');
    const arrow1 = document.getElementById('arrowIcon1');
    if (btn1 && menu1 && arrow1) {
        btn1.addEventListener('click', (e) => {
            e.stopPropagation();
            menu1.classList.toggle('hidden');
            arrow1.classList.toggle('rotate-180');
        });
        document.addEventListener('click', () => {
            menu1.classList.add('hidden');
            arrow1.classList.remove('rotate-180'); 
        });
    }

    // 5. Admin Dropdown Menu 2
    const btn2 = document.getElementById('dropdownBtn2');
    const menu2 = document.getElementById('dropdownMenu2');
    const arrow2 = document.getElementById('arrowIcon2');
    if (btn2 && menu2 && arrow2) {
        btn2.addEventListener('click', (e) => {
            e.stopPropagation();
            menu2.classList.toggle('hidden');
            arrow2.classList.toggle('rotate-180');
        });
        document.addEventListener('click', () => {
            menu2.classList.add('hidden');
            arrow2.classList.remove('rotate-180'); 
        });
    }

    // 6. Auto Capitalization ng Unang Letra kada Salita (.autoInput)
    const autoFields = document.querySelectorAll('.autoInput');
    autoFields.forEach(inputField => {
        inputField.addEventListener('input', (e) => {
            const target = e.target;
            const cursorPosition = target.selectionStart;
            const originalValue = target.value;
            const words = originalValue.split(' ');
            const formattedWords = words.map(word => {
                if (word.length === 0) return '';
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            });
            
            const formattedValue = formattedWords.join(' ');
            if (originalValue !== formattedValue) {
                target.value = formattedValue;
                target.setSelectionRange(cursorPosition, cursorPosition);
            }
        });
    });

    // 7. Single Input Validation at Paste Restriction para sa Contact Number (.number_only)
    const contactInput = document.querySelector('.number_only');
    if (contactInput) {
        contactInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        contactInput.addEventListener('paste', function(e) {
            const pasteData = (e.clipboardData || window.clipboardData).getData('text');
            if (/[^0-9]/.test(pasteData)) {
                e.preventDefault();
                this.value = pasteData.replace(/[^0-9]/g, '').substring(0, 11);
            }
        });
    }

    // 8. Real-time Image Upload Preview (Profile Picture Setup)
    const fileInput = document.getElementById('profile-upload');
    const preview = document.getElementById('profile-preview');
    if (fileInput && preview) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});

// 9. Navbar Scroll Visual Effect Trigger
const navbar = document.querySelector('.navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar.classList.remove('glass');
            navbar.classList.add('bg-[#0d9488]');
        } else {
            navbar.classList.add('glass');
            navbar.classList.remove('bg-[#0d9488]');
        }
    });
}

// 10. Password Visibility Toggle Functions (Nasa labas para maabot ng inline HTML 'onclick')
function toggleRepeatPassword() {
    const passwordField = document.getElementById("repeat_password");
    const repeat_pass = document.getElementById("repeat_pass");
    if (passwordField && repeat_pass) {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            repeat_pass.src = "assets/images/view-icon.png";
        } else {
            passwordField.type = "password";
            repeat_pass.src = "assets/images/hide-icon.png";
        }
    }
}

function togglePasswords() {
    const password = document.getElementById("password");
    const pass = document.getElementById("pass");
    if (password && pass) {
        if (password.type === "password") {
            password.type = "text";
            pass.src = "assets/images/view-icon.png";
        } else {
            password.type = "password";
            pass.src = "assets/images/hide-icon.png";
        }
    }
}

function toggleRepeatPassword_user() {
    const passwordField = document.getElementById("repeat_password");
    const repeat_pass = document.getElementById("repeat_pass");
    if (passwordField && repeat_pass) {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            repeat_pass.src = "../assets/images/view-icon.png";
        } else {
            passwordField.type = "password";
            repeat_pass.src = "../assets/images/hide-icon.png";
        }
    }
}

function toggletPassword_user() {
    const password = document.getElementById("password");
    const pass = document.getElementById("pass");
    if (password && pass) {
        if (password.type === "password") {
            password.type = "text";
            pass.src = "../assets/images/view-icon.png";
        } else {
            password.type = "password";
            pass.src = "../assets/images/hide-icon.png";
        }
    }
}


//bed count
document.addEventListener('DOMContentLoaded', function () {
    const bedsContainer = document.getElementById('beds');
    const addBedBtn = document.getElementById('addBedBtn');

    // Keep track of total beds
    let bedCount = bedsContainer.getElementsByClassName('bed-item').length;

    addBedBtn.addEventListener('click', function () {
        bedCount++;

        // Template for new bed item
        const newBedHTML = `
            <div class="bed-item border-b border-base-200 pb-4 mb-5 relative">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-sm  bed-title">Bed ${bedCount}</p>
                    <button type="button" class="btn btn-error btn-xs text-white remove-bed-btn">
                        Remove
                    </button>
                </div>

           
                <div class="w-full flex flex-col items-end gap-3 mb-3">
                    <span class="w-full">
                        <label class="input w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <input type="text" class="autoInput grow w-full" name="bednum[]" value="Bed ${bedCount}" placeholder="Enter Bed Number" readonly />
                        </label>
                    </span>

                    <!-- Image Input -->
                    <span class="w-full">
                        <p class="mb-2 text-sm">Image *</p>
                        <label class="input w-full flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 9h3.75m-4.5 2.625h4.5M12 18.75 9.75 16.5h.375a2.625 2.625 0 0 0 0-5.25H9.75m.75-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <input type="file" class="file-input grow w-full" name="image[]" accept="image/jpeg, image/jpg" required />
                        </label>
                    </span>
                </div>

                <div class="w-full flex flex-col lg:flex-row gap-3">
                    <span class="w-full">
                        <p class="mb-2 text-sm">Number of Deck</p>
                       <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bed-double"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                            <select class="select w-[100%]" name="num_deck[]" required>
                                <option value="<?php echo $_SESSION['num_deck'] ?? '' ?>"><?php echo $_SESSION['num_deck'] ?? 'Select Number Decks' ?></option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>     
                        </div>     
                    </span>
                </div>
            </div>
        `;

        // Append the new bed block
        bedsContainer.insertAdjacentHTML('beforeend', newBedHTML);
    });


bedsContainer.addEventListener("click", function (e) {
    const btn = e.target.closest(".remove-bed-btn");

    if (!btn) return;

    const bedItems = bedsContainer.querySelectorAll(".bed-item");


    if (bedItems.length === 1) {
        alert("You cannot remove Bed 1. At least one bed is required.");
        return;
    }

    btn.closest(".bed-item").remove();

    reindexBeds();
});
 
  function reindexBeds() {
    const bedItems = bedsContainer.querySelectorAll(".bed-item");
    bedCount = bedItems.length;

    bedItems.forEach((item, index) => {
        const currentBedNum = index + 1;

        item.querySelector(".bed-title").textContent = `Bed ${currentBedNum}`;
        item.querySelector('input[name="bednum[]"]').value = `Bed ${currentBedNum}`;

        const removeBtn = item.querySelector(".remove-bed-btn");

        if (bedItems.length === 1) {
            removeBtn.style.display = "none";
        } else {
            removeBtn.style.display = "inline-flex";
        }
    });
}
  reindexBeds();
});


//amenities count
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('amenities-container');
    const addamenBtn = document.getElementById('addamenBtn');

    addamenBtn.addEventListener('click', function () {

        const firstSelect = container.querySelector('select');

        const optionsMarkup = firstSelect.innerHTML.replace(
            /selected/g,
            ''
        );

        const row = document.createElement('div');
        row.className = 'amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2';

        row.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg"
                class="size-4 text-gray-500 ms-2 flex-shrink-0"
                viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 22a2 2 0 0 1-2-2"/>
                <path d="M14 2a2 2 0 0 1 2 2"/>
                <path d="M16 22h-2"/>
                <path d="M2 10V8"/>
                <path d="M2 4a2 2 0 0 1 2-2"/>
                <path d="M20 8a2 2 0 0 1 2 2"/>
                <path d="M22 14v2"/>
                <path d="M22 20a2 2 0 0 1-2 2"/>
                <path d="M4 16a2 2 0 0 1-2-2"/>
                <path d="M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/>
                <path d="M8 2h2"/>
            </svg>

            <select class="select w-[100%]" name="amenity[]" required>
                ${optionsMarkup}
            </select>

            <button type="button"
                    class="remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1"
                    title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        `;

 
        row.querySelector('select').selectedIndex = 0;

        container.appendChild(row);

    });

    container.addEventListener('click', function(e){

        const btn = e.target.closest('.remove-amen-btn');

        if(btn){
            btn.closest('.amen-item').remove();
        }

    });

});


//edit boarding house 
document.addEventListener('DOMContentLoaded', function () {
    const addBedBtn1 = document.getElementById('addBedBtn1');
  
    const bedsContainer1 = addBedBtn1.closest('.mb-5'); 


    function getNextBedNumber() {
        const bedInputs = bedsContainer1.querySelectorAll('input[name="bednum[]"]');
        let maxNum = 0;
        
        bedInputs.forEach(input => {
            const val = parseInt(input.value.replace(/\D/g, ''), 10);
            if (!isNaN(val) && val > maxNum) {
                maxNum = val;
            }
        });
        
        return maxNum + 1;
    }


    addBedBtn1.addEventListener('click', function () {
        const nextBedNum1 = getNextBedNumber();

        const bedHTML1 = `
            <div class="bed-item border-b border-base-200 pb-4 mb-5">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-sm bed-title font-medium">
                        Bed ${nextBedNum1}
                    </p>
                    <button type="button" class="btn btn-error btn-xs text-white remove-bed-btn">
                        Remove
                    </button>
                </div>
                
                <!-- Empty for new beds -->
                <input type="hidden" name="boarding_id[]" value="">
                
                <div class="w-full flex flex-col gap-3 mb-3">
                    <span class="w-full">
                        <label class="input w-full flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M2 8h18a2 2 0 0 1 2 2v10"/><path d="M2 17h20"/><path d="M6 8v9"/></svg>
                            <input type="text" name="bednum[]" class="grow" value="Bed ${nextBedNum1}" readonly>
                        </label>
                    </span>

                    <span class="w-full">
                        <p class="text-sm bed-title mb-2">Bed Image *</p>
                        <input type="hidden" name="old_image[]" value="">
                        <input type="file" class="file-input w-full" name="image[]" accept="image/jpeg,image/jpg" required>
                    </span>
                </div>

                <div class="w-full flex flex-col lg:flex-row gap-3 mb-3">
                    <span class="w-full">
                        <p class="mb-2 text-sm">Number of Deck</p>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
                            <select class="select w-[100%]" name="num_deck[]" required>
                                <option value="" disabled selected>Select Number of Deck</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select> 
                        </div> 
                    </span>
                </div>

                <div class="w-full flex flex-col lg:flex-row gap-3">
                    <span class="w-full">
                        <p class="mb-2 text-sm">Status</p>
                        <div class="flex items-center gap-2 border border-gray-300 rounded-md p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <select class="select w-[100%]" name="status[]" required>
                                <option value="Available" selected>Available</option>
                                <option value="Not Available">Not Available</option>
                            </select> 
                        </div> 
                    </span>
                </div>
            </div>
        `;

        // Append the new bed HTML right above the "Add Bed" button block or container
        bedsContainer1.insertAdjacentHTML('beforeend', bedHTML1);
    });


    bedsContainer1.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-bed-btn')) {
            const bedItem1 = e.target.closest('.bed-item');
            if (bedItem1) {
                bedItem1.remove();
            }
        }
    });
});


//tab boarding house room info 
document.addEventListener('DOMContentLoaded', function () {
    const tabs   = document.querySelectorAll('.room-tab');
    const panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('tab-active'));
            panels.forEach(p => p.classList.add('hidden'));

            this.classList.add('tab-active');
            document.querySelector(`.tab-panel[data-panel="${this.dataset.tab}"]`).classList.remove('hidden');
        });
    });
});

//apartment amenities
document.addEventListener('DOMContentLoaded', function () {
    const container1 = document.getElementById('amenities-container1');
    const addamenBtn1 = document.getElementById('addamenBtn1');

    if (!container1 || !addamenBtn1) return;

    addamenBtn1.addEventListener('click', function () {
        const firstSelect = container1.querySelector('select');
        
        if (!firstSelect) {
            console.error("No base select element found to clone options from.");
            return;
        }

        // Use innerHTML directly from the existing select options
        const optionsMarkup = firstSelect.innerHTML;

        const row = document.createElement('div');
        row.className = 'amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2';

        row.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg"
                class="size-4 text-gray-500 ms-2 flex-shrink-0"
                viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 22a2 2 0 0 1-2-2"/>
                <path d="M14 2a2 2 0 0 1 2 2"/>
                <path d="M16 22h-2"/>
                <path d="M2 10V8"/>
                <path d="M2 4a2 2 0 0 1 2-2"/>
                <path d="M20 8a2 2 0 0 1 2 2"/>
                <path d="M22 14v2"/>
                <path d="M22 20a2 2 0 0 1-2 2"/>
                <path d="M4 16a2 2 0 0 1-2-2"/>
                <path d="M8 10a2 2 0 0 1 2 2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/>
                <path d="M8 2h2"/>
            </svg>

            <select class="select w-[100%]" name="apartment_amenity[]" required>
                ${optionsMarkup}
            </select>

            <button type="button"
                    class="remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1"
                    title="Remove">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        `;


        const newSelect = row.querySelector('select');
        newSelect.selectedIndex = 0;

        container1.appendChild(row);
    });

    
    container1.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-amen-btn');
        if (btn) {
            btn.closest('.amen-item').remove();
        }
    });
});