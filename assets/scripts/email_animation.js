   window.addEventListener('DOMContentLoaded', function() {
    const myForm = document.getElementById('myForm');
    
    if (myForm) {
        myForm.addEventListener('submit', function() {
            
           
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