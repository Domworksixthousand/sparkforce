window.addEventListener('DOMContentLoaded', function () {
       



    const myForm = document.getElementById('myForm');
    
    if (myForm) {
        myForm.addEventListener('submit', function() {
            
                (function() {
                    const akingElement = document.getElementById('my_modal_3');
                    if (akingElement) {
                
                        akingElement.classList.remove('modal-open');
                        
                    
                        akingElement.classList.add('!hidden', 'hidden');
                    }
                })();
            
            CoolAlert.show({
                title: 'Please Wait...',
                text: 'Sending email, please do not close the page.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false, 
                showCancelButton: false,
             
                didOpen: () => {
                
                    if (typeof CoolAlert.showLoading === 'function') {
                        CoolAlert.showLoading();
                    }
                }
            });
            
        });
    }
});



   window.addEventListener('DOMContentLoaded', function() {
    const myForm = document.getElementById('signin_form');
    
    if (myForm) {
        myForm.addEventListener('submit', function() {
            
           
            CoolAlert.show({
                title: 'Please Wait...',
                text: 'Signing In',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false, 
                showCancelButton: false,
                didOpen: () => {
                
                    if (typeof CoolAlert.showLoading === 'function') {
                        CoolAlert.showLoading();
                    }
                }
            });
            
        });
    }
});