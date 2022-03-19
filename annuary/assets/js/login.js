// ** Open/close login form ** //
if(document.querySelector('.loginBtn')) {
    const loginBtn = document.querySelector('.loginBtn');
    const loginMod = document.querySelector('.loginMod .wrapper');

    loginBtn.addEventListener('click', (e) => {
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

// ** Register routing ** //
    const registerLink = document.querySelector('.registerLink');
    registerLink.addEventListener('click', (e) => {
        displayRegistrationFormChoices(loginMod);
    });

    const displayRegistrationFormChoices = (field) => {
        let title = document.querySelector('.login h1');
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
}