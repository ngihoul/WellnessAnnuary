// *********************** //
// *** Form validation *** //
// *********************** //

// ** Variables & constants ** //
const providerInputs = document.forms['provider'].getElementsByTagName('input') || document.forms['customer'].getElementsByTagName('input');
const websiteField = document.getElementById('provider_website');
const phoneNumberField = document.getElementById('provider_phoneNumber');
const VTANumberField = document.getElementById('provider_VTANumber');
const emailField = document.getElementById('provider_user_email');
const postCodeField = document.getElementById('provider_user_postCode');
const passwordField = document.getElementById('provider_user_password');
const passwordConfirmField = document.getElementById('provider_user_confirmPassword');

const MSG_BLANK = 'Veuillez compléter ce champ';
const MSG_NOT_WEBSITE = 'Format attendu : www.exemple.be';
const MSG_NOT_PHONENUMBER = 'Format attendu : +32 X XXX XX XX ou +32 XXX XX XX XX';
const MSG_NOT_VTANUMBER = 'Format attendu : BE0 X XXX XXX XXX ou BE X XXX XXX XXXX';
const MSG_NOT_EMAIL = 'Format attendu : exemple@exemple.be';
const MSG_NOT_POSTCODE = 'Format attendu : XXXX';
const MSG_NOT_PASSWORD = 'Le mot de passe doit être de minimum 7 caractères et doit contenir : <br> - une lettre majuscule <br> - une lettre minuscule <br> - un chiffre <br> - un caractère spécial (? ou ! ou @)';
const MSG_NOT_SIMILAR = 'Les mots de passe ne sont pas identiques';

const PREFIX_BELGIUM = '+32';

// ** Functions ** //
// * Add style if field is validated or not * //
const fieldValidated = field => field.className = 'validated';
const fieldNotValidated = field => field.className = 'notValidated';

// * Add Message * //
const addMessage = (field, message) => {
    let newElement = document.createElement('p');
    newElement.innerHTML = message;
    newElement.className = 'error-message';
    field.parentNode.insertBefore(newElement, field.nextSibling);
}

// ** Check blank fields ** //
// * Format field if empty
const formatIfBlank = (field) => {
    if(blank(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_BLANK);
        }
    } else {
        fieldValidated(field);
        field.nextSibling.remove();
    }
}

// * Check if input is empty
const blank = field => field.value.length < 1 ? true : false;

// ** Check phoneNumber ** //
// * Format field if not a valid phoneNumber * /
const formatFieldIfNotPhoneNumber = field => {
    // Delete whitespaces
    field.value = escapeSpaces(field.value);

    // If starts with 0 replace it by +32
    if(startWithZero(field.value)) {
        field.value = field.value.replace('0', PREFIX_BELGIUM);
    }

    // If not correspond to a belgian phonenumber, format field
    if(!isBelgianPhoneNumber(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_PHONENUMBER);
        }
    } else {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    }
}

// * Delete whitespaces in string * //
const escapeSpaces = string => string.replace(/\s/g, '');

// * check if string start with 0 * //
const startWithZero = string => string.charAt(0) == 0 ? true : false;

// * Check if string start with +32 * //
const isBelgianPhoneNumber = field => {
    return field.value.startsWith(PREFIX_BELGIUM) && field.value.length >= 11 && field.value.length <= 12 ? true : false;
}

// ** Check if website ** //
// * Format field if not a website * //
const formatIfNotWebsite = (field) => {
    if(!isWebsite(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_WEBSITE);
        }
    } else {
        fieldValidated(field);
        field.nextSibling.remove();
    }
}

// * Check if website field is a url * //
const isWebsite = field => {
    let expression = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
    let regex = new RegExp(expression);

    return field.value.match(regex) ? true : false;
}

// ** Check if VTA Number ** //
// * Check if is a valid Belgian VTA Number * /
const formatIfNotBelgianVTANumber = field => {
    field.value = escapeSpaces(field.value);

    if(startWithBE0(field) && (field.value.length == 12 || field.value.length == 13)) {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    } else {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_VTANUMBER);
        }
    }
}

// * Check if starts with BE0 * /
const startWithBE0 = field => field.value.startsWith('BE0') ? true : false;

// ** Check if email address ** //
const formatIfNotEmail = (field) => {
    if(!isEmail(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_EMAIL);
        }
    } else {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    }
}
// * check if value of field is a valid email * /
const isEmail = field => {
    let expression = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    let regex = new RegExp(expression);

    return field.value.match(regex) ? true : false;
}

// ** Check if postCode is valid ** //
const formatIfNotPostCode = (field) => {
    if(!isBelgianPostCode(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_POSTCODE);
        }
    } else {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    }
}

const isBelgianPostCode = field => field.value.length == 4 ? true : false;

// ** Check password - One uppercase letter, one lowercase letter, one digit and one special chars ** /
const formatIfNotPassword = (field) => {
    if(!isPassword(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_PASSWORD);
        }
    } else {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    }
}

const isPassword = field => {
    let expression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!@.*\s).{7,255}$/;
    let regex = new RegExp(expression);

    return field.value.match(regex) && field.value.length >= 7 && field.value.length <= 255 ? true : false;
}

// ** check if confirm password is similar to password ** ///
const formatIfPasswordsNotSimilar = (field) => {
    if(passwordIsSimilar(field)) {
        fieldNotValidated(field);
        if(!field.nextSibling) {
            addMessage(field, MSG_NOT_SIMILAR);
        }
    } else {
        fieldValidated(field);
        if(field.nextSibling) {
            field.nextSibling.remove();
        }
    }
}

const passwordIsSimilar = field => {
    return field.value != passwordField.value ? false : true;
}

// *** Scripts *** //
// * If field is blank * //
for(let input of providerInputs) {
    if(input != websiteField &&
        input != phoneNumberField &&
        input != VTANumberField &&
        input != emailField &&
        input != postCodeField &&
        input != passwordField &&
        input != passwordConfirmField ) {
            input.addEventListener('focusout', () => {
                formatIfBlank(input);
        });
    }
}

websiteField.addEventListener('focusout', () => {
    formatIfNotWebsite(websiteField);
});

phoneNumberField.addEventListener('focusout', () => {
    formatFieldIfNotPhoneNumber(phoneNumberField);
});

VTANumberField.addEventListener('focusout', () => {
    formatIfNotBelgianVTANumber(VTANumberField);
});

emailField.addEventListener('focusout', () => {
    formatIfNotEmail(emailField);
});

postCodeField.addEventListener('focusout', () => {
    formatIfNotPostCode(postCodeField);
});

passwordField.addEventListener('focusout', () => {
    formatIfNotPassword(passwordField);
});

passwordConfirmField.addEventListener('focusout', () => {
    formatIfPasswordsNotSimilar(passwordConfirmField);
});





