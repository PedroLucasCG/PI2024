export default async function set_avaliacao (idAcordo, grau, comentario) {
    try {
        const response = await fetch('../../controllers/dashboard/setAvaliacao.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idAcordo, grau, comentario }),
        });

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
