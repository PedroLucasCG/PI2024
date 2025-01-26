export default async function get_telefone (idPessoa) {
    try {
        const response = await fetch('/../../controllers/dashboard/telefoneByPessoa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const pessoa = await response.json();
        console.log(pessoa);
        return pessoa;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
