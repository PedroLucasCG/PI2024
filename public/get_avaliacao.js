export default async function get_avaliacao (idAcordo) {
    try {
        const response = await fetch('/../../controllers/dashboard/avaliacaoByAcordo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idAcordo }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const avaliacao = await response.json();
        console.log(avaliacao);
        return avaliacao;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
