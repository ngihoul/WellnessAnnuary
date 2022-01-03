/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';


// ** Menu ** //
// * Open/close menu on click on hamburger icon * /
const menu = document.querySelector('.menu');
const openMenu = document.querySelector('.openMenu');
const openLogo = document.querySelector('.openLogo')
// Open menu when clicking on menu icon
openMenu.addEventListener('click', (e) => {
    e.preventDefault();
    if(!searchMod.classList.contains('active')) {
        menu.classList.toggle('active');
        if(menu.classList.contains('active')) {
            openLogo.innerHTML = '<i class="fas fa-times"></i>';
        } else {
            openLogo.innerHTML = '<i class="fas fa-ellipsis-v"></i>';
        }
    }
});

// * Open/close search module on magnifier icon * //
const openSearch = document.querySelector('.openSearch');
const searchMod = document.querySelector('.searchMod');

openSearch.addEventListener('click', (e) => {
   e.preventDefault();
   e.stopPropagation();
   searchMod.classList.toggle('active');
    if(searchMod.classList.contains('active')) {
        openLogo.innerHTML = '<i class="fas fa-times"></i>';
        openMenu.addEventListener('click', () => {
            searchMod.classList.remove('active');
            openLogo.innerHTML = '<i class="fas fa-ellipsis-v"></i>';
        });
    } else {
        openLogo.innerHTML = '<i class="fas fa-ellipsis-v"></i>';
    }
});
// Close search module when clicking outside element only
window.addEventListener('click', () => {
    searchMod.classList.remove('active');
});
// Don't close if click is on the search module
searchMod.addEventListener('click', (e) => {
    e.stopPropagation();
})


// * Display categories list after clicking on "Categories" link * /
const categoryLink = document.querySelector('.categoryLink');
const dropDown = document.querySelector('.dropDown');
const chevronCategory = document.querySelector('.chevronCategory');

const switchChevron = () => {
    if(chevronCategory.classList.contains("fa-chevron-down")) {
        chevronCategory.className = "chevronCategory fas fa-chevron-up";
    } else if (chevronCategory.classList.contains("fa-chevron-up")) {
        chevronCategory.className = "chevronCategory fas fa-chevron-down";
    }
}

categoryLink.addEventListener('click', (e) => {
    dropDown.classList.toggle('active');
    e.stopPropagation();
    // Modify chevron icons
    switchChevron()
});
// Close dropdown when clicking outside element only
window.addEventListener('click', () => {
    if(dropDown.classList.contains('active')) {
        switchChevron();
        dropDown.classList.remove('active');
    }
});
// Don't close if click is on the dropdown
dropDown.addEventListener('click', (e) => {
    e.stopPropagation();
});

// ** Autocompletion in search module ** //
// * Definition of required functions * /
const fetchAndDisplayAutoCompletion = (searchType, field) => {
    let query = searchType == 'what' ? 'q' : 'w';
    field.addEventListener('input', () => {
        fetch(`/search/${searchType}/?${query}=${field.value.trim()}`)
            .then((response) => {
                if(!response) {
                    throw new Error(`erreur HTTP! statut: ${reponse.status}`);
                }
                return response.json();
            }).then((data) => {
            console.log(data);
        });
    });
}

// * Autocompletion of "What" field * /
const searchQ = document.querySelector('#search_q');
fetchAndDisplayAutoCompletion('what', searchQ);

// * Autocompletion of "Where" field * /
const searchW = document.querySelector('#search_w');
fetchAndDisplayAutoCompletion('where', searchW);