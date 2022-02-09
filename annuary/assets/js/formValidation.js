// *********************** //
// *** Form validation *** //
// *********************** //

// * Variables * //
if(document.forms['provider'] !== undefined) {
    var formInputs = document.forms['provider'];
    var websiteField = document.getElementById('provider_website');
    var phoneNumberField = document.getElementById('provider_phoneNumber');
    var VTANumberField = document.getElementById('provider_VTANumber');
} else if (document.forms['customer'] !== undefined) {
    var formInputs = document.forms['customer'];
    var newsletterField = document.getElementById('customer_newsletter');
}

const emailField = document.getElementById('provider_user_email') || document.getElementById('customer_user_email');
const logoField = document.getElementById('provider_logo') || document.getElementById('customer_logo');
const postCodeField = document.getElementById('provider_user_postCode') || document.getElementById('customer_user_postCode');
const passwordField = document.getElementById('provider_user_password') || document.getElementById('customer_user_password');
const passwordConfirmField = document.getElementById('provider_user_confirmPassword') || document.getElementById('customer_user_confirmPassword');
const privacyPolicyField = document.getElementById('provider_user_privacyPolicy') || document.getElementById('customer_user_privacyPolicy')
const submitBtn = document.getElementById('provider_user_submit') || document.getElementById('customer_user_submit');

// * Constants * //
const PREFIX_BELGIUM = '+32';
const HTML_CLASS_VALIDATED = 'validated';
const HTML_CLASS_NOT_VALIDATED = 'notValidated';
const HTML_CLASS_ERROR_MSG = 'error-message';
const HTML_CLASS_INFO_MSG = 'info-message';
const HTML_CLASS_LABEL_VALID = 'labelValidated';
const HTML_CLASS_PRIVACY_VALIDATED = 'privacyValidated';
const HTML_CLASS_PRIVACY_NOT_VALIDATED = 'privacyNotValidated';
const PHONENUMBER_MIN_LENGTH = 12;
const PHONENUMBER_MAX_LENGTH = 13;
const VTANUMBER_MIN_LENGTH = 12;
const VTANUMBER_MAX_LENGTH = 13;
const POSTCODE_LENGTH = 4;
const PASSWORD_MIN_LENGTH = 7;
const PASSWORD_MAX_LENGTH = 255;
const IMAGE_EXTENSIONS_ALLOWED = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

// * Messages * //
const MSG_BLANK = 'Veuillez compléter ce champ';
const MSG_NOT_WEBSITE = 'Format attendu : www.exemple.be';
const MSG_NOT_PHONENUMBER = 'Format attendu : +32 X XXX XX XX ou +32 XXX XX XX XX';
const MSG_NOT_VTANUMBER = 'Format attendu : BE 0 XXX XXX XXX ou BE 0 XXX XXX XXXX';
const MSG_NOT_EMAIL = 'Format attendu : exemple@exemple.be';
const MSG_NOT_POSTCODE = 'Format attendu : XXXX';
const MSG_NOT_PASSWORD = 'Le mot de passe doit être de minimum 7 caractères et doit contenir au moins : <br> - une lettre majuscule <br> - une lettre minuscule <br> - un chiffre <br> - un caractère spécial (? ou ! ou @)';
const MSG_NOT_SIMILAR = 'Les mots de passe ne sont pas identiques';
const MSG_NOT_IMAGE = 'L\'image doit être au format JPG, PNG ou GIF et ne pas dépasser 1Mo';

// ** Functions ** //
// * Add style if field is validated or not * //
const fieldValidated = field => {
    field.className = HTML_CLASS_VALIDATED;
    // Delete error-message
    if(field.nextSibling) {
        field.nextSibling.remove();
    }
    // Add checked icon before label
    field.previousSibling.classList.add(HTML_CLASS_LABEL_VALID);
}

const fieldNotValidated = (field, message) => {
    field.className = HTML_CLASS_NOT_VALIDATED;
    if(!field.nextSibling) {
        addMessage(field, message);
    // If there is already a info-message
    } else if (field.nextSibling.classList.contains(HTML_CLASS_INFO_MSG)) {
        field.nextSibling.className = HTML_CLASS_ERROR_MSG;
    }
    // In case user switch to valid to invalid value
    if(field.previousSibling.classList.contains(HTML_CLASS_LABEL_VALID)) {
        field.previousSibling.classList.remove(HTML_CLASS_LABEL_VALID);
    }
}

// * Add Message * //
const addMessage = (field, message, htmlClass = HTML_CLASS_ERROR_MSG) => {
    let newElement = document.createElement('p');
    newElement.innerHTML = message;
    newElement.className = htmlClass;
    field.parentNode.insertBefore(newElement, field.nextSibling);
}

// ** Check blank fields ** //
const formatIfNotBlank = field => isBlank(field) ? fieldNotValidated(field, MSG_BLANK) : fieldValidated(field);

const isBlank = field => field.value.length < 1 ? true : false;

