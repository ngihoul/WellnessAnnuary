
// Annuary Bien-Être - JS - Reset password form
// Author : Nicolas Gihoul
// Date : March 2022

import { Validator, Format, Locality, HTML_CLASS, MSG } from './functions';


// ** Variables ** //

const passwordField = document.getElementById('change_password_form_plainPassword_first');
const passwordConfirmField = document.getElementById('change_password_form_plainPassword_second');
const submitBtn = document.querySelector('.form-reset-password-btn');


// ** Scripts ** //

// Add message to password
Format.addMessage(passwordField, MSG.NOT_PASSWORD, HTML_CLASS.INFO_MSG);

// Check if password is valid
passwordField.addEventListener('focusout', () => {
    Format.notPassword(passwordField);
});

// Check if password = password confirmation
passwordConfirmField.addEventListener('focusout', () => {
    if(!Validator.isBlank(passwordConfirmField)) {
        Format.passwordsNotSimilar(passwordConfirmField, passwordField);
    }
});