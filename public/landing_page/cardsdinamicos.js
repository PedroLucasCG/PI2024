import get_login_data from '../get_login_data.js';
import get_paginated_ofertas from '../get_paginated_ofertas.js';
import get_areas from '../get_areas.js';
import get_oferta from '../get_oferta.js';
import get_recent_avaliacoes from '../get_recent_avaliacoes.js';
import set_oferta from '../set_oferta.js?v=1';

// Teste
(async () => {
    await CategoryCards();
    await OfertaCards();

    const testes2 = await get_paginated_ofertas();
    console.log(testes2.data)
})();

function closePopup() {
    const modal = document.getElementById('screen-popup');
    modal.style.display = 'none';
  }

  window.closePopup = closePopup;

async function showPopUp(oferta) {
  const modal = document.getElementById('screen-popup');
  const idOferta =
    oferta.parentElement.querySelector('input').attributes[1].value;
  const ofertaDetail = await get_oferta(idOferta);
  const dataNascimento = new Date(
    ofertaDetail.data.data_nasc.replace('-', '/'),
  );
  const reviews = await get_recent_avaliacoes(ofertaDetail.data.Freelancer);

  let reviewCard = '';
  for (const review of reviews.data || []) {
    let estrelas = '';
    for (let c = 0; c < parseInt(review.grau || 0); c++) {
      estrelas += '<img src="../../assets/icons/star.svg" alt="estrela">';
    }
    reviewCard = reviewHTMLTemplate
      .replace(
        ':contratanteImage',
        `../../uploads/${review.Freelancer}/${review.foto}`,
      )
      .replace(':nome', review.nome)
      .replace(':comentario', review.comentario)
      .replace(':estrelas', estrelas)
      .replace(':nota', review.grau);
  }

  let periodos = '';
  for (const periodo of ofertaDetail.data.periodos) {
    periodos += `<span class='periodo'> ${periodo.dia}, ${periodo.hora_inicio} - ${periodo.hora_final} </span>`;
  }
  modal.innerHTML = popupHTMLTemplate
    .replace(
      ':freelancerImage',
      `../../uploads/${ofertaDetail.data.Freelancer}/${ofertaDetail.data.pessoaFoto}`,
    )
    .replace(':nome', ofertaDetail.data.nomePessoa)
    .replace(':dataNascimento', dataNascimento.toLocaleDateString('pt-br'))
    .replace(':cidade', ofertaDetail.data.cidade)
    .replace(':estado', ofertaDetail.data.estado)
    .replace(':bairro', ofertaDetail.data.bairro)
    .replace(':telefone', ofertaDetail.data.telefone)
    .replace(':email', ofertaDetail.data.email)
    .replace(
      ':ofertaImg',
      `../../uploads/${ofertaDetail.data.Freelancer}/${ofertaDetail.data.ofertaFoto}`,
    )
    .replace(':titulo', ofertaDetail.data.titulo)
    .replace(
      ':preco',
      ofertaDetail.data.preco.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      }),
    )
    .replace(':descricao', ofertaDetail.data.descricao)
    .replace(':idOferta', ofertaDetail.data.idOferta)
    .replace(':periodos', periodos)
    .replace(':reviews', reviewCard);
  modal.style.display = 'block';
}
window.showPopUp = showPopUp;

async function efetutarProposta(oferta) {
  const idOferta =
    oferta.parentElement.querySelector('input#idOferta').attributes[2].value;
  const valor = oferta.parentElement.querySelector('input#valor').value;

  const ofertaDetail = await get_oferta(idOferta);
  const login = await get_login_data();
  if (login.error) {
    window.location.replace('../login/login.html');
  }
  await set_oferta(
    valor || ofertaDetail.data.preco,
    ofertaDetail.data.descricao,
    'proposto',
    'horista',
    login.idPessoa,
    idOferta,
  );
  localStorage.setItem('section', 'projetos');
  window.location.replace('../profile/profile.html');
}

window.efetutarProposta = efetutarProposta;

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
        <a class="card-link">
            <img src=":ofertaimg"
                class="card-image">
            <p class="badge">:area</p>
            <h2 class="card-tittle">:ofertaTittle</h2>
            <input type="hidden" value=":idOferta">
            <button class="card-button material-symbols-outlined" onclick="showPopUp(this)">
                arrow_forward
            </button>
        </a>
    </li>
