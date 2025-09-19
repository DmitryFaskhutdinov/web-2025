function initTogglePassword(tougleBtn, passBox, ImgOff, ImgOn) {
    const eyeButton = document.querySelector(tougleBtn);
    const passInput = document.querySelector( passBox);
    const eyeOff = document.querySelector(ImgOff);
    const eyeOn = document.querySelector(ImgOn);

    if (!eyeButton || !passInput || !eyeOff || !eyeOn) return;

    eyeButton.addEventListener('click', () => {
        const isPassword = passInput.type === "password";
        passInput.type = isPassword ? "text" : "password";
        eyeOff.style.display = isPassword ? "none" : "block";
        eyeOn.style.display = isPassword ? "block" : "none";
    });
}

initTogglePassword(
    ".login__eye-button",
    ".login__pass-box",
    ".login__eye-img--off",
    ".login__eye-img--on"
);

// логин-запрос
const form = document.querySelector('.login__form');
const errorBox = form.querySelector('.login__error-box');
const inputInfo = form.querySelector('.login__input-info');
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

form.email.addEventListener('input', () => errorBox.classList.remove('show'));
form.password.addEventListener('input', () => errorBox.classList.remove('show'));

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const email = form.email.value.trim();
    const password = form.password.value.trim();

    form.email.classList.remove('error');
    form.password.classList.remove('error');
    inputInfo.classList.remove('error');

    errorBox.textContent = '';
    errorBox.classList.remove('show');

    if (!email || !password) {
        errorBox.textContent = "🤓 Поля обязательные";
        errorBox.classList.add('show');

        form.email.classList.add('error');
        form.password.classList.add('error');
        return;

    } else if (!emailPattern.test(email)) {
        errorBox.textContent = "🤓 Неверный формат электропочты";
        errorBox.classList.add('show');

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
        window.location.href = `profile.php?id=${result.user_id}`;
    } else {
        errorBox.textContent = "🤥 " + result.message; 
        errorBox.classList.add('show');

        form.email.classList.add('error');
        form.password.classList.add('error');
        inputInfo.classList.add('error');
    }
});

// to do: проверка пароля на допустимые символы и минимальную длину
// to do: блокировка повторных попыток
// to do: response.ok проверить
// to do: обработать потенциально пустой result.user_id