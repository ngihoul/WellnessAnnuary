
// Annuary Bien-Être - JS - Promotions' form
// Author : Nicolas Gihoul
// Date : March 2022

import { Validator, Format, Locality, HTML_CLASS, MSG } from './functions';


// ** Variables ** //
const nameField = document.getElementById('promotion_name');
const descriptionField = document.getElementById('promotion_description');
const PDFField = document.getElementById('promotion_PDFDocument');
const startAtField = document.getElementById('promotion_startAt');
const endAtField = document.getElementById('promotion_endAt');
const displayedFromField = document.getElementById('promotion_displayedFrom');
const displayedUntilField = document.getElementById('promotion_displayedUntil');


// ** Constants ** //
const MIN_DESCRIPTION = 5;


// ** Scripts ** //

// * Check if name is not blank * //
nameField.addEventListener('focusout', () => {
    Format.notBlank(nameField);
});

// * Check if description length is minimum 5 chars * //
descriptionField.addEventListener('focusout', () => {
    Format.notMin(descriptionField, MIN_DESCRIPTION);
});

// * Check if Startat is ok * //
startAtField.addEventListener('input', () => {
    new Date(startAtField.value) >= new Date() ? Format.fieldValidated(startAtField) : Format.fieldNotValidated(startAtField, MSG.DATE_NOT_BEFORE_TODAY)
});

// * Check if StartEnd is ok * //
endAtField.addEventListener('input', () => {
    new Date(endAtField.value) > new Date(startAtField.value) ? Format.fieldValidated(endAtField) : Format.fieldNotValidated(endAtField, MSG.ENDDATE_BEFORE_STARTDATE);
});

// * Check if publishedFrom is OK * //
displayedFromField.addEventListener('input', () => {
    new Date(displayedFromField.value) >= new Date() ? Format.fieldValidated(displayedFromField) : Format.fieldNotValidated(displayedFromField, MSG.DATE_NOT_BEFORE_TODAY)
});

// * Check if StartEnd is ok * //
displayedUntilField.addEventListener('input', () => {
    new Date(displayedUntilField.value) > new Date(displayedFromField.value) ? Format.fieldValidated(displayedUntilField) : Format.fieldNotValidated(displayedUntilField, MSG.ENDDATE_BEFORE_STARTDATE);
});

// * Check if PDF is valid * //
PDFField.addEventListener('input', () => {
   if(PDFField.files[0]) {
       Validator.isValidFile(PDFField) ? Format.fieldValidated(PDFField) : Format.fieldNotValidated(PDFField, MSG.FILE_NOK);
   }
});