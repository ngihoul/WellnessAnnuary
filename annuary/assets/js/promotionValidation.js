const nameField = document.getElementById('promotion_name');
const descriptionField = document.getElementById('promotion_description');
const PDFField = document.getElementById('promotion_PDFDocument');
const startAtField = document.getElementById('promotion_startAt');
const endAtField = document.getElementById('promotion_endAt');
const displayedFromField = document.getElementById('promotion_displayedFrom');
const displayedUntilField = document.getElementById('promotion_displayedUntil');

const HTML_CLASS_VALIDATED = 'validated';
const HTML_CLASS_NOT_VALIDATED = 'notValidated';
const HTML_CLASS_ERROR_MSG = 'error-message';
const HTML_CLASS_INFO_MSG = 'info-message';
const HTML_CLASS_LABEL_VALID = 'labelValidated';

const FILE_EXTENSIONS_ALLOWED = /(\.pdf)$/i;
const MIN_DESCRIPTION = 5;

const MSG_NOT_MIN_DESCRIPTION = `Introduiez une description d'au moins ${MIN_DESCRIPTION} caractères.`;
const MSG_BLANK = 'Veuillez compléter ce champ';
const MSG_NOT_NUMERIC = 'Le montant doit être un chiffre';
const MSG_DATE_NOT_BEFORE_TODAY = 'La date doit être au minimum celle de demain';
const MSG_ENDDATE_BEFORE_STARTDATE = 'La date de fin doit être plus grande que celle de début';
const FILE_NOK = 'Le fichier doit être au format PDF et être inférieur à 10Mo';

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

// ** Check if the length is min X chars ** //
const formatIfNotMin = (field, min) => isLengthMinimum(field, min) ? fieldValidated(field) : fieldNotValidated(field, MSG_NOT_MIN_DESCRIPTION) ;

const isLengthMinimum = (field, minLength) => field.value.length >= minLength ? true : false;

// ** Check if name is not blank ** //
nameField.addEventListener('focusout', () => {
    formatIfNotBlank(nameField, MSG_BLANK);
});
// ** Check if description length is minimum 5 chars ** //
descriptionField.addEventListener('focusout', () => {
    formatIfNotMin(descriptionField, MIN_DESCRIPTION);
});
// ** Check if Startat is ok ** //
startAtField.addEventListener('input', () => {
    new Date(startAtField.value) >= new Date() ? fieldValidated(startAtField) : fieldNotValidated(startAtField, MSG_DATE_NOT_BEFORE_TODAY)
});
// ** Check if StartEnd is ok ** //
endAtField.addEventListener('input', () => {
    new Date(endAtField.value) > new Date(startAtField.value) ? fieldValidated(endAtField) : fieldNotValidated(endAtField, MSG_ENDDATE_BEFORE_STARTDATE);
});
// ** Check if publishedFrom is OK ** //
displayedFromField.addEventListener('input', () => {
    new Date(displayedFromField.value) >= new Date() ? fieldValidated(displayedFromField) : fieldNotValidated(displayedFromField, MSG_DATE_NOT_BEFORE_TODAY)
});
// ** Chech if publishedUntil is OK ** //
// ** Check if StartEnd is ok ** //
displayedUntilField.addEventListener('input', () => {
    new Date(displayedUntilField.value) > new Date(displayedFromField.value) ? fieldValidated(displayedUntilField) : fieldNotValidated(displayedUntilField, MSG_ENDDATE_BEFORE_STARTDATE);
});

// ** Check if PDF is ok ** //
const isValidFile = field => {
    return FILE_EXTENSIONS_ALLOWED.exec(field.value) && field.files[0].size <= 10240000 ? true : false;
}
PDFField.addEventListener('input', () => {
   if(PDFField.files[0]) {
       isValidFile(PDFField) ? fieldValidated(PDFField) : fieldNotValidated(PDFField, FILE_NOK);
   }
});