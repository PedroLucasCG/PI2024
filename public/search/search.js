import get_login_data from "../get_login_data.js";
import get_freelancer from "../get_freelancer.js";
import get_paginated_ofertas from "../get_paginated_ofertas.js";
import get_areas from "../get_areas.js";
import get_oferta from "../get_oferta.js";


const cardHTMLTemplate = `
    <div class="bg-white rounded-md p-8 shadow-[0_20px_30px_0_rgba(0,0,0,0.15)] text-center hover:-translate-y-2 transition-all duration-300 ease-in-out ">
        <div class="flex items-center space-x-4">
            <img src=":freelancerImage" class="w-16 h-16 rounded-full" alt="Foto do Freelancer" onerror="this.src='../../assets/imgs/job-sample.jpg'">

            <div class="ml-4">
                <h3 class="text-lg font-semibold">:nome</h3>
                <p class="text-gray-500 text-sm">:bairro, :cidade</p>
            </div>

        </div>
        <div class="flex items-center justify-between mt-4">
            <span class="bg-green-100 text-green-600 text-sm px-4 py-1 rounded-full">:area</span>
        </div>
        <div class="mt-4 flex items-center justify-between">
        <p class="text-gray-500">:titulo</p>
        <div class="text-xl font-bold">:preco</div>

        </div>
        <input type="hidden" id="idOferta" value=":idOferta">
        <button id="offer-popup" class="mt-6 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600" onclick="showPopUp(this)">
        Visualizar
        </button>
    </div>
`;

const popupHTMLTemplate = `
<div id="modal"
      class="fixed inset-0 bg-black/40 backdrop-blur-md"
    ></div>
      <div class="mx-auto mt-5 bg-white rounded-lg shadow-lg z-50 absolute">
      <div class="close" onclick="closePopup()"><img src="../../assets/icons/close.svg" alt="fechar descrição da oferta"></div>
        <div class="content flex flex-wrap p-5">
          <div class="font-medium">
            <!-- picture and person details -->
            <div class="mb-5">
              <h3 class="text-lg text-blue-500 mb-3">Detalhes Pessoais</h3>
              <div class="flex flex-1 gap-11">
                <img
                  src=":freelancerImage"
                  alt="Foto de perfil"
                  class="w-64 h-80 rounded-md mb-5"
                  onerror="this.src='../../assets/icons/profile.svg'"
                />

                <div class="pt-4">
                  <div class="text-sm mb-2 pb-4">
                    <h3 class="text-gray-600 pb-1">Nome:</h3>
                    <p>:nome</p>
                  </div>
                  <div class="text-sm mb-2 pb-4">
                    <h3 class="text-gray-600 pb-1">Data de Nascimento:</h3>
                    <p>:dataNascimento</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- adreess and contact details -->
            <div class="flex flex-1 gap-36">
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
          <div class="px-4">
            <h3 class="text-lg text-blue-500 mb-3">
              Últimos Trabalhos <br />
              Contratados
            </h3>
            <!-- first review -->
            <div class="space-y-4 pb-4">
              <div
                class="review items-center border border-gray-200 rounded-lg p-4 max-w-60"
              >
                <div class="flex flex-1 mb-4">
                  <img
                    src="./../../assets/imgs/search//50.png"
                    alt="Sofia"
                    class="w-12 h-12 rounded-full mr-4"
                  />
                  <div>
                    <p class="text-gray-800 font-semibold pb-1">Sofia</p>
                    <p class="text-xs text-gray-400">2 days ago</p>
                  </div>
                </div>
                <div class="mb-4">
                  <p class="text-xs text-gray-600">
                    Lorem Ipsum is simply dummy text of the printing industry.
                  </p>
                  <div class="flex items-center mt-1">
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-gray-300">★</span>
                    <span class="ml-2 text-sm text-gray-600">4.0</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- second review -->
            <div class="space-y-4 pb-4">
              <div
                class="review items-center border border-gray-200 rounded-lg p-4 max-w-60"
              >
                <div class="flex flex-1 mb-4">
                  <img
                    src="../../assets/imgs/search/50.png"
                    alt="Sofia"
                    class="w-12 h-12 rounded-full mr-4"
                  />
                  <div>
                    <p class="text-gray-800 font-semibold pb-1">Sofia</p>
                    <p class="text-xs text-gray-400">2 days ago</p>
                  </div>
                </div>
                <div class="mb-4">
                  <p class="text-xs text-gray-600">
                    Lorem Ipsum is simply dummy text of the printing industry.
                  </p>
                  <div class="flex items-center mt-1">
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-yellow-400">★</span>
                    <span class="text-gray-300">★</span>
                    <span class="ml-2 text-sm text-gray-600">4.0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ofertas (esqueci como escreve em inglês kkk) offers? -->
          <div class="flex-1">
            <div>
              <h3 class="text-red-500 font-bold mb-3 text-3xl underline">
                Oferta
              </h3>
              <div
                class="offer-item border border-gray-200 rounded-lg overflow-hidden mb-5 px-5 bg-gray-100"
              >
                <h1 class="text-3xl text-gray-400 py-4">
                  :titulo
                </h1>
                <img
                  src=":ofertaImg"
                  alt="Pintura de carros clássicos"
                  onerror="this.src='../../assets/imgs/job-sample.jpg'"
                  class="w-full h-auto"
                />
                <div class="p-4">
                  <p class="text-sm text-gray-600 mt-2">
                    :descricao
                  </p>
                </div>
                <div class="p-4">
                  <p class="text-sm text-gray-600 mt-2">
                    :periodos
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- accept -->
        <div
          class="footer flex justify-between items-center p-4 bg-gray-50 border-t border-gray-200"
        >
          <button id="accept"
            class="bg-green-500 text-white py-3 px-6 rounded-full shadow hover:bg-green-600"
          >
            Contratar
          </button>
        </div>
      </div>
`;

