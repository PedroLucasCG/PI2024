export default async function get_paginated_ofertas ({ search = null, page = null, size = null } = {}) {
    try {
        const response = await fetch('/../../controllers/search/ofertasWithPagination.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ search, page, size }),
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
