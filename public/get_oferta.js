export default async function get_oferta (idOferta) {
    try {
        const response = await fetch('/../../controllers/search/ofertaFromFreelancer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idOferta }),
        })

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
