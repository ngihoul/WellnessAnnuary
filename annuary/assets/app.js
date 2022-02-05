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

// *** Main scripts *** //
// ** Menu ** //
// * CONSTANTS : SVG LOGOS * //
const HAMBURGER_LOGO = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M0.892857 21.9286H24.1071C24.2113 21.9286 24.3113 21.97 24.3849 22.0436C24.4586 22.1173 24.5 22.2172 24.5 22.3214V24.1071C24.5 24.2113 24.4586 24.3113 24.3849 24.3849C24.3113 24.4586 24.2113 24.5 24.1071 24.5H0.892857C0.788665 24.5 0.688739 24.4586 0.615064 24.3849C0.541391 24.3113 0.5 24.2113 0.5 24.1071V22.3214C0.5 22.2172 0.541391 22.1173 0.615064 22.0436C0.688739 21.97 0.788665 21.9286 0.892857 21.9286ZM0.892857 14.7857H24.1071C24.2113 14.7857 24.3113 14.8271 24.3849 14.9008C24.4586 14.9745 24.5 15.0744 24.5 15.1786V16.9643C24.5 17.0685 24.4586 17.1684 24.3849 17.2421C24.3113 17.3158 24.2113 17.3571 24.1071 17.3571H0.892857C0.788667 17.3571 0.688741 17.3158 0.615064 17.2421C0.541391 17.1684 0.5 17.0685 0.5 16.9643V15.1786C0.5 15.0744 0.541391 14.9745 0.615065 14.9008C0.688739 14.8271 0.788663 14.7857 0.892857 14.7857ZM0.892857 7.64286H24.1071C24.2113 7.64286 24.3113 7.68425 24.3849 7.75792C24.4586 7.8316 24.5 7.93152 24.5 8.03571V9.82143C24.5 9.92562 24.4586 10.0255 24.3849 10.0992C24.3113 10.1729 24.2113 10.2143 24.1071 10.2143H0.892857C0.788665 10.2143 0.68874 10.1729 0.615065 10.0992C0.54139 10.0255 0.5 9.92562 0.5 9.82143V8.03571C0.5 7.93152 0.54139 7.8316 0.615065 7.75792C0.68874 7.68425 0.788665 7.64286 0.892857 7.64286ZM0.892857 0.5H24.1071C24.2113 0.5 24.3113 0.541391 24.3849 0.615064C24.4586 0.688739 24.5 0.788665 24.5 0.892857V2.67857C24.5 2.78276 24.4586 2.88269 24.3849 2.95636C24.3113 3.03004 24.2113 3.07143 24.1071 3.07143H0.892857C0.788665 3.07143 0.68874 3.03004 0.615065 2.95636C0.54139 2.88269 0.5 2.78276 0.5 2.67857V0.892857C0.5 0.788665 0.54139 0.68874 0.615065 0.615065C0.68874 0.54139 0.788665 0.5 0.892857 0.5Z" stroke="#33272A"/>\n' +
    '</svg>\n';

const BACK_LOGO = '<svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M16.8052 0.938045L16.8072 0.939688L18.9941 2.70476C19.3433 2.98661 19.4903 3.329 19.4903 3.64242C19.4903 3.95551 19.3437 4.29506 18.9961 4.57257L18.9937 4.57453L9.67529 12.1112L9.19413 12.5004L9.67569 12.8891L19.0038 20.418C19.353 20.6998 19.5 21.0422 19.5 21.3556C19.5 21.6687 19.3533 22.0083 19.0058 22.2858L19.0029 22.2881L16.8169 24.0603C16.8168 24.0604 16.8167 24.0605 16.8165 24.0606C16.0892 24.6474 14.8815 24.6451 14.1667 24.062L14.1647 24.0603L1.00469 13.4386L1.00268 13.437C0.648991 13.1545 0.500792 12.8121 0.500003 12.4987C0.499214 12.1852 0.645847 11.8432 0.995018 11.5614L14.155 0.939688C14.8823 0.352641 16.0902 0.354783 16.8052 0.938045Z" stroke="#33272A"/>\n' +
    '</svg>\n';

const CHEVRON_UP = '<svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M14.9242 8.28468L14.9223 8.28657L13.8188 9.39008C13.554 9.65486 13.1299 9.65212 12.8725 9.39198L12.8725 9.39197L12.8706 9.39008L8.16361 4.68305L7.81006 4.3295L7.45651 4.68305L2.74948 9.39008C2.4847 9.65486 2.06057 9.65212 1.8032 9.39197L1.80131 9.39008L0.697794 8.28657C0.433021 8.0218 0.435754 7.59766 0.695903 7.34029L0.697794 7.3384L7.33842 0.697777L7.33844 0.697797L7.3422 0.693955C7.59324 0.437456 8.01585 0.431928 8.2817 0.697777L14.9223 7.3384C15.1871 7.60317 15.1844 8.02731 14.9242 8.28468Z" stroke="#594A4E"/>\n' +
    '</svg>\n';

