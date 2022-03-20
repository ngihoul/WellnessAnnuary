
// Annuary Bien-Être - JS - Registration forms
// Author : Nicolas Gihoul
// Date : March 2022

import { Validator, Format, Locality, HTML_CLASS, MSG } from './functions';


// * Variables * //
if(document.forms['provider'] != undefined) {
    var formInputs = document.forms['provider'];
    var websiteField = document.getElementById('provider_website');
    var phoneNumberField = document.getElementById('provider_phoneNumber');
    var VTANumberField = document.getElementById('provider_VTANumber');
} else if (document.forms['customer'] != undefined) {
    var formInputs = document.forms['customer'];
    var newsletterField = document.getElementById('customer_newsletter');
}

const localityField = document.getElementById('provider_user_locality') || document.getElementById('customer_user_locality');
const emailField = document.getElementById('provider_user_email') || document.getElementById('customer_user_email');
const logoField = document.getElementById('provider_logo') || document.getElementById('customer_logo');
const postCodeField = document.getElementById('provider_user_postCode') || document.getElementById('customer_user_postCode');
const passwordField = document.getElementById('provider_user_password') || document.getElementById('customer_user_password');
const passwordConfirmField = document.getElementById('provider_user_confirmPassword') || document.getElementById('customer_user_confirmPassword');
const privacyPolicyField = document.getElementById('provider_user_privacyPolicy') || document.getElementById('customer_user_privacyPolicy')
const submitBtn = document.getElementById('provider_user_submit') || document.getElementById('customer_user_submit');


// ** Custom functions ** //
const displayImage = field => {
    // Format validated field
    Format.fieldValidated(field);
    // If user change of logo, delete the first choice.
    if(document.getElementById('previewLogo')) {
        document.getElementById('previewLogo').remove();
    }
    // Display the logo next to the field
    let img = field.files[0];
    let newDiv = document.createElement('div');
    let imgContainer = document.createElement('img');
    imgContainer.src = URL.createObjectURL(img);
    newDiv.id = 'previewLogo';
    newDiv.appendChild(imgContainer);
    logoField.parentNode.parentNode.appendChild(newDiv);
}

// ** Scripts ** //
// * Inputs check * //
// Only for Provider Registration form
if(document.forms['provider'] !== undefined) {
    for (let input of formInputs) {
        if (input != websiteField &&
            input != phoneNumberField &&
            input != VTANumberField &&
            input != emailField &&
            input != postCodeField &&
            input != passwordField &&
            input != passwordConfirmField &&
            input != logoField &&
            input != submitBtn) {

            input.addEventListener('focusout', () => {
                Format.notBlank(input);
            });
        }
    }

    websiteField.addEventListener('focusout', () => {
        Format.notWebsite(websiteField);
    });

    phoneNumberField.addEventListener('focusout', () => {
        Format.notPhoneNumber(phoneNumberField);
    });

    VTANumberField.addEventListener('focusout', () => {
        Format.notBelgianVTANumber(VTANumberField);
    });

// Only for Customer registration form
} else {
    for (let input of formInputs) {
        if (input != emailField &&
            input != postCodeField &&
            input != passwordField &&
            input != passwordConfirmField &&
            input != newsletterField &&
            input != privacyPolicyField &&
            input != logoField &&
            input != submitBtn) {

            input.addEventListener('focusout', () => {
                Format.notBlank(input);
            });
        }
    }
}

// For all registration forms
// * Email check * //
emailField.addEventListener('focusout', () => {
    Format.notEmail(emailField);
});

// * Postcode check * //
postCodeField.addEventListener('focusout', () => {
    Format.notPostCode(postCodeField);
});

// * Add message to password * //
if(passwordField) {
    Format.addMessage(passwordField, MSG.NOT_PASSWORD, HTML_CLASS.INFO_MSG);

// * Paswword checks * //
    passwordField.addEventListener('focusout', () => {
        Format.notPassword(passwordField);
    });
}

if(passwordConfirmField) {
    passwordConfirmField.addEventListener('focusout', () => {
        if(!Validator.isBlank(passwordConfirmField)) {
            Format.passwordsNotSimilar(passwordConfirmField, passwordField);
        }
    });
}

// * Logo check * //
logoField.addEventListener('change', () => {
    Validator.isValidImage(logoField) ? displayImage(logoField) : Format.fieldNotValidated(logoField, MSG.NOT_IMAGE);
});

if(privacyPolicyField) {
    // * Privacy Policy check * //
    Format.privacyPolicyNotChecked(privacyPolicyField);
    privacyPolicyField.addEventListener('change', () => {
        Format.privacyPolicyNotChecked(privacyPolicyField);
    })
}

// * Locality checks * //
// Delete all options in Locality <select>
localityField.innerHTML = "";

// Display Locality in relation with the postCode typed by the user
Locality.displayOnInputEvent(postCodeField, localityField);
Locality.displayIfFieldValueEqualsFour(postCodeField, localityField);
