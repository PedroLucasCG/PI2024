export default async function get_areas () {
    try {
        const response = await fetch('../../controllers/common/getError.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const errors = await response.json();
        errors.forEach(error => {
            console.log(error);
        });
        return errors;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
