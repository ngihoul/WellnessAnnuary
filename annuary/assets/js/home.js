// ** Displaying/hiding search button on homepage ** //
const searchBtn = document.querySelector('.searchBtn');
const screenHeight = window.screen.height;
const screenWidth = window.screen.width;
const url = window.location.href;

let BREAKING_POINT_RESPONSIVE = (screenHeight/3)*2;

BREAKING_POINT_RESPONSIVE = (screenWidth >= 1140) ? (screenHeight/5) : (screenHeight/3*2);

// * Display/hide searchBtn after scrolling on Mobile * //
searchBtn.classList.add('hidden');
console.log(BREAKING_POINT_RESPONSIVE);

window.addEventListener('scroll', () => {
    if(window.scrollY > BREAKING_POINT_RESPONSIVE) {
        searchBtn.classList.remove('hidden');
    } else {
        searchBtn.classList.add('hidden');
    }
})