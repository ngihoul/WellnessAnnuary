
// Annuary Bien-Être - JS - Request password form
// Author : Nicolas Gihoul
// Date : March 2022

import { Format } from './functions';


// ** Variables & constants ** //
const emailField = document.getElementById('reset_password_request_form_email');
const submitBtn = document.querySelector('.form-reset-password-btn');


// ** Scripts ** //
emailField.addEventListener('input', () => {
    Format.notEmail(emailField);
});