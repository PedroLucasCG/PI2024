import Inputmask from "/node_modules/inputmask/dist/inputmask.es6.js";
import get_login_data from "../get_login_data.js";
import get_areas from "../get_areas.js";
import get_ofertas from "../get_ofertas.js";
import delete_oferta from "../delete_oferta.js";
import get_acordos from "../get_acordos.js";
import get_freelancer from "../get_freelancer.js";
import set_estado_acordo from "../set_estado_acordo.js";
import delete_acordo from "../delete_Acordo.js";
import get_endereco from "../get_endereco.js";

//Informações de perfil - miniatura -
(async () => {
    const userData = await get_login_data();
    profilePic.src = `../../uploads/${userData.idPessoa}/profile.png`;
    profileName.innerText = userData.nome;
})();

// Funções de carregamento das seções
function loadStatusAndReviews() {}
function loadProposalsAndProjects() {}
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
        <strong>R$ :preco</strong>
        <p>:descricao</p>

        <div>
            <!-- <span> segunda, 13:00 - 15:00</span> -->
            :periodos
        </div>

        <div>
            <span style="display: hidden" id="idOferta" value=":idOferta"></span>
            <img src="../../assets/icons/delete.svg" alt="deletar oferta" id="deletarOferta">
        </div>
    </div>
`;

// Template HTML para cards de propostas de trabalho
const cardPropostaCliente = `
    <div>
        <img src=":ofertaImg" alt="imagem da oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
        <strong>:titulo</strong>
        <p>:descricao</p>
        <div>
            <div>
                <strong>R$ :preco</strong>
            </div>
            <div>
                <input type="hidden" value=":idAcordo" id="idAcordo">
                <img src="../../assets/icons/refuse.svg" alt="recusar" name="recusar">
            </div>
        </div>
    </div>
`;

const cardPropostaFreelancer = `
    <div>
        <img src=":ofertaImg" alt="imagem da oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
        <strong>:titulo</strong>
        <p>:descricao</p>
        <div>
            <strong>R$ :preco</strong>
            <div>
                <input type="hidden" value=":idAcordo" id="idAcordo">
                <img src="../../assets/icons/accept.svg" alt="aceitar" name="aceitar">
                <img src="../../assets/icons/refuse.svg" alt="recusar" name="recusar">
            </div>
        </div>
    </div>
`;

// Template HTML para cards de trabalhos ativos
const cardTrabalhosAtivosFreelancer = `
    <div>
        <div class="header">
            <img src=":freelancerImage" alt="imagem de perfil" onerror="this.src='../../assets/icons/profile.svg'">
            <div>
                <strong>:nome</strong>
                <p>:bairro</p>
            </div>
        </div>
        <span>:area</span>
        <div class="description">
            <p>:descricao</p>
            <strong>R$ :preco</strong>
        </div>
        <div class="actions">
            <input type="hidden" id="idAcordo" value=":idAcordo">
            <button id="finalizarServico">Finalizar Serviço</button>
            <button id="cancelarServico"><img src="../../assets/icons/delete.svg" alt="deletar projeto"></button>
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
        function: configureProjectsSection,
    },
    ofertas: {
        html: "./sections/ofertas.html",
        panel: "oferta",
        function: configureOfertaSection,
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
    const ofertasContainer = document.querySelector(".ofertas .slider");
    try {
        const loginData = await get_login_data();
        const areas = await get_areas();
        const ofertas = await get_ofertas(loginData.idPessoa);
        for (const oferta of ofertas?.data || []) {
            let periodos = "";
            for (const periodo of oferta.periodos) {
                periodos += `<span idPeriodo="${periodo.idPeriodo}"> ${periodo.dia}, ${periodo.hora_inicio} - ${periodo.hora_final}</span>`;
            }
            ofertasContainer.innerHTML += ofertaTemplate
                                    .replace(":titulo", oferta.titulo)
                                    .replace(":area", areas.data.find(area => area.Area === oferta.idArea).nome)
                                    .replace(":idPessoa", oferta.Freelancer)
                                    .replace(":ofertaImg", oferta.foto)
                                    .replace(":preco", oferta.preco.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2,
                                    }))
                                    .replace(":periodos", periodos)
                                    .replace(":descricao", oferta.descricao)
                                    .replace(":idOferta", oferta.idOferta);
        }

        for (const area of areas.data) {
            areaSelect.innerHTML += `<option value="${area.idArea}">${area.nome}</option>`;
        }
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
    if(ofertasContainer.children.length !== 0) {
        deletarOferta.addEventListener("click", async (event) => {
            const parentElement = event.target.parentElement;
            const idOfertaElement = parentElement.querySelector("#idOferta");

            if (idOfertaElement) {
                await delete_oferta(idOfertaElement.attributes.value.value);
                console.log(idOfertaElement.attributes.value.value);
            } else {
                console.log("Element with ID 'idOferta' not found.");
            }
        });
    }

    if (adicionarButton) {
        adicionarButton.addEventListener("click", (event) => {
            event.preventDefault();
            const dia = document.getElementById("dia").value;
            const horario = horarioInput.value;

            if (dia !== "selecione" && horario && /\d{2}:\d{2} - \d{2}:\d{2}/.test(horario)) {
                const horarioHTML = horarioTemplate.replace(/\{0\}/g, `${dia}, ${horario}`);
                document.getElementById("horariosContainer").insertAdjacentHTML("beforeend", horarioHTML);
            } else {
                alert("Por favor, preencha o dia e o horário corretamente.");
            }
        });
    }

    const removerTodosButton = document.getElementById("removerTodos");
    if (removerTodosButton) {
        removerTodosButton.addEventListener("click", () => {
            document.getElementById("horariosContainer").innerHTML = "";
        });
    }
}

