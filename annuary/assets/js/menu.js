// Open or close menu on click on hamburger icon
const menu = document.querySelector('.menu');
const openMenu = document.querySelector('.openMenu');
const openLogo = document.querySelector('.openLogo')

openMenu.addEventListener('click', (e) => {
   e.preventDefault();
   menu.classList.toggle('active');
   if(menu.classList.contains('active')) {
      openLogo.innerHTML = '<i class="fas fa-times"></i>';
   } else {
      openLogo.innerHTML = '<i class="fas fa-ellipsis-v"></i>';
   }

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
