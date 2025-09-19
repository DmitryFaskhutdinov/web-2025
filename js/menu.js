function initLogoutBtn(button) {
    const logoutBtn = document.querySelector(button);
    if (!logoutBtn) return;

    logoutBtn.addEventListener('click', async () => {
        try {
            const response = await fetch('api.php?act=logout', {method: "POST"});
            const result = await response.json();
            if (result.status === 'ok') {
                window.location.href = 'login.html';
            }
        } catch (err) {
            console.error('Ошибка при выходе', err);
        }
    });
}

initLogoutBtn('.menu__icon_type_logout');
