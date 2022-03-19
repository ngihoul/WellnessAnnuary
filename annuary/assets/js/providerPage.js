
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

// Geocoding
let address = document.getElementById('map').dataset.address;
let encodedAddress = encodeURI((address));
fetch(`https://api.geoapify.com/v1/geocode/search?text=${encodedAddress}&format=json&apiKey=b02158d3cd38494aa9344154ad101aea`, { method: 'GET' })
    .then(response => response.json())
    .then(result => {
        let lon = result.results[0].lon;
        let lat = result.results[0].lat;
        let map = L.map('map').setView([lat, lon], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 20,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(map);

        var marker = L.marker([lat, lon]).addTo(map);

    })
    .catch(error => {
        document.getElementById('map').innerText = `Erreur : La carte ne peut pas être affichée.`;
    });