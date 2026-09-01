

    document.addEventListener("DOMContentLoaded", function () {
        const inputFields = document.querySelectorAll('.numbers_only');
        inputFields.forEach(function(inputField) {
            inputField.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
            });
        });
        
    });

    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('my_modal_3');
        if (modal) {
            modal.showModal();
        }
    
    });
    
    document.addEventListener("DOMContentLoaded", function () {
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
    });


    document.addEventListener("DOMContentLoaded", function () {
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
    });

    // 5. Admin Dropdown Menu 2
    document.addEventListener("DOMContentLoaded", function () {
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
    });

    // 6. Auto Capitalization ng Unang Letra kada Salita (.autoInput)
    document.addEventListener("DOMContentLoaded", function(){
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
    });

    // 7. Single Input Validation at Paste Restriction para sa Contact Number (.number_only)
    document.addEventListener("DOMContentLoaded", function () {
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
    });

    // 8. Real-time Image Upload Preview (Profile Picture Setup)
    document.addEventListener("DOMContentLoaded", function () {
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


document.addEventListener("DOMContentLoaded", function () {
      const addBedBtn = document.getElementById('addBedBtn');
    const bedsContainer = document.getElementById('beds');

    if (addBedBtn && bedsContainer) {
        addBedBtn.addEventListener('click', function () {
            const bedCount = bedsContainer.querySelectorAll('.bed-item').length + 1;
            const bedLabel = `Bed ${bedCount}`;

            const bedHTML = `
                <div class="bed-item bg-slate-50/50 border border-slate-200/80 rounded-xl p-4 space-y-3 relative group">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-700 bed-title">${bedLabel}</span>
                        <button type="button" class="remove-bed-btn text-rose-400 hover:text-rose-600 text-xs font-medium transition-colors flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            Remove
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Bed Identifier</label>
                            <input type="text" name="bednum[]" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 font-medium focus:outline-none" value="${bedLabel}" readonly>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[11px] font-medium text-slate-500">Number of Deck</label>
                            <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" name="num_deck[]" required>
                                <option value="" disabled selected>Select Number of Deck</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-slate-500">Bed Image</label>
                        <input type="file" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition-all cursor-pointer bg-white border border-slate-200 rounded-lg p-1" name="image[]" accept="image/jpeg,image/jpg" required>
                    </div>
                </div>
            `;

            bedsContainer.insertAdjacentHTML('beforeend', bedHTML);
            bindRemoveBed();
        });
    }


    function bindRemoveBed() {
        document.querySelectorAll('.remove-bed-btn').forEach(btn => {
            btn.onclick = function () {
                this.closest('.bed-item').remove();
                // Renumber beds
                document.querySelectorAll('.bed-item').forEach((item, i) => {
                    const label = `Bed ${i + 1}`;
                    item.querySelector('.bed-title').textContent = label;
                    item.querySelector('input[name="bednum[]"]').value = label;
                });
            };
        });
    }
    bindRemoveBed();
 
});


//amenities count
document.addEventListener("DOMContentLoaded", function () {
   
    const addAmenBtn = document.getElementById('addamenBtn');
    const amenContainer = document.getElementById('amenities-container');

    if (addAmenBtn && amenContainer) {
        addAmenBtn.addEventListener('click', function () {
            const firstSelect = amenContainer.querySelector('select');
            const optionsHTML = firstSelect ? firstSelect.innerHTML : '';

            const amenHTML = `
                <div class="amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2">
                    <select class="w-full px-3 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20" name="amenity[]" required>
                        ${optionsHTML}
                    </select>
                    <button type="button" class="remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0" title="Remove">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
            `;

            amenContainer.insertAdjacentHTML('beforeend', amenHTML);
            bindRemoveAmen();
        });
    }


    function bindRemoveAmen() {
        document.querySelectorAll('.remove-amen-btn').forEach(btn => {
            btn.onclick = function () {
                this.closest('.amen-item').remove();
            };
        });
    }
    bindRemoveAmen(); 
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
     const amenContainer = document.getElementById('amenities-container1');
    const addAmenBtn = document.getElementById('addamenBtn1');

    function bindRemoveButtons() {
        amenContainer.querySelectorAll('.remove-amen-btn').forEach(btn => {
            btn.onclick = function () {
                btn.closest('.amen-item').remove();
            };
        });
    }

    if (addAmenBtn && amenContainer) {
        addAmenBtn.addEventListener('click', function () {
            const firstSelect = amenContainer.querySelector('select');
            const newItem = document.createElement('div');
            newItem.className = 'amen-item flex items-center gap-2 bg-slate-50/50 border border-slate-200 rounded-xl p-2';

            const selectClone = firstSelect.cloneNode(true);
            selectClone.selectedIndex = 0;

            newItem.appendChild(selectClone);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-amen-btn p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs transition-colors shrink-0';
            removeBtn.title = 'Remove';
            removeBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>`;
            newItem.appendChild(removeBtn);

            amenContainer.appendChild(newItem);
            bindRemoveButtons();
        });
    }

    bindRemoveButtons();
});


//splide
document.addEventListener('DOMContentLoaded', function () {
    // Access global Splide and window.splide.Extensions.AutoScroll
    const splide = new Splide('.splide', {
    type: 'loop',
    drag: 'free',
    focus: 'center',
    perPage: 3,
    gap: '1rem',
    pagination: false,
    autoScroll: {
        speed: 0.4,
        pauseOnHover: true,
        pauseOnFocus: false,
    },
    breakpoints: {
        768: { perPage: 2 },
        480: { perPage: 1 }
    }
    });

    // Mount using global extension object
    splide.mount(window.splide.Extensions);
});


document.addEventListener("DOMContentLoaded",function(){
    $(document).ready(function() {
    $('#userSearchInput').on('keyup input', function() {
        var searchValue = $(this).val().toLowerCase().trim();
        var visibleCount = 0;

        $('.user-item').each(function() {
            var userName = $(this).find('.user-name').text().toLowerCase();
            
 
            if (userName.indexOf(searchValue) !== -1) {
                $(this).removeClass('hidden');
                visibleCount++;
            } else {
                $(this).addClass('hidden');
            }
        });

      
        if (visibleCount === 0 && $('.user-item').length > 0) {
            $('#noSearchResults').removeClass('hidden').addClass('flex');
        } else {
            $('#noSearchResults').addClass('hidden').removeClass('flex');
        }
    });
});
});


// chat portal.php
document.addEventListener("DOMContentLoaded", function(){
  const messageForm = document.getElementById('messageForm');
  const messageTextInput = document.getElementById('messageText');
  const sendBtn = document.getElementById('sendBtn');

  // Status indicator elements
  const sendStatus = document.getElementById('sendStatus');
  const sendStatusSpinner = document.getElementById('sendStatusSpinner');
  const sendStatusCheck = document.getElementById('sendStatusCheck');
  const sendStatusText = document.getElementById('sendStatusText');
  let sendStatusHideTimer = null;

  function setSendStatus(state) {
    // state: 'sending' | 'sent' | 'error' | 'hidden'
    clearTimeout(sendStatusHideTimer);

    if (state === 'hidden') {
      sendStatus.classList.add('hidden');
      sendStatus.classList.remove('flex');
      return;
    }

    sendStatus.classList.remove('hidden');
    sendStatus.classList.add('flex');

    if (state === 'sending') {
      sendStatusSpinner.classList.remove('hidden');
      sendStatusCheck.classList.add('hidden');
      sendStatusText.textContent = 'Sending...';
      sendStatusText.className = 'text-base-content/60';
    } else if (state === 'sent') {
      sendStatusSpinner.classList.add('hidden');
      sendStatusCheck.classList.remove('hidden');
      sendStatusText.textContent = 'Sent';
      sendStatusText.className = 'text-success';
      sendStatusCheck.classList.add('text-success');
      // Auto-hide the "Sent" indicator after a couple seconds
      sendStatusHideTimer = setTimeout(() => setSendStatus('hidden'), 2000);
    } else if (state === 'error') {
      sendStatusSpinner.classList.add('hidden');
      sendStatusCheck.classList.add('hidden');
      sendStatusText.textContent = 'Failed to send';
      sendStatusText.className = 'text-error';
      sendStatusHideTimer = setTimeout(() => setSendStatus('hidden'), 3000);
    }
  }

  messageForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(messageForm);
    formData.append('send_message', '1');

    try {
      sendBtn.disabled = true;
      sendBtn.classList.add('loading');
      setSendStatus('sending');

      const response = await fetch('../functions.php', {
        method: 'POST',
        body: formData
      });

      if (response.ok) {

        messageTextInput.value = '';

        window.fileListStore = new DataTransfer();
        window.fileInput.files = window.fileListStore.files;
        window.renderFilePreviews();

        setSendStatus('sent');

        await window.pollMessages();
      } else {
        setSendStatus('error');
      }
    } catch (error) {
      console.error('Send Error:', error);
      setSendStatus('error');
    } finally {
      sendBtn.disabled = false;
      sendBtn.classList.remove('loading');
    }
  });
});


//badge files images chat portal
document.addEventListener("DOMContentLoaded", function(){

  window.fileInput = document.getElementById('chatFileInput');
  const previewContainer = document.getElementById('filePreviewContainer');
  window.fileListStore = new DataTransfer();

  window.fileInput.addEventListener('change', function (e) {
    // Append newly selected files to the DataTransfer store
    Array.from(e.target.files).forEach(file => {
      window.fileListStore.items.add(file);
    });

    // Sync state back to standard input element
    window.fileInput.files = window.fileListStore.files;
    renderFilePreviews();
  });

  function renderFilePreviews() {
    previewContainer.innerHTML = '';

    if (window.fileListStore.files.length === 0) {
      previewContainer.classList.add('hidden');
      return;
    }

    previewContainer.classList.remove('hidden');

    Array.from(window.fileListStore.files).forEach((file, index) => {
      const fileSize = (file.size / (1024 * 1024)).toFixed(2) + ' MB';

      const badge = document.createElement('div');
      badge.className = 'flex items-center gap-1.5 bg-base-100 px-2.5 py-1 rounded-lg border border-base-200 text-xs shadow-xs';

      badge.innerHTML = `
        <span class="badge badge-success badge-xs truncate max-w-[120px]">${file.name}</span>
        <span class="text-[10px] text-base-content/60">${fileSize}</span>
        <button type="button" onclick="removeSelectedFile(${index})" class="btn btn-ghost btn-xs btn-circle h-4 w-4 min-h-0 text-base-content/60 hover:text-error">✕</button>
      `;

      previewContainer.appendChild(badge);
    });
  }

  window.renderFilePreviews = renderFilePreviews;

  function removeSelectedFile(index) {
    const updatedStore = new DataTransfer();

    Array.from(window.fileListStore.files).forEach((file, i) => {
      if (i !== index) {
        updatedStore.items.add(file);
      }
    });

    window.fileListStore = updatedStore;
    window.fileInput.files = window.fileListStore.files;
    renderFilePreviews();
  }
  window.removeSelectedFile = removeSelectedFile;
});


//real time messages
document.addEventListener("DOMContentLoaded", function(){
  const chatUserId = window.chatUserId;

  async function pollMessages() {
    try {
      let res = await fetch(`fetch_chat.php?id=${chatUserId}`);
      let html = await res.text();

      const messageBody = document.getElementById('message_body');
      messageBody.innerHTML = html;

      // Auto scroll to bottom on new message
      messageBody.scrollTop = messageBody.scrollHeight;
    } catch (e) {
      console.error('Polling Error:', e);
    }
  }

  window.pollMessages = pollMessages;

  pollMessages();
  setInterval(pollMessages, 3000);
});



document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('addamenBtn2');
    const container = document.getElementById('amenities-container2');

    if (!addBtn || !container) return;

    // Handle removing an amenity row
    container.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-amen-btn');
        if (removeBtn) {
            const item = removeBtn.closest('.amen-item');
            if (item) {
                item.remove();
            }
        }
    });

    // Add new amenity dropdown dynamically
    addBtn.addEventListener('click', function () {
        const firstSelect = container.querySelector('select[name="cs_amenity[]"]');
        if (!firstSelect) return;

        // Create container wrapper
        const newDiv = document.createElement('div');
        newDiv.className = 'amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2';

        // SVG Icon
        const iconSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 22a2 2 0 0 1-2-2"/><path d="M14 2a2 2 0 0 1 2 2"/><path d="M16 22h-2"/><path d="M2 10V8"/><path d="M2 4a2 2 0 0 1 2-2"/><path d="M20 8a2 2 0 0 1 2 2"/><path d="M22 14v2"/><path d="M22 20a2 2 0 0 1-2 2"/><path d="M4 16a2 2 0 0 1-2-2"/><path d="M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/><path d="M8 2h2"/>
            </svg>`;

        // Clone select element options correctly
        const newSelect = firstSelect.cloneNode(true);
        newSelect.value = ""; // Reset selected value

        // Ensure default placeholder option is selected
        const defaultOption = newSelect.querySelector('option[value=""]');
        if (defaultOption) {
            defaultOption.selected = true;
        }

        // Delete button
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1';
        deleteBtn.title = 'Remove';
        deleteBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>`;

        // Append components
        newDiv.innerHTML = iconSvg;
        newDiv.appendChild(newSelect);
        newDiv.appendChild(deleteBtn);

        container.appendChild(newDiv);
    });
});



//event space amenities
 

document.addEventListener('DOMContentLoaded', function () {
    const addBtn = document.getElementById('addamenBtn3');
    const container = document.getElementById('amenities-container3');

    if (!addBtn || !container) return;

    // Handle removing an amenity row
    container.addEventListener('click', function (e) {
        const removeBtn = e.target.closest('.remove-amen-btn');
        if (removeBtn) {
            const item = removeBtn.closest('.amen-item');
            if (item) {
                item.remove();
            }
        }
    });

    // Add new amenity dropdown dynamically
    addBtn.addEventListener('click', function () {
        const firstSelect = container.querySelector('select[name="es_amenity[]"]');
        if (!firstSelect) return;

        // Create container wrapper
        const newDiv = document.createElement('div');
        newDiv.className = 'amen-item flex items-center gap-2 border border-gray-300 rounded-md p-1 mb-2';

        // SVG Icon
        const iconSvg = `
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-gray-500 ms-2 flex-shrink-0"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 22a2 2 0 0 1-2-2"/><path d="M14 2a2 2 0 0 1 2 2"/><path d="M16 22h-2"/><path d="M2 10V8"/><path d="M2 4a2 2 0 0 1 2-2"/><path d="M20 8a2 2 0 0 1 2 2"/><path d="M22 14v2"/><path d="M22 20a2 2 0 0 1-2 2"/><path d="M4 16a2 2 0 0 1-2-2"/><path d="M8 10a2 2 0 0 1 2-2h5a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2H9a1 1 0 0 1-1-1z"/><path d="M8 2h2"/>
            </svg>`;

        // Clone select element options correctly
        const newSelect = firstSelect.cloneNode(true);
        newSelect.value = ""; // Reset selected value

        // Ensure default placeholder option is selected
        const defaultOption = newSelect.querySelector('option[value=""]');
        if (defaultOption) {
            defaultOption.selected = true;
        }

        // Delete button
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'remove-amen-btn btn btn-error btn-sm text-white me-1 px-2 py-1';
        deleteBtn.title = 'Remove';
        deleteBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>`;

        // Append components
        newDiv.innerHTML = iconSvg;
        newDiv.appendChild(newSelect);
        newDiv.appendChild(deleteBtn);

        container.appendChild(newDiv);
    });
});


//users index.php
  document.addEventListener('DOMContentLoaded', function () {
    // Toggle "Show all" visited properties
    const toggleBtn = document.getElementById('toggleVisitedBtn');
    if (toggleBtn) {
      const toggleLabel = document.getElementById('toggleVisitedLabel');
      const toggleIcon = document.getElementById('toggleVisitedIcon');
      const hiddenRows = document.querySelectorAll('#visitedList .visited-row.hidden');
      const totalCount = document.querySelectorAll('#visitedList .visited-row').length;
      let expanded = false;

      toggleBtn.addEventListener('click', () => {
        expanded = !expanded;

        hiddenRows.forEach(row => {
          row.classList.toggle('hidden', !expanded);
        });

        toggleLabel.textContent = expanded ? 'Show less' : `Show all ${totalCount}`;
        toggleIcon.classList.toggle('rotate-180', expanded);
      });
    }
  });
//admin/report_info.php
document.addEventListener("DOMContentLoaded", function () {
  const tabButtons = document.querySelectorAll(".amenity-tab-btn");
  const tabPanels  = document.querySelectorAll(".amenity-tab-panel");

  tabButtons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      const target = btn.getAttribute("data-tab");

      tabButtons.forEach(function (b) {
        b.classList.remove("border-[#0d9488]", "text-[#0d9488]");
        b.classList.add("border-transparent", "text-gray-500");
      });
      btn.classList.remove("border-transparent", "text-gray-500");
      btn.classList.add("border-[#0d9488]", "text-[#0d9488]");

      tabPanels.forEach(function (panel) {
        if (panel.getAttribute("data-tab-panel") === target) {
          panel.classList.remove("hidden");
        } else {
          panel.classList.add("hidden");
        }
      });
    });
  });
});