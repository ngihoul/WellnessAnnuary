// Close alert with close button
let alertCloseBtn = document.querySelectorAll('.alert-closeBtn');
alertCloseBtn.forEach((element) => {
    element.addEventListener('click', (e) => {
        e.target.parentElement.style.display = 'none';
    })
})


// FadeOut & undisplay alert boxes
const closeAlert = () => {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach((element) => {
        element.classList.add('closed');
        setTimeout(hideAlert, 3000);
    });
}

const hideAlert = () => {
    let alerts = document.querySelectorAll('.alert');
    alerts.forEach((element) => {
        element.style.display = 'none';
    });
}

setTimeout(closeAlert, 5000);


