export default async function get_ofertas (idPessoa) {
    try {
        await fetch('/../../controllers/dashboard/ofertasFromFreelancer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        })
            .then(response => response.json())
            .then(result => {
                console.log(result);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } catch (err) {
        console.log(err);
    }

}
