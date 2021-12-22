// Open or close menu on CLick on hamburger icon
const menu = document.querySelector('.menu');
const openBtn = document.querySelector('.openMenu');

openBtn.addEventListener('click', () => {
   menu.classList.toggle('active');
});

// Display magnifier if scroll down

// Display categories list after clicking on "Categories" link.
const categoryLink = document.querySelector('.categoryLink');
const dropDown = document.querySelector('.dropDown');
const chevronCategory = document.querySelector('.chevronCategory');

categoryLink.addEventListener('click', () => {
   dropDown.classList.toggle('active');
   // Modify chevron icons
   if(chevronCategory.classList.contains("fa-chevron-down")) {
      chevronCategory.className = "chevronCategory fas fa-chevron-up";
   } else if (chevronCategory.classList.contains("fa-chevron-up")) {
      chevronCategory.className = "chevronCategory fas fa-chevron-down";
   }
});
