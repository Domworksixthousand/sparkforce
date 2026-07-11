//number only allowed
    const inputFields = document.querySelectorAll('.numbers_only');
    inputFields.forEach(function(inputField) {
        inputField.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });
    });


    //auto uppercase
     document.addEventListener("DOMContentLoaded", () => {
        const inputFields = document.querySelectorAll('.autoInput');

        inputFields.forEach(inputField => {
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
    
     //auto 09
    document.addEventListener("DOMContentLoaded", function () {
        const phoneInput = document.getElementById("contactNumber");
        const form = phoneInput.closest("form"); 
        phoneInput.addEventListener("input", function () {
            let value = this.value.replace(/\D/g, "");
            if (value.length > 0 && !value.startsWith("0")) {
                value = "09" + value;
            } else if (value.length > 1 && !value.startsWith("09")) {
                value = "09" + value.substring(2);
            }
            this.value = value;
        });


        if (form) {
            form.addEventListener("submit", function (e) {
                const value = phoneInput.value;

                if (value.length !== 11 || !value.startsWith("09")) {
                    e.preventDefault();
                                
                CoolAlert.show({
                    toast: true,
                    icon: "error",
                    text: "Please enter a valid 11-digit contact number starting with 09.!",
                    position: "top-right",
                    });
       
                    phoneInput.focus();
                    phoneInput.classList.add("input-error");
                }
            });
        }
    });

    //hide show password
    function toggleRepeatPassword() {
        const passwordField = document.getElementById("repeat_password");
        const repeat_pass = document.getElementById("repeat_pass");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            repeat_pass.src="assets/images/hide-icon.png";
        } else {
            passwordField.type = "password";
            repeat_pass.src="assets/images/view-icon.png";
        }
    }


     //hide show password
    function toggletPassword() {
        const password = document.getElementById("password");
        const pass = document.getElementById("pass");
        if (password.type === "password") {
            password.type = "text";
            pass.src="assets/images/hide-icon.png";
        } else {
            password.type = "password";
            pass.src="assets/images/view-icon.png";
        }
    }
    
//scroll style
      const navbar = document.querySelector('.navbar');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
     
      navbar.classList.remove('glass');
      navbar.classList.add('bg-[#0d9488]');

    
    
    } else {
     
      navbar.classList.add('glass');
      navbar.classList.remove('bg-[#0d9488]');

   
    }
  });

//check an password if valid and secure
  document.addEventListener("DOMContentLoaded", () => {

    const registrationForm = document.getElementById("myForm"); 
    const passwordInput = document.getElementById("password");

    registrationForm.addEventListener("submit", (e) => {
        const value = passwordInput.value;

        // Validation Rules
        const hasMinLength = value.length >= 7;
        const hasNumber = /[0-9]/.test(value);
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(value);

        if (hasMinLength && hasNumber && hasSymbol) {
         
            passwordInput.setCustomValidity(""); 
            passwordInput.classList.remove("border-error"); 
        } else {
          
            e.preventDefault(); 

            CoolAlert.show({
                toast: true,
                icon: "error",
                text: "Password must be at least 7 characters long, contain 1 number, and 1 symbol!",
                position: "top-right",
            });
            
            passwordInput.classList.add("border-error"); 
            passwordInput.focus();
        }
    });
  });



    /*email animation */
    document.addEventListener("DOMContentLoaded", () => {
        const myForm = document.getElementById('myForm');
        const animation = document.getElementById('animation_id');

    
        function showLoading() {
            document.body.classList.add('overflow-hidden');
            animation.classList.remove("hidden");
        }

    
        function hideLoading() {
            document.body.classList.remove('overflow-hidden');
            animation.classList.add("hidden");
        }


        myForm.addEventListener('submit', (event) => {
            showLoading();
        
        });
    });