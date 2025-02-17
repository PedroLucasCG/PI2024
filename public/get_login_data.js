export default async function get_login_data () {
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
        console.log(loginData);
        return loginData;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
