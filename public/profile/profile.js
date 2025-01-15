import Inputmask from "/node_modules/inputmask/dist/inputmask.es6.js";
import get_login_data from "../get_login_data.js";
import get_areas from "../get_areas.js";
import get_ofertas from "../get_ofertas.js";

//Informações de perfil - miniatura -
(async () => {
    const userData = await get_login_data();
    profilePic.src = `../../uploads/${userData.idPessoa}/profile.png`;
    profileName.innerText = userData.nome;
})();

// Funções de carregamento das seções
function loadStatusAndReviews() {}
function loadProposalsAndProjects() {}
function loadOffers() {}
function suporte() {}

// Função para deletar horário
function deleteHorario(element) {
    if (element && element.parentElement) {
        element.parentElement.remove();
    }
}
window.deleteHorario = deleteHorario;

// Template HTML para exibição dos horários
const horarioTemplate = `
    <span>
        {0}
        <img onclick="deleteHorario(this)" src="../../assets/icons/delete.svg" alt="Deletar horário">
        <input type="hidden" value="{0}" name="periodos[]">
    </span>
`;

// Template HTML para exibição de ofertas cadastradas
const ofertaTemplate = `
    <div>
        <h2>:titulo</h2>
        <h4>:area</h4>
        <img src="../../uploads/:idPessoa/:ofertaImg" alt="amostra de trabalho" onerror="this.src='../../assets/imgs/job-sample.jpg'">
        <strong>:preco</strong>
        <p>:descricao</p>

        <div>
            <!-- <span> segunda, 13:00 - 15:00</span> -->
            :periodos
        </div>

        <div>
            <span style="display: hidden" id="idOferta" value=":idOferta"></span>
            <img src="../../assets/icons/delete.svg" alt="deletar oferta" id="deletarOferta">
            <button id="editarOferta">editar</button>
        </div>
    </div>
`;

// Configuração das seções
const sections = {
    atividade: {
        html: "./sections/atividade.html",
        panel: "atividade",
        function: loadStatusAndReviews,
    },
    projetos: {
        html: "./sections/projetos.html",
        panel: "projeto",
        function: loadProposalsAndProjects,
    },
    ofertas: {
        html: "./sections/ofertas.html",
        panel: "oferta",
        function: loadOffers,
    },
    suporte: {
        html: "./sections/suporte.html",
        panel: "suporte",
        function: suporte,
    }
};

// Elementos principais
const main = document.getElementsByTagName("main")[0];
const panelSelect = document.querySelectorAll("li[panel]");

// Adiciona eventos de clique aos painéis
for (const panel of panelSelect) {
    panel.addEventListener("click", async function () {
        const selectedPanel = document.querySelector("li[selected]");
        if (selectedPanel) {
            selectedPanel.removeAttribute("selected");
        }

        const panelItem = this.getAttribute("panel");
        const section = sections[panelItem];

        if (section) {
            try {
                // Carrega o conteúdo da seção
                const response = await fetch(section.html);
                const content = await response.text();
                main.innerHTML = content;
                section.function();
                this.setAttribute("selected", true);
            } catch (error) {
                console.error("Erro ao carregar a seção:", error);
            }

            // Configuração específica para a seção de ofertas
            if (section.panel === "oferta") {
                configureOfertaSection();
            }
        } else {
            console.error("Seção não encontrada para o painel:", panelItem);
        }
    });
}

// Função para configurar a seção de ofertas
async function configureOfertaSection() {
    const horarioInput = document.getElementById("horario");
    const adicionarButton = document.getElementById("adicionar");
    const idPessoa = document.getElementById("Freelancer");
    const areaSelect = document.getElementById("area");
    try {
        const loginData = await get_login_data();
        const areas = await get_areas();
        const ofertas = await get_ofertas(loginData.idPessoa);
        const ofertasContainer = document.querySelector(".ofertas .slider");
        for (const oferta of ofertas.data) {
            ofertasContainer += ofertaTemplate
                                    .replace(":titulo", oferta.titulo)
                                    .replace(":area", areas.data.find(area => area.idArea === oferta.idArea))
                                    .replace(":idPessoa", oferta.Freelancer)
                                    .replace(":ofertaImg", oferta.foto)
                                    .replace(":preco", oferta.preco)
                                    .replace(":descricao", oferta.descricao)
                                    .replace(":idOferta", oferta.idOferta);
        }

        for (const area of areas.data) {
            areaSelect.innerHTML += `<option value="${area.idArea}">${area.nome}</option>`;
        }
        console.log(idPessoa);
        if (loginData && loginData.idPessoa) {
            idPessoa.setAttribute("value", loginData.idPessoa);
        } else {
            console.error("Erro: Dados de login inválidos.");
        }
    } catch (error) {
        console.error("Erro ao obter os dados de login:", error);
    }

    if (horarioInput) {
        Inputmask({ mask: "99:99 - 99:99" }).mask(horarioInput);
    }

    if (adicionarButton) {
        adicionarButton.addEventListener("click", (event) => {
            event.preventDefault();
            const dia = document.getElementById("dia").value;
            const horario = horarioInput.value;

            if (dia && horario && /\d{2}:\d{2} - \d{2}:\d{2}/.test(horario)) {
                const horarioHTML = horarioTemplate.replace(/\{0\}/g, `${dia}, ${horario}`);
                document.getElementById("horariosContainer").insertAdjacentHTML("beforeend", horarioHTML);
            } else {
                alert("Por favor, preencha o dia e o horário corretamente.");
            }
        });
    }

    // Botão para remover todos os horários (opcional)
    const removerTodosButton = document.getElementById("removerTodos");
    if (removerTodosButton) {
        removerTodosButton.addEventListener("click", () => {
            document.getElementById("horariosContainer").innerHTML = "";
        });
    }
}
