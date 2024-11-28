window.addEventListener('DOMContentLoaded', function(){

    const messages = document.querySelectorAll('.message-box');

    messages.forEach((msg) => {

        setTimeout(() => {
            msg.style.display = 'none';

        }, 3000);
    });
});

window.addEventListener('DOMContentLoaded', function(){

    const messages = document.querySelectorAll('.error-message');

    messages.forEach((msg) => {

        setTimeout(() => {
            msg.style.display = 'none';

        }, 3000);
    });
});

window.onload = function() {

    const errorPopup = document.getElementById('error-popup');

    if (errorPopup) {
        errorPopup.style.display = 'block'; 
        setTimeout(() => {
            errorPopup.style.display = 'none'; 
        }, 3000);
    }
};


