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