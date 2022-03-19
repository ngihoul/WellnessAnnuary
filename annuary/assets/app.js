/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single scss file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import { LOGO } from './js/functions';

// *** Main scripts *** //

// * Open/close menu on click on hamburger icon * /
const menu = document.querySelector('.menu');
const openMenu = document.querySelector('.openMenu');
const openLogo = document.querySelector('.openLogo')

openMenu.addEventListener('click', (e) => {
    e.preventDefault();
    if(!menu.classList.contains('active') && !searchMod.classList.contains('active')) {
        menu.classList.add('active');
        openLogo.innerHTML = LOGO.BACK;
    } else if (menu.classList.contains('active')) {
        menu.classList.remove('active');
        openLogo.innerHTML = LOGO.HAMBURGER;
    } else if (searchMod.classList.contains('active')){
        searchMod.classList.remove('active');
        openLogo.innerHTML = LOGO.HAMBURGER;
    }
});


// * Open/close search module on magnifier icon * //
const openSearch = document.querySelector('.openSearch');
const searchMod = document.querySelector('.searchMod');

openSearch.addEventListener('click', (e) => {
   e.preventDefault();
   e.stopPropagation();
    if(menu.classList.contains('active')) {
        menu.classList.remove('active');
        searchMod.classList.add('active');
    } else if (searchMod.classList.contains('active')) {
        searchMod.classList.remove('active');
        openLogo.innerHTML = LOGO.HAMBURGER;
    } else if(dropDown.classList.contains('active')) {
        dropDown.classList.remove('active');
        searchMod.classList.add('active');
        switchChevron();
    } else {
        searchMod.classList.add('active');
        openLogo.innerHTML = LOGO.BACK;
    }
});


// * Close search module when clicking outside element only * /
window.addEventListener('click', () => searchMod.classList.remove('active'));
// Don't close if click is on the search module
searchMod.addEventListener('click', (e) => e.stopPropagation());


// * Display categories list after clicking on "Categories" link * /
const categoryLink = document.querySelector('.categoryLink');
const dropDown = document.querySelector('.dropDown');
const chevronCategory = document.querySelector('.chevronCategory');

const switchChevron = () => {
    if(chevronCategory.classList.contains("closed")) {
        chevronCategory.innerHTML = LOGO.CHEVRON_UP;
        chevronCategory.classList.remove('closed');
    } else {
        chevronCategory.innerHTML = LOGO.CHEVRON_DOWN;
        chevronCategory.classList.add('closed');
    }
}

categoryLink.addEventListener('click', (e) => {
    if(searchMod.classList.contains('active')) {
        searchMod.classList.remove('active');
    }
    dropDown.classList.toggle('active');
    e.stopPropagation();
    e.preventDefault();
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

