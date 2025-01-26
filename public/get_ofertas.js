export default async function get_ofertas (idPessoa) {
    try {
        const response = await fetch('/../../controllers/dashboard/ofertasFromFreelancer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const ofertas = await response.json();
        console.log(ofertas);
        return ofertas;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
