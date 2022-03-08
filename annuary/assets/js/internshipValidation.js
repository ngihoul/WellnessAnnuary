
// Annuary Bien-Être - JS - Internships' form
// Author : Nicolas Gihoul
// Date : March 2022

import { Validator, Format, Locality, HTML_CLASS, MSG } from './functions';


// ** Constants ** //
const MIN_DESCRIPTION = 5;


// ** Variables ** //
const nameField = document.getElementById('internship_name');
const descriptionField = document.getElementById('internship_description');
const priceField = document.getElementById('internship_price');
const startAtField = document.getElementById('internship_startAt');
const endAtField = document.getElementById('internship_endAt');
const displayedFromField = document.getElementById('internship_displayedFrom');


// ** Check if name is not blank ** //
nameField.addEventListener('focusout', () => {
    Format.notBlank(nameField);
});

// ** Check if description length is minimum 5 chars ** //
descriptionField.addEventListener('focusout', () => {
    Format.notMin(descriptionField, MIN_DESCRIPTION);
});

// ** Check price ** //
priceField.addEventListener('focusout', () => {
   priceField.value = priceField.value.replace(/,/g, '.')
   Format.notNumeric(priceField);
});

// ** Check if Startat is ok ** //
startAtField.addEventListener('input', () => {
    new Date(startAtField.value) >= new Date() ? Format.fieldValidated(startAtField) : Format.fieldNotValidated(startAtField, MSG.DATE_NOT_BEFORE_TODAY)
});

// ** Check if StartEnd is ok ** //
endAtField.addEventListener('input', () => {
    new Date(endAtField.value) > new Date(startAtField.value) ? Format.fieldValidated(endAtField) : Format.fieldNotValidated(endAtField, MSG.ENDDATE_BEFORE_STARTDATE);
});

// ** Check if publishedFrom if OK ** //
displayedFromField.addEventListener('input', () => {
    new Date(displayedFromField.value) >= new Date() ? Format.fieldValidated(displayedFromField) : Format.fieldNotValidated(displayedFromField, MSG.DATE_NOT_BEFORE_TODAY)
});