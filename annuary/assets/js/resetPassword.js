const passwordField = document.getElementById('change_password_form_plainPassword_first');
const passwordConfirmField = document.getElementById('change_password_form_plainPassword_second');
const submitBtn = document.querySelector('.form-reset-password-btn');

const HTML_CLASS_VALIDATED = 'validated';
const HTML_CLASS_NOT_VALIDATED = 'notValidated';
const HTML_CLASS_LABEL_VALID = 'labelValidated';
const HTML_CLASS_ERROR_MSG = 'error-message';
const HTML_CLASS_INFO_MSG = 'info-message';

const PASSWORD_MIN_LENGTH = 7;
const PASSWORD_MAX_LENGTH = 255;

const MSG_NOT_PASSWORD = 'Le mot de passe doit être de minimum 7 caractères et doit contenir au moins : <br> - une lettre majuscule <br> - une lettre minuscule <br> - un chiffre <br> - un caractère spécial (? ou ! ou @)';
const MSG_NOT_SIMILAR = 'Les mots de passe ne sont pas identiques';

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

// ** Check password - One uppercase letter, one lowercase letter, one digit and one special chars ** /
const formatIfNotPassword = field => {
    if(isPassword(field)) {
        fieldValidated(field);
        submitBtn.disabled = false;
    } else {
        fieldNotValidated(field, MSG_NOT_PASSWORD)
        submitBtn.disabled = true;
    }
}

const isPassword = field => {
    let expression = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!@.*\s).{7,255}$/;
    let regex = new RegExp(expression);

    return field.value.match(regex) && field.value.length >= PASSWORD_MIN_LENGTH && field.value.length <= PASSWORD_MAX_LENGTH ? true : false;
}

const isBlank = field => field.value.length < 1 ? true : false;

// ** check if confirm password is similar to password ** ///
const formatIfPasswordsNotSimilar = field => {
    if(passwordIsSimilar(field)) {
        fieldValidated(field);
        submitBtn.disabled = false;
    } else {
        fieldNotValidated(field, MSG_NOT_SIMILAR);
        submitBtn.disabled = true;
    }
}

const passwordIsSimilar = field => {
    return field.value == passwordField.value ? true : false;
}

// ** Scripts ** //
// Add message to password
addMessage(passwordField, MSG_NOT_PASSWORD, HTML_CLASS_INFO_MSG);

passwordField.addEventListener('focusout', () => {
    formatIfNotPassword(passwordField);
});

passwordConfirmField.addEventListener('focusout', () => {
    if(!isBlank(passwordConfirmField)) {
        formatIfPasswordsNotSimilar(passwordConfirmField);
    }
});