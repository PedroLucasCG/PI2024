export default async function delete_acordo (idAcordo) {
    try {
        const response = await fetch('../../controllers/dashboard/deleteAcordoById.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idAcordo }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const acordo = await response.json();
        console.log(acordo);
        return acordo;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