`;

//Popoup
const popupHTMLTemplate = `
<div id="modal"
      class="fixed inset-0 bg-black/40 backdrop-blur-md w-full"
    ></div>
      <div class="mx-auto mt-5 bg-white rounded-lg shadow-lg z-50 absolute">
      <div class="close" onclick="closePopup()"><img src="../../assets/icons/close.svg" alt="fechar descrição da oferta"></div>
        <div class="content flex flex-wrap p-5">
          <div class="font-medium">
            <!-- picture and person details -->
            <h3 class="text-lg text-blue-500 mb-3">Detalhes Pessoais</h3>
            <div class="mb-5 flex flex-wrap">

              <div class="flex flex-1 gap-11 to-down">
                <img
                  src=":freelancerImage"
                  alt="Foto de perfil"
                  class="w-64 rounded-md mb-5"
                  onerror="this.src='../../assets/icons/profile.svg'"
                />

                <div class="pt-4">
                  <div class="text-sm mb-2 pb-4">
                    <h3 class="font-bold text-gray-600 pb-1">Nome:</h3>
                    <p>:nome</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- adreess and contact details -->
            <div class="flex flex-1 gap-36 tira-gap">
              <div class="mb-5">
                <h3 class="text-lg text-blue-500 mb-3">Endereço</h3>
                <div class="">

                  <div class="pb-5">
                    <h3 class="text-gray-400 text-sm pb-1 font-semibold">
                      Cidade:
                    </h3>
                    <p>:cidade</p>
                  </div>
                  <div class="pb-5">
                    <h3 class="text-gray-400 text-sm pb-1 font-semibold">
                      Estado
                    </h3>
                    <p class="">:estado</p>
                  </div>
                </div>
              </div>
              <div class="">
                <h3 class="text-lg text-blue-500 mb-3">Detalhes de Contato</h3>
                <div class="pb-5">
                  <h3 class="text-gray-400 text-sm pb-1 font-semibold">
                    Telefone:
                  </h3>
                  <p>:telefone</p>
                </div>
                <div class="pb-5">
                  <h3 class="text-gray-400 text-sm pb-1 font-semibold">
                    E-mail:
                  </h3>
                  <p>:email</p>
                </div>
              </div>
            </div>
          </div>

          <!-- last contract jobs -->
          <div class="px-4 flex flex-wrap repara-padding">
            <h3 class="text-lg text-blue-500 mb-3">
              Últimos Trabalhos <br />
              Contratados
            </h3>
            :reviews
          </div>

          <!-- ofertas (esqueci como escreve em inglês kkk) offers? -->
          <div class="">

              <div
                class="offer-item border border-gray-200 rounded-lg overflow-hidden mb-5 px-5 bg-gray-100"
              ><div>
              <h3 class="text-red-500 font-bold mb-3 text-3xl underline mt-2">
                Oferta
              </h3>
                <h1 class="text-3xl text-black py-4 font-bold">
                  :titulo
                </h1>
                <img
                  src=":ofertaImg"
                  alt="Pintura de carros clássicos"
                  onerror="this.src='../../assets/imgs/job-sample.jpg'"
                  class="h-auto"
                />
                <div class="">
                  <p class="text-sm text-gray-600 mt-2">
                    :descricao
                  </p>
                </div>
                 <div class="py-4">
                  <p class="text-sm text-gray-600 mt-2">
                    :periodos
                  </p>
                </div>
                <div class="py-4 font-semibold">
                  <p class="text-xl text-gray-600">
                    R$ :preco
                  </p>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- accept -->
        <div
          class="footer flex flex-wrap gap-2 justify-between items-center p-4 bg-gray-50 border-t border-gray-200"
        >
        <div>
            <label for="valor">Digite o valor que deseja pagar: </label>
            <input type="number" step=".01" id="valor">
            <input type="hidden" id="idOferta" value=":idOferta">
          </div>
          <button id="accept"
            class="bg-green-500 text-white py-3 px-6 rounded-full shadow hover:bg-green-600"
            onclick="efetutarProposta(this)"
          >
            Contratar
          </button>

        </div>
      </div>
`;

function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

async function OfertaCards() {
    const ofertas = await get_paginated_ofertas({ size: 5 });
    const areas = await get_areas();
    const ofertasContainer = document.getElementById("ofertasContainer");
    ofertasContainer.innerHTML = "";
    for (const oferta of ofertas.data) {
        const card = ofertasCardTemplate
            .replace(":ofertaimg", `../../uploads/${oferta.idPessoa}/${oferta.foto}`)
            .replace(":area", areas.data.find(item => item.idArea === oferta.Area).nome)
            .replace(":idOferta", oferta.idOferta)
            .replace(":ofertaTittle", oferta.titulo);
            ofertasContainer.innerHTML += card;
    }
}

async function CategoryCards() {
    const categoriasContainer = document.querySelector(".grid");
    const areas = await get_areas();

    for (const area of areas.data) {
        const areaNome = capitalize(area['nome']);

        const card = categoriaTemplate
            .replace(":areaImg", `../../assets/imgs/landing_page/areas/categoria-${area['idArea']}.jpg`)
            .replace(":areaNome", areaNome);

        categoriasContainer.innerHTML += card;
    }
}