const CHEVRON_DOWN = '<svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M0.695896 1.80319L0.697792 1.80131L1.80131 0.697792C2.06608 0.433019 2.49021 0.435752 2.74759 0.695896L2.74758 0.695901L2.74947 0.697792L7.45651 5.40482L7.81006 5.75838L8.16361 5.40482L12.8706 0.697792C13.1354 0.433019 13.5595 0.435752 13.8169 0.695901L13.8188 0.697792L14.9223 1.80131C15.1871 2.06608 15.1844 2.49021 14.9242 2.74758L14.9223 2.74947L8.2817 9.3901L8.28168 9.39008L8.27792 9.39392C8.02688 9.65042 7.60427 9.65595 7.33842 9.3901L0.697792 2.74947C0.433019 2.4847 0.435752 2.06057 0.695896 1.80319Z" stroke="#594A4E"/>\n' +
    '</svg>\n';

const LOGIN_LOGO = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M19.0312 7.03125C19.0312 10.6369 16.1057 13.5625 12.5 13.5625C8.89431 13.5625 5.96875 10.6369 5.96875 7.03125C5.96875 3.42556 8.89431 0.5 12.5 0.5C16.1057 0.5 19.0312 3.42556 19.0312 7.03125ZM12.5 16.9062C13.8052 16.9062 15.0468 16.6235 16.1673 16.125H18.75C21.926 16.125 24.5 18.699 24.5 21.875V22.6562C24.5 23.6741 23.6741 24.5 22.6562 24.5H2.34375C1.32595 24.5 0.5 23.6741 0.5 22.6562V21.875C0.5 18.699 3.07399 16.125 6.25 16.125H8.83307C9.95713 16.6232 11.1941 16.9062 12.5 16.9062Z" stroke="#33272A"/>\n' +
    '</svg>';

// * Open/close menu on click on hamburger icon * /
const menu = document.querySelector('.menu');
const openMenu = document.querySelector('.openMenu');
const openLogo = document.querySelector('.openLogo')

openMenu.addEventListener('click', (e) => {
    e.preventDefault();
    if(!menu.classList.contains('active') && !searchMod.classList.contains('active')) {
        menu.classList.add('active');
        openLogo.innerHTML = BACK_LOGO;
    } else if (menu.classList.contains('active')) {
        menu.classList.remove('active');
        openLogo.innerHTML = HAMBURGER_LOGO;
    } else if (searchMod.classList.contains('active')){
        searchMod.classList.remove('active');
        openLogo.innerHTML = HAMBURGER_LOGO;
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
    } else {
        searchMod.classList.add('active');
        openLogo.innerHTML = BACK_LOGO;
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
        chevronCategory.innerHTML = CHEVRON_UP;
        chevronCategory.classList.remove('closed');
    } else {
        chevronCategory.innerHTML = CHEVRON_DOWN;
        chevronCategory.classList.add('closed');
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

// ** Open/close login form ** //
const loginBtn = document.querySelector('.loginBtn');
const loginMod = document.querySelector('.loginMod')

loginBtn.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    if(!loginMod.classList.contains('active') &&
        (menu.classList.contains('active') ||
        searchMod.classList.contains('active'))) {
            menu.classList.remove('active');
            searchMod.classList.remove('active');
            loginMod.classList.add('active');
            loginBtn.innerHTML = BACK_LOGO;
    } else if (!loginMod.classList.contains('active')) {
        loginMod.classList.add('active');
        loginBtn.innerHTML = BACK_LOGO;
    } else if (loginMod.classList.contains('active')) {
        loginMod.classList.remove('active');
        loginBtn.innerHTML = LOGIN_LOGO;
    }
});

// ** Register routing ** //
const registerLink = document.querySelector('.registerLink');
registerLink.addEventListener('click', (e) => {
    displayRegistrationFormChoices(loginMod);
});

const displayRegistrationFormChoices = (field) => {
    let title = document.querySelector('.loginMod h1');
    let loginForm = document.querySelector('.loginForm');
    let registerLink = document.querySelector('.register');
    title.innerText = 'Inscrivez-vous';
    loginForm = loginForm.remove();
    registerLink = registerLink.remove();

    createRegisterBtn('/register/customer', 'Je suis <br><strong>un particulier</strong><br>et je veux profiter des bons plans', field);

    createRegisterBtn('/register/provider', 'Je suis <br><strong>un professionnel</strong><br>et je souhaite partager mes supperbes offres', field);
}

const createRegisterBtn = (link, htmlMessage, parentField) => {
    let btnCustomer = document.createElement('p');
    btnCustomer.className = 'cta';
    btnCustomer.innerHTML = `<a href="${link}">${htmlMessage}</a>`;
    parentField.appendChild(btnCustomer);
}