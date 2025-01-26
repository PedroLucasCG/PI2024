export default async function get_areas () {
    try {
        const response = await fetch('../../controllers/common/listAreas.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok 😢');
        }

        const areas = await response.json();
        console.log(areas);
        return areas;
    } catch (error) {
        console.error('Algo deu errado ', error);
    }
};
