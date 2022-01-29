const postCode = document.getElementById('provider_user_postCode') || document.getElementById('customer_user_postCode');
const localityField = document.getElementById('provider_user_locality') || document.getElementById('customer_user_locality');

const fetchAndDisplayAutoCompletion = (field) => {
    field.addEventListener('input', () => {
        if(field.value.length > 0) {
            fetch(`/search/where/?w=${field.value.trim()}`)
                .then((response) => {
                    if(!response) {
                        throw new Error(`erreur HTTP! statut: ${reponse.status}`);
                    }
                    return response.json();
                }).then((data) => {
                displayLocality(localityField, data);
            });
        } else {
            localityField.innerHTML = "";
        }
    });
}

const displayLocality = (localityField, data) => {
    localityField.innerHTML = "";
    data.forEach((element) => {
        console.log(element);
        localityField.innerHTML += `<option value="${element.id}">${element.locality}</option>`;
    });
}


// Delete all options in Locality <select>
localityField.innerHTML = "";
// Display Locality in relation with the postCode typed by the user
fetchAndDisplayAutoCompletion(postCode);