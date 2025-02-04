import get_areas from "../get_areas.js";
import get_ofertas from "../get_ofertas.js";


// Teste
(async () => {
    const testes1 = await get_areas();
    console.log(testes1.data);

    const testes2 = await get_ofertas();
    console.log(testes2.data)
})();


// Template Categorias
const categoriaTemplate = `
<div class="card">
    <a href="../search/search.html">
    <img src=":areaImg" alt="">
    <p>:areaNome</p>
    </a>
</div>
`;

// Template CardOfertas
const ofertasCardTemplate = `
    <li class="card-item swiper-slide">
        <a href="#" class="card-link">
            <img src="ofertaimg"
                class="card-image">
            <p class="badge">:area</p>
            <h2 class="card-tittle">:ofertaTittle</h2>
            <button class="card-button material-symbols-outlined">
                arrow_forward
            </button>
        </a>
    </li>
`;

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

async function CategoryCards() {
    const categoriasContainer = document.querySelector(".grid");
    const areas = await get_areas();

    for (const area of areas.data) {
        const areaNome = capitalize(area['nome']);

        const card = categoriaTemplate
            .replace(":areaImg", `../../assets/imgs/landing_page/areas/${area['nome']}.jpg`)
            .replace(":areaNome", areaNome);

        categoriasContainer.innerHTML += card; // Adiciona o card ao container
    }
}
