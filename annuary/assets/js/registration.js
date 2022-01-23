const fetchAndDisplayAutoCompletion = (field) => {
    field.addEventListener('input', () => {
        if(field.value.length == 4) {
            fetch(`/search/where/?w=${field.value.trim()}`)
                .then((response) => {
                    if(!response) {
                        throw new Error(`erreur HTTP! statut: ${reponse.status}`);
                    }
                    return response.json();
                }).then((data) => {
                displayLocality(data);
            });
        }
    });
}

const displayLocality = (data) => {
    const localityField = document.getElementById('registration_form_locality');
    localityField.innerHTML = "";
    data.forEach((element) => {
        localityField.innerHTML += `<option value="${element.id}">${element.locality}</option>`;
    });
}

const postCode = document.getElementById('registration_form_postCode');
const localityField = document.getElementById('registration_form_locality');
// Delete all options in Locality <select>
localityField.innerHTML = "";
// Display Locality in relation with the postCode typed by the user
fetchAndDisplayAutoCompletion(postCode);