export default async function get_endereco (idPessoa) {
    try {
        const response = await fetch('/../../controllers/dashboard/enderecoByPessoa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const endereco = await response.json();
        console.log(endereco);
        return endereco;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
