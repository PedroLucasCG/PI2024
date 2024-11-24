const getLoginData = async () => {
    try {
        const response = await fetch('../../controllers/session/verify.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const loginData = await response.json();
        return loginData;
    } catch (error) {
        console.error('Error:', error);
    }
};

(async () => {
    const login = await getLoginData();
    if (login.error) {
        window.location.replace('../../index.php');
    }
    console.log('Login Data:', login);
})();
