export default async function get_freelancer (idPessoa) {
    try {
        const response = await fetch('../../controllers/dashboard/freelancerById.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ idPessoa }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const freelancer = await response.json();
        console.log(freelancer);
        return freelancer;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
