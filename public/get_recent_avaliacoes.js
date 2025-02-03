export default async function get_recent_avaliacoes (idPessoa) {
    try {
        const response = await fetch('/../../controllers/search/avaliacoesByFreelancer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const avaliacoes = await response.json();
        console.log(avaliacoes);
        return avaliacoes;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
