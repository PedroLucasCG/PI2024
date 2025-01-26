export default async function get_acordos ({ idContratante = null, idFreelancer = null, estado = null } = {}) {
    try {
        const response = await fetch('/../../controllers/dashboard/acordosByPessoa.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idContratante, estado, idFreelancer }),
        })

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const acordos = await response.json();
        console.log(acordos);
        return acordos;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