const review = `
<div class="space-y-4 pb-4">
              <div
                class="review items-center border border-gray-200 rounded-lg p-4 max-w-60"
              >
                <div class="flex flex-1 mb-4">
                  <img
                    src=":contratanteImage"
                    alt="Avaliador foto"
                    class="w-12 h-12 rounded-full mr-4"
                    onerror="this.src='../../assets/icons/profile.svg'"
                  />
                  <div>
                    <p class="text-gray-800 font-semibold pb-1">:nome</p>
                  </div>
                </div>
                <div class="mb-4">
                  <p class="text-xs text-gray-600">
                    Lorem Ipsum is simply dummy text of the printing industry.
                  </p>
                  <div class="flex items-center mt-1">
                    :estrelas
                    <span class="ml-2 text-sm text-gray-600">:nota</span>
                  </div>
                </div>
              </div>
            </div>
`;

async function showJobs(ofertas) {
    const ofertasContainer = document.getElementById("ofertasContainer");
    const areas = await get_areas();
    for (const oferta of ofertas.data) {
        const card = cardHTMLTemplate
            .replace(":freelancerImage", `../../uploads/${oferta.Freelancer}/${oferta.foto}`)
            .replace(":nome", oferta.nome)
            .replace(":bairro", oferta.bairro)
            .replace(":cidade", oferta.cidade)
            .replace(":area", areas.data.find(items => items.idArea == oferta.Area).nome)
            .replace(":titulo", oferta.titulo)
            .replace(":preco", oferta.preco)
            .replace(":idOferta", oferta.idOferta);

        ofertasContainer.innerHTML += card;
    }
}

function closePopup() {
    const modal = document.getElementById("screen-popup");
    modal.style.display = "none";
}

window.closePopup = closePopup;

async function showPopUp(oferta) {
    const modal = document.getElementById("screen-popup");
    const idOferta = oferta.parentElement.querySelector("input").attributes[2].value;
    const ofertaDetail = await get_oferta(idOferta);
    const dataNascimento = new Date(ofertaDetail.data.data_nasc.replace("-", "/"));

    let periodos = "";
    for (const periodo of ofertaDetail.data.periodos) {
        periodos += `<span class='periodo'> ${periodo.dia}, ${periodo.hora_inicio} - ${periodo.hora_final} </span>`;
    }
    modal.innerHTML += popupHTMLTemplate
            .replace(":freelancerImage", `../../uploads/${ofertaDetail.data.Freelancer}/${ofertaDetail.data.pessoaFoto}`)
            .replace(":nome", ofertaDetail.data.nomePessoa)
            .replace(":dataNascimento", dataNascimento.toLocaleDateString('pt-br'))
            .replace(":cidade", ofertaDetail.data.cidade)
            .replace(":estado", ofertaDetail.data.estado)
            .replace(":telefone", ofertaDetail.data.telefone)
            .replace(":email", ofertaDetail.data.email)
            .replace(":ofertaImg", `../../uploads/${ofertaDetail.data.Freelancer}/${ofertaDetail.data.ofertaFoto}`)
            .replace(":titulo", ofertaDetail.data.titulo)
            .replace(":descricao", ofertaDetail.data.descricao)
            .replace(":periodos", periodos);
    modal.style.display = "block";
}
window.showPopUp = showPopUp;

const value = await get_login_data();
console.log(value);
(async () => {
    const ofertas = await get_paginated_ofertas();
    ofertasContainer.innerHTML = "";
    await showJobs(ofertas);
})();
if (!value.error) {
    entrar.style.display = "none";
    cadastrar.style.display = "none";
    const userData = await get_login_data();
    const foto = await get_freelancer(userData.idPessoa).then(value => value.data.foto);
    profilePic.src = `../../uploads/${userData.idPessoa}/${foto}`;
    profilePic.addEventListener("click", function () {
        const profileDropdown = this.parentElement.querySelector("#profileDropdown");
        profileDropdown.style.display = profileDropdown.style.display == "block" ? "none" : "block";
    });
    dashboard.addEventListener("click", () => {
        window.location.replace('../profile/profile.html');
    });

    sair.addEventListener("click", () => {
        fetch("../../controllers/session/destroy.php")
            .then(response => response.text())
                .then(data => {
                    console.log(data.msg);
                    window.location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
    });
    const navbarButton = document.getElementById("postarTrabalho");
    navbarButton.addEventListener("click", () => {
        localStorage.setItem("section", "ofertas");
        window.location.replace('../profile/profile.html');
    });
} else {
    const profile = document.querySelector('li.profileContainer');
    profile.style.display = 'none';
}
