// js/validation.js

document.addEventListener('DOMContentLoaded', function () {

    // --- Register form validation ---
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            const password = document.getElementById('password').value;
            const errorMsg = document.getElementById('error-message');
            errorMsg.textContent = '';

            if (password.length < 6) {
                e.preventDefault();
                errorMsg.textContent = 'Password must be at least 6 characters.';
            }
        });
    }

    // --- Login form validation ---
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const email    = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields.');
            }
        });
    }

});
