import get_login_data from './get_login_data.js';
(async () => {
    const login = await get_login_data();
    if (login.error) {
        window.location.replace('../../index.php');
    }
})();
