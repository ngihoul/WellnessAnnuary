
// Annuary Bien-Être - JS - Request password form
// Author : Nicolas Gihoul
// Date : March 2022

import { LOGO } from './functions';


// ** Variables ** //
const openLoginForm = document.querySelectorAll('.openLoginForm');
const loginMod = document.querySelector('.loginMod');
const menu = document.querySelector('.menu');
const searchMod = document.querySelector('.searchMod');
const loginBtn = document.querySelector('.loginBtn');

// ** Scripts ** //
openLoginForm.forEach(element => {
    element.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        if(!loginMod.classList.contains('active') &&
            (menu.classList.contains('active') ||
                searchMod.classList.contains('active'))) {
            menu.classList.remove('active');
            searchMod.classList.remove('active');
            loginMod.classList.add('active');
            loginBtn.innerHTML = LOGO.BACK;
        } else if (!loginMod.classList.contains('active')) {
            loginMod.classList.add('active');
            loginBtn.innerHTML = LOGO.BACK;
        } else if (loginMod.classList.contains('active')) {
            loginMod.classList.remove('active');
            loginBtn.innerHTML = LOGO.LOGIN;
        }
    });
});