// ** Check phoneNumber ** //
const formatIfNotPhoneNumber = field => {
    // Delete whitespaces
    field.value = escapeSpaces(field.value);

    // If starts with 0 replace it by +32
    field.value = startWithZero(field.value) ? field.value.replace('0', PREFIX_BELGIUM) : field.value;

    // If not correspond to a belgian phonenumber, format field
    isBelgianPhoneNumber(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_PHONENUMBER);
}

const escapeSpaces = string => string.replace(/\s/g, '');

const startWithZero = string => string.charAt(0) == 0 ? true : false;

const isBelgianPhoneNumber = field => {
    return field.value.startsWith(PREFIX_BELGIUM) && field.value.length >= PHONENUMBER_MIN_LENGTH && field.value.length <= PHONENUMBER_MAX_LENGTH ? true : false;
}

// ** Check if website ** //
const formatIfNotWebsite = field => isWebsite(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_WEBSITE);

const isWebsite = field => {
    let expression = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
    let regex = new RegExp(expression);

    return field.value.match(regex) ? true : false;
}

// ** Check if VTA Number ** //
const formatIfNotBelgianVTANumber = field => {
    // Delete spaces & transform to upperCase
    field.value = escapeSpaces(field.value).toUpperCase();

    if(startsWithBE0(field) && (field.value.length == VTANUMBER_MIN_LENGTH || field.value.length == VTANUMBER_MAX_LENGTH)) {
        fieldValidated(field);
    } else {
        fieldNotValidated(field, MSG_NOT_VTANUMBER);
    }
}

const startsWithBE0 = field => {
    return field.value.startsWith('BE0') ? true : false;
}

// ** Check if email address ** //
const formatIfNotEmail = field => isEmail(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_EMAIL);

const isEmail = field => {
    let expression = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    let regex = new RegExp(expression);

    return field.value.match(regex) ? true : false;
}

// ** Check if postCode is valid ** //
const formatIfNotPostCode = field => isBelgianPostCode(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_POSTCODE);

const isBelgianPostCode = field => field.value.length == POSTCODE_LENGTH ? true : false;

// ** Check password - One uppercase letter, one lowercase letter, one digit and one special chars ** /
const formatIfNotPassword = field => isPassword(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_PASSWORD);

const isPassword = field => {
    let expression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!@.*\s).{7,255}$/;
    let regex = new RegExp(expression);

    return field.value.match(regex) && field.value.length >= PASSWORD_MIN_LENGTH && field.value.length <= PASSWORD_MAX_LENGTH ? true : false;
}

// ** check if confirm password is similar to password ** ///
const formatIfPasswordsNotSimilar = field => passwordIsSimilar(field) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_SIMILAR);

const passwordIsSimilar = field => {
    return field.value == passwordField.value ? true : false;
}

// ** check if logo is valid ** //
logoField.addEventListener('change', () => {
   isValidImage(logoField) ? displayImage(logoField) : fieldNotValidated(logoField, MSG_NOT_IMAGE);
});

const displayImage = field => {
    // Format validated field
    fieldValidated(field);
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

const isValidImage = field => {
    return IMAGE_EXTENSIONS_ALLOWED.exec(field.value) && field.files[0].size <= 1024000 ? true : false;
}

// ** Scripts ** //
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
                formatIfNotBlank(input);
            });
        }
    }

    websiteField.addEventListener('focusout', () => {
        formatIfNotWebsite(websiteField);
    });

    phoneNumberField.addEventListener('focusout', () => {
        formatIfNotPhoneNumber(phoneNumberField);
    });

    VTANumberField.addEventListener('focusout', () => {
        formatIfNotBelgianVTANumber(VTANumberField);
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

            input.addEventListener('input', () => {
                formatIfNotBlank(input);
            });
        }
    }
}

// For all registration forms
emailField.addEventListener('input', () => {
    formatIfNotEmail(emailField);
});

postCodeField.addEventListener('input', () => {
    formatIfNotPostCode(postCodeField);
});

// Add message to password
addMessage(passwordField, MSG_NOT_PASSWORD, HTML_CLASS_INFO_MSG);

passwordField.addEventListener('input', () => {
    formatIfNotPassword(passwordField);
});

passwordConfirmField.addEventListener('input', () => {
    if(!isBlank(passwordConfirmField)) {
        formatIfPasswordsNotSimilar(passwordConfirmField);
    }
});

// PrivacyPolicy validation
const formatIfPrivacyPolicyNotChecked = field => {
    if(field.checked) {
        field.previousSibling.classList.add(HTML_CLASS_PRIVACY_VALIDATED);
        field.previousSibling.classList.remove(HTML_CLASS_PRIVACY_NOT_VALIDATED);
    } else {
        field.previousSibling.classList.add(HTML_CLASS_PRIVACY_NOT_VALIDATED);
        field.previousSibling.classList.remove(HTML_CLASS_PRIVACY_VALIDATED);
    }
}

formatIfPrivacyPolicyNotChecked(privacyPolicyField);
// On change
privacyPolicyField.addEventListener('change', () => {
    formatIfPrivacyPolicyNotChecked(privacyPolicyField);
})








