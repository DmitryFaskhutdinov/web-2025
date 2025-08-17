// показать/скрыть пароль
const passInput = document.querySelector(".login__pass-box");
const eyeButton = document.querySelector(".login__eye-button");
const eyeOff = document.querySelector(".login__eye-img--off");
const eyeOn = document.querySelector(".login__eye-img--on");

if (eyeButton) {
    eyeButton.addEventListener('click', () => {
        if (passInput.type === "password") {
            passInput.type = "text";
            eyeOff.style.display = "none";
            eyeOn.style.display = "block";
        } else {
            passInput.type = "password";
            eyeOff.style.display = "block";
            eyeOn.style.display = "none";
        }
    })
}
// логин-запрос
const form = document.querySelector('.login__form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const email = form.email.value;
    const password = form.password.value;
    const errorBox = form.querySelector('.login__error-box');
    const inputInfo = form.querySelector('.login__input-info');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    form.email.classList.remove('error');
    form.password.classList.remove('error');
    errorBox.style.display = 'none';
    errorBox.textContent = '';
    inputInfo.classList.remove('error');


    if (!email || !password) {
        errorBox.textContent = "🤓 Поля обязательные";
        errorBox.style.display = 'block';
        form.email.classList.add('error');
        form.password.classList.add('error');
        return;
    } else if (!emailPattern.test(email)) {
        errorBox.textContent = "🤓 Неверный формат электропочты";
        errorBox.style.display = 'block';
        form.email.classList.add('error');
        inputInfo.classList.add('error');
        return;
    }

    const response = await fetch('api.php?act=login', {
        method: 'POST',
        body: new URLSearchParams({email, password})
    });

    const result = await response.json();
    if (result.status === 'ok') {
        window.location.href = 'home.php';
    } else {
        errorBox.textContent = "🤥 " + result.message; 
        errorBox.style.display = 'block';

        form.email.classList.add('error');
        form.password.classList.add('error');
        inputInfo.classList.add('error');
    }
});

// to do: проверка пароля на допустимые символы и минимальную длину