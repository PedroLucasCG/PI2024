export default async function set_estado_acordo (idAcordo, estado) {
    try {
        const response = await fetch('../../controllers/dashboard/setEstadoAcordo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idAcordo, estado }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const oferta = await response.json();
        console.log(oferta);
        return oferta;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
