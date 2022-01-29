// *************************** //
// **** Registration Form **** //
// *************************** //

// ***************************** //
// *** Variables & constants *** //
// ***************************** //
const postCodeField = document.getElementById('provider_user_postCode') || document.getElementById('customer_user_postCode');
const localityField = document.getElementById('provider_user_locality') || document.getElementById('customer_user_locality');

// ***************** //
// *** Functions *** //
// ***************** //

// **************** //
// ** Localities ** //
// **************** //

// * Capitalize strings * //
const capitalizeFirstLetter = (string) => {
    string = string.toLowerCase();
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// * Display Localities if there is a change on an input * //
const displayLocalitiesOnInputEvent = (field) => {
    field.addEventListener('input', () => {
        if(field.value.length > 0) {
            fetchAndDisplayLocalities(field);
        } else {
            localityField.innerHTML = "";
        }
    });
}

// * Display localities if input value has already a length value of 4 * //
const displayLocalitiesIfFieldValueEqualsFour = (field) => {
    if(field.value.length == 4) {
        fetchAndDisplayLocalities(field);
    }
}

// * Fetch and Display Locailities in appropriate field * //
const fetchAndDisplayLocalities = (field) => {
    fetch(`/search/where/?w=${field.value.trim()}`)
    .then(response => response.json())
    .then(data => displayLocalities(localityField, data));
}

// * Display properly localities * //
const displayLocalities = (localityField, data) => {
    localityField.innerHTML = "";
    data.forEach((element) => {
        console.log(element);
        localityField.innerHTML += `<option value="${element.id}">${capitalizeFirstLetter(element.locality)}</option>`;
    });
}





// *************** //
// *** SCRIPTS *** //
// *************** //

// * Delete all options in Locality <select> * //
localityField.innerHTML = "";

// * Display Locality in relation with the postCode typed by the user * //
displayLocalitiesOnInputEvent(postCodeField);
displayLocalitiesIfFieldValueEqualsFour(postCodeField);