// Função para configurar a seção de projetos
async function configureProjectsSection() {
    const propostasClienteContainer = document.querySelector(".propostas .cliente .slider");
    const propostasFreelancerContainer = document.querySelector(".propostas .freelancer .slider");
    const trabalhosClienteContainer = document.querySelector("section#trabalhosCliente .slider");
    const trabalhosFreelancerContainer = document.querySelector("section#trabalhosFreelancer .slider");
    const { idPessoa } = await get_login_data();
    const areas = await get_areas();
    const propostasCliente = await get_acordos({ idContratante: idPessoa, estado: "proposto" });
    const propostasFreelancer = await get_acordos({ idFreelancer: idPessoa, estado: "proposto" });
    const trabalhosCliente = await get_acordos({ idContratante: idPessoa, estado: "ativo" });
    const trabalhosFreelancer = await get_acordos({ idFreelancer: idPessoa, estado: "ativo" });

    for (const proposta of propostasCliente?.data || []) {
        const card = cardPropostaCliente
            .replace(":ofertaImg", `../../uploads/${idPessoa}/${proposta.foto}`)
            .replace(":descricao", proposta.descricao)
            .replace(":titulo", proposta.titulo)
            .replace(":preco", proposta.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }))
            .replace(":idAcordo", proposta.idAcordo );

        propostasClienteContainer.innerHTML += card;

        const recusarButtons = propostasClienteContainer.querySelectorAll("img[name='recusar']");
        for (const button of recusarButtons) {
            button.addEventListener("click", async function () {
                const idAcordo = this.parentElement
                    .querySelector("input#idAcordo")
                    .attributes[1].value;
                console.log(idAcordo);
                await delete_acordo(proposta.idAcordo);
            });
        }
    }

    for (const proposta of propostasFreelancer?.data || []) {
        const freelancer = await get_freelancer(proposta.Contratante);
        const card = cardPropostaFreelancer
            .replace(":ofertaImg", `../../uploads/${idPessoa}/${proposta.foto}`)
            .replace(":descricao", proposta.descricao)
            .replace(":titulo", proposta.titulo)
            .replace(":preco", proposta.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }))
            .replace(":idAcordo", proposta.idAcordo );

        propostasFreelancerContainer.innerHTML += card;
        const aceitarButtons = propostasFreelancerContainer.querySelectorAll("img[name='aceitar']");
        const recusarButtons = propostasFreelancerContainer.querySelectorAll("img[name='recusar']");
        for (const button of aceitarButtons) {
            button.addEventListener("click", async function () {
                const idAcordo = this.parentElement
                    .querySelector("input#idAcordo")
                    .attributes[1].value;
                await set_estado_acordo(idAcordo, "ativo");
            });
        }

        for (const button of recusarButtons) {
            button.addEventListener("click", async function () {
                const idAcordo = this.parentElement
                    .querySelector("input#idAcordo")
                    .attributes[1].value;
                console.log(idAcordo);
                await delete_acordo(proposta.idAcordo);
            });
        }
    }

    for (const trabalho of trabalhosCliente?.data || []) {
        const freelancer = await get_freelancer(trabalho.Freelancer);
        const endereco = await get_endereco(freelancer.data.Endereco);
        const card = cardTrabalhosAtivosFreelancer
            .replace(":freelancerImage", `../../uploads/${trabalho.Freelancer}/${freelancer.data.foto}`)
            .replace(":nome", freelancer.data.nome)
            .replace(":bairro", endereco.data.bairro)
            .replace(":area", areas.data.find(area => area.idArea === trabalho.Area).nome)
            .replace(":descricao", trabalho.descricao)
            .replace(":idAcordo", trabalho.idAcordo)
            .replace(":preco", trabalho.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }));
        trabalhosClienteContainer.innerHTML += card;
    }
    for (const trabalho of trabalhosFreelancer?.data || []) {
        const freelancer = await get_freelancer(trabalho.Freelancer);
        const endereco = await get_endereco(freelancer.data.Endereco);
        const card = cardTrabalhosAtivosFreelancer
            .replace(":freelancerImage", `../../uploads/${trabalho.Freelancer}/${freelancer.data.foto}`)
            .replace(":nome", freelancer.data.nome)
            .replace(":bairro", endereco.data.bairro)
            .replace(":area", areas.data.find(area => area.idArea === trabalho.Area).nome)
            .replace(":descricao", trabalho.descricao)
            .replace(":idAcordo", trabalho.idAcordo)
            .replace(":preco", trabalho.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }));
        trabalhosFreelancerContainer.innerHTML += card;
    }
    const finalizarButtons = trabalhosClienteContainer.parentElement.querySelectorAll(".actions #finalizarServico");
    const cancelarButtons = trabalhosClienteContainer.parentElement.querySelectorAll(".actions #cancelarServico");
    for (const button of finalizarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[2].value;
            await set_estado_acordo(idAcordo, "finalizado");
        });
    }

    for (const button of cancelarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[2].value;
            await set_estado_acordo(idAcordo, "quebrado");
        });
    }
}
