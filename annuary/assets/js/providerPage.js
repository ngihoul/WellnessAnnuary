const openLoginForm = document.querySelectorAll('.openLoginForm');
const loginMod = document.querySelector('.loginMod');
const menu = document.querySelector('.menu');
const searchMod = document.querySelector('.searchMod');
const loginBtn = document.querySelector('.loginBtn');

const LOGIN_LOGO = '<svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M19.0312 7.03125C19.0312 10.6369 16.1057 13.5625 12.5 13.5625C8.89431 13.5625 5.96875 10.6369 5.96875 7.03125C5.96875 3.42556 8.89431 0.5 12.5 0.5C16.1057 0.5 19.0312 3.42556 19.0312 7.03125ZM12.5 16.9062C13.8052 16.9062 15.0468 16.6235 16.1673 16.125H18.75C21.926 16.125 24.5 18.699 24.5 21.875V22.6562C24.5 23.6741 23.6741 24.5 22.6562 24.5H2.34375C1.32595 24.5 0.5 23.6741 0.5 22.6562V21.875C0.5 18.699 3.07399 16.125 6.25 16.125H8.83307C9.95713 16.6232 11.1941 16.9062 12.5 16.9062Z" stroke="#33272A"/>\n' +
    '</svg>';

const BACK_LOGO = '<svg width="20" height="25" viewBox="0 0 20 25" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
    '<path d="M16.8052 0.938045L16.8072 0.939688L18.9941 2.70476C19.3433 2.98661 19.4903 3.329 19.4903 3.64242C19.4903 3.95551 19.3437 4.29506 18.9961 4.57257L18.9937 4.57453L9.67529 12.1112L9.19413 12.5004L9.67569 12.8891L19.0038 20.418C19.353 20.6998 19.5 21.0422 19.5 21.3556C19.5 21.6687 19.3533 22.0083 19.0058 22.2858L19.0029 22.2881L16.8169 24.0603C16.8168 24.0604 16.8167 24.0605 16.8165 24.0606C16.0892 24.6474 14.8815 24.6451 14.1667 24.062L14.1647 24.0603L1.00469 13.4386L1.00268 13.437C0.648991 13.1545 0.500792 12.8121 0.500003 12.4987C0.499214 12.1852 0.645847 11.8432 0.995018 11.5614L14.155 0.939688C14.8823 0.352641 16.0902 0.354783 16.8052 0.938045Z" stroke="#33272A"/>\n' +
    '</svg>\n';

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
            loginBtn.innerHTML = BACK_LOGO;
        } else if (!loginMod.classList.contains('active')) {
            loginMod.classList.add('active');
            loginBtn.innerHTML = BACK_LOGO;
        } else if (loginMod.classList.contains('active')) {
            loginMod.classList.remove('active');
            loginBtn.innerHTML = LOGIN_LOGO;
        }
    });
});

