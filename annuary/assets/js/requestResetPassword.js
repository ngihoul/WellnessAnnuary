// ** Variables & constants ** //
const emailField = document.getElementById('reset_password_request_form_email');
const submitBtn = document.querySelector('.form-reset-password-btn');

const HTML_CLASS_VALIDATED = 'validated';
const HTML_CLASS_NOT_VALIDATED = 'notValidated';
const HTML_CLASS_LABEL_VALID = 'labelValidated';
const HTML_CLASS_ERROR_MSG = 'error-message';
const HTML_CLASS_INFO_MSG = 'info-message';

const MSG_NOT_EMAIL = 'Format attendu : exemple@exemple.be';

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

// ** Check if email address ** //
const formatIfNotEmail = field => {
    if(isEmail(field)) {
        fieldValidated(field);
        submitBtn.disabled = false;
    } else {
        fieldNotValidated(field, MSG_NOT_EMAIL);
        submitBtn.disabled = true;
    }
}

const isEmail = field => {
    let expression = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
    let regex = new RegExp(expression);

    return field.value.match(regex) ? true : false;
}

// ** Scripts ** //
emailField.addEventListener('input', () => {
    formatIfNotEmail(emailField);
});