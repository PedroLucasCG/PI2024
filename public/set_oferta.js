export default async function set_oferta (valor, descricao, estado, modalidade, Contratante, Oferta) {
    try {
        const response = await fetch('../../controllers/search/setAcordo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ valor, descricao, estado, modalidade, Contratante, Oferta }),
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
