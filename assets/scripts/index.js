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
    
  
   

    //hide show password
    function toggleRepeatPassword() {
        const passwordField = document.getElementById("repeat_password");
        const repeat_pass = document.getElementById("repeat_pass");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            repeat_pass.src="assets/images/view-icon.png";
        } else {
            passwordField.type = "password";
            repeat_pass.src="assets/images/hide-icon.png";
        }
    }
    
    //hide show password
    function togglePasswords() {
        const password = document.getElementById("password");
        const pass = document.getElementById("pass");
        if (password.type === "password") {
            password.type = "text";
            pass.src="assets/images/view-icon.png";
        } else {
            password.type = "password";
            pass.src="assets/images/hide-icon.png";
        }
    }
    


     //hide show password
    function toggletPassword() {
        const password = document.getElementById("password");
        const pass = document.getElementById("pass");
        if (password.type === "password") {
            password.type = "text";
            pass.src="assets/images/view-icon.png";
        } else {
            password.type = "password";
            pass.src="assets/images/hide-icon.png";
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



    /*email animation
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


       if (myForm) {
            myForm.addEventListener('submit', (event) => {
                showLoading();
            });
        }
    }); */


//show modal
    document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('my_modal_3');
    if (modal) {
      modal.showModal();
    }
  });
  

//number only allowed
      window.addEventListener('DOMContentLoaded', function() {
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
