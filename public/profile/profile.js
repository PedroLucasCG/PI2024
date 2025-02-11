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
import get_acordo from "../get_acordo.js";
import get_telefone from "../get_telefone.js";
import get_avaliacao from "../get_avaliacao.js";

function showHideSideBar () {
    console.log("pedro");
    const aside = document.getElementsByTagName("aside")[0];
    const button = document.querySelector("main div.responsive");
    button.innerHTML = button.innerText === "<" ? ">" : "<";
    aside.classList.toggle("visible");
};

window.showHideSideBar = showHideSideBar;

//Informações de perfil - miniatura -
(async () => {
    const userData = await get_login_data();
    const foto = await get_freelancer(userData.idPessoa).then(value => value.data.foto);
    profilePic.src = `../../uploads/${userData.idPessoa}/${foto}`;
    profileName.innerText = userData.nome;
})();

function getYearDifference(date1, date2) {
    const years1 = parseInt(date1.getFullYear());
    const years2 = parseInt(date2.getFullYear());
    const months1 = parseInt(date1.getMonth());
    const months2 = parseInt(date2.getMonth());
    const days1 = parseInt(date1.getDate());
    const days2 = parseInt(date2.getDate());

    let differenceInYears = years2 - years1;

    if (months1 > months2 || (months1 === months2 && days1 > days2)) {
        differenceInYears--;
    }
    return Math.abs(differenceInYears);
}

// Funções de carregamento das seções
function loadStatusAndReviews() {}
function suporte() {}

// close modal
function closeModal() {
    document.getElementById("modal").style.display = "none";
}

window.closeModal = closeModal;

async function select(section) {
    try {
        const response = await fetch(section.html);
        const content = await response.text();
        main.innerHTML = mainContent + content;
        section.function();
        const selectedPanel = document.querySelector("[selected]");
        if (selectedPanel) {
            selectedPanel.removeAttribute("selected");
        }
    } catch (error) {
        console.error("Erro ao carregar a seção:", error);
    }
}

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
            <img src="../../assets/icons/delete.svg" alt="deletar oferta" id="deletarOferta" name="deletarOferta">
        </div>
    </div>
`;

// Template HTML para cards de propostas de trabalho
const cardPropostaCliente = `
    <div>
        <img onclick="showDetails(this.parentElement)" src=":ofertaImg" alt="imagem da oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
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
        <img onclick="showDetails(this.parentElement)" src=":ofertaImg" alt="imagem da oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
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
        <div class="header" onclick="showDetails(this.parentElement)">
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
            <input type="hidden" value=":idAcordo" id="idAcordo">
            <button id="finalizarServico">Finalizar Serviço</button>
            <button id="cancelarServico"><img src="../../assets/icons/delete.svg" alt="deletar projeto"></button>
        </div>
    </div>
`;

// Template HTML para cards do Histórico
const cardHistorico = `
    <div onclick="showDetails(this)">
        <h2>:titulo</h2>
        <img src=":ofertaImg" alt="imagem oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
        <div class="header">
            <img src=":profileImg" alt="imagem de perfil" onerror="this.src='../../assets/icons/profile.svg'">
            <strong>:nome</strong>
        </div>
        <span>:local</span>
        <p>:descricao</p>
        <input type="hidden" value=":idAcordo" id="idAcordo">
        <span>:funcao</span>
        <div>
            <strong>R$ :valor</strong>
            <span>:estado</span>
        </div>
    </div>
`;

// Template HTML para modal
const sectionsModal = {
    parceiro: `
    <h1>Parceiro</h1>
    <img src=":profileImg" alt="parceiro profile foto" onerror="this.src='../../assets/icons/profile.svg'">
    <strong>:nome</strong>
    <p>:cidade/:bairro</h3>
    <p>Idade: :idade anos</p>
    <p>Email: :email</p>
    <p>Telefone: :telefone</p>
    <div>
        <strong><em id="servicosCancelados">:servicosCancelados</em> serviços cancelados</strong>
        <strong><em id="servicosConcluidos">:servicosConcluidos</em> serviços concluídos</strong>
        <strong>Avaliação Média <em id="servicosMedia">:mediaServicos</em></strong>
    </div>
    `,
    oferta: `
    <h1>Acordo</h1>
    <img src=":ofertaImg" alt="imagem da oferta" onerror="this.src='../../assets/imgs/job-sample.jpg'">
    <strong>:titulo</strong>
    <p>:area</p>
    <p>:descricao</p>
    <strong>R$ :valor</strong>
    <div>
        <!-- <span>segunda, 13:00 - 15:00</span> -->
        :periodos
    </div>
    `,
    avaliacao: `
    <h1>Avaliação</h1>
    <div>
        <h3>Nota</h3>
        <!-- <img src="../../assets/icons/star.svg" alt="estrela"> -->
        :estrelas
    </div>
    <p>
        :avaliacao
    </p>
    `,
};

// Tempplate HTML editar perfil

const editarPerfilTemplate = `
        <div><img src=":profileImg" alt="imagem de perfil" onerror="this.src='../../assets/icons/profile.svg'"></div>
        <form action="../../index.php" method="post" enctype="multipart/form-data">
            <h1>Atualizar Perfil:</h1>
            <div class="input-wrapper"><input type="text" name="nome" placeholder="Ex.: Joaquim Mercedes" required disabled value=":nome"></div>
            <div class="input-wrapper"><input type="text" name="data_nasc" id="data_nasc" placeholder="00/00/0000" required value=":data_nasc"></div>
            <div class="input-wrapper"><input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" required disabled value=":cpf"></div>
            <div class="input-wrapper"><input type="text" name="usuario" placeholder="Ex.: Joaquim Pinturas" required value=":usuario"></div>
            <div class="input-wrapper"><input type="email" name="email" placeholder="email@umcorre.com" required value=":email"></div>
            <div class="input-wrapper"><input type="text" name="telefone" id="telefone" placeholder="(00)0 0000-0000" required value=":telefone"></div>

            <div class="endereco">
                <div class="selects">
                    <div class="input-wrapper"><select name="estado" id="estado" required>
                        <option selected disabled>Ex.: Bahia</option>
                    </select></div>

                    <div class="input-wrapper"><select name="cidade" id="cidade" required>
                        <option selected disabled>Ex.: Eunápolis</option>
                    </select></div>

                    <div class="input-wrapper"><input type="text" name="bairro" id="bairro" placeholder="Ex.: Juca Rosa" required value=":bairro"></div>
                </div>
            </div>

            <div class="input-wrapper" type="password"><input type="password" name="senha" id="senha" required></div>
            <div class="input-wrapper" type="password">
                <input type="password" id="check_senha" required>
                <span>Os campos não conferem!</span>
            </div>
            <div class="input-wrapper">
                <input type="file" name="profileImg" id="profileImg">
            </div>
            <input type="hidden" name="idPessoa" value=":idPessoa">
            <input type="submit" value="Salvar Alterações" id="submit_form">
            <input type="hidden" name="form" value="atualizarPerfil">
        </form>
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
        panel: "projetos",
        function: configureProjectsSection,
    },
    ofertas: {
        html: "./sections/ofertas.html",
        panel: "ofertas",
        function: configureOfertaSection,
    },
    historico:{
        html: "./sections/historico.html",
        panel: "historico",
        function: configureHistoricoSection,
    },
    suporte: {
        html: "./sections/suporte.html",
        panel: "suporte",
        function: suporte,
    },
    perfil: {
        html: "./sections/perfil.html",
        panel: "perfil",
        function: configureUpdatePerfil,
    }
};

// Elementos principais
const main = document.getElementsByTagName("main")[0];
const panelSelect = document.querySelectorAll("[panel]");
const mainContent = main.innerHTML;
// Adiciona eventos de clique aos painéis
for (const panel of panelSelect) {
    panel.addEventListener("click", async function () {

        const panelItem = this.getAttribute("panel");
        const section = sections[panelItem];

        if (section) {
            try {
                // Carrega o conteúdo da seção
                await select(section);
                localStorage.setItem("section", section.panel);
                this.setAttribute("selected", true);
            } catch (error) {
                console.error("Erro ao carregar a seção:", error);
            }
        } else {
            console.error("Seção não encontrada para o painel:", panelItem);
        }
    });
}

if (!localStorage.getItem("section")) {
    localStorage.setItem("section", "projetos");
}

(async () => {
    await select(sections[localStorage.getItem("section")]);
    const panelSelect = document.querySelector(`[panel='${localStorage.getItem("section")}']`);
    panelSelect.setAttribute("selected", true);
})();

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
        const buttons = document.querySelectorAll('img[name="deletarOferta"]');
        for (const deletarOferta of buttons) {
            deletarOferta.addEventListener("click", async (event) => {
                const parentElement = event.target.parentElement;
                const idOfertaElement = parentElement.querySelector("#idOferta");

                if (idOfertaElement) {
                    await delete_oferta(idOfertaElement.attributes.value.value);
                    console.log(idOfertaElement.attributes.value.value);
                } else {
                    console.log("Element with ID 'idOferta' not found.");
                }
                window.location.reload();
            });
        }
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
            .replace(":ofertaImg", `../../uploads/${proposta.Freelancer}/${proposta.foto}`)
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
                window.location.reload();
            });
        }
    }

    for (const proposta of propostasFreelancer?.data || []) {
        const card = cardPropostaFreelancer
            .replace(":ofertaImg", `../../uploads/${proposta.Contratante}/${proposta.foto}`)
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
                window.location.reload();
            });
        }

        for (const button of recusarButtons) {
            button.addEventListener("click", async function () {
                const idAcordo = this.parentElement
                    .querySelector("input#idAcordo")
                    .attributes[1].value;
                console.log(idAcordo);
                await delete_acordo(proposta.idAcordo);
                window.location.reload();
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
    let finalizarButtons = trabalhosClienteContainer.parentElement.querySelectorAll(".actions #finalizarServico");
    let cancelarButtons = trabalhosClienteContainer.parentElement.querySelectorAll(".actions #cancelarServico");
    for (const button of finalizarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[1].value;
            await set_estado_acordo(idAcordo, "finalizado");
            window.location.reload();
        });
    }

    for (const button of cancelarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[1].value;
            await set_estado_acordo(idAcordo, "quebrado");
            window.location.reload();
        });
    }
    for (const trabalho of trabalhosFreelancer?.data || []) {
        const contratante = await get_freelancer(trabalho.Contratante);
        const endereco = await get_endereco(freelancer.data.Endereco);
        const card = cardTrabalhosAtivosFreelancer
            .replace(":freelancerImage", `../../uploads/${trabalho.Contratante}/${contratante.data.foto}`)
            .replace(":nome", contratante.data.nome)
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
    finalizarButtons = trabalhosFreelancerContainer.parentElement.querySelectorAll(".actions #finalizarServico");
    cancelarButtons = trabalhosFreelancerContainer.parentElement.querySelectorAll(".actions #cancelarServico");
    for (const button of finalizarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[1].value;
            await set_estado_acordo(idAcordo, "finalizado");
            window.location.reload();
        });
    }

    for (const button of cancelarButtons) {
        button.addEventListener("click", async function () {
            const idAcordo = this.parentElement.querySelector("input").attributes[1].value;
            await set_estado_acordo(idAcordo, "quebrado");
            window.location.reload();
        });
    }
}

// Função para configurar a seção de Histórico
async function configureHistoricoSection() {
    const { idPessoa } = await get_login_data();
    const acordosFinalizadosFreelancer = await get_acordos({ idFreelancer: idPessoa, estado: "finalizado" });
    const acordosFinalizadosCliente = await get_acordos({ idContratante: idPessoa, estado: "finalizado" });
    const historicoContainer = document.getElementsByClassName("historico")[0];

    for (const acordo of acordosFinalizadosFreelancer?.data || []) {
        const contratante = await get_freelancer(acordo.Contratante);
        const endereco = await get_endereco(contratante.data.Endereco);
        const card = cardHistorico
            .replace(":titulo", acordo.titulo)
            .replace(":ofertaImg", `../../uploads/${acordo.Freelancer}/${acordo.foto}`)
            .replace(":profileImg", `../../uploads/${acordo.Contratante}/${contratante.data.foto}`)
            .replace(":nome", contratante.data.nome)
            .replace(":local", `${endereco.data.cidade}/${endereco.data.bairro}`)
            .replace(":descricao", acordo.descricao)
            .replace(":idAcordo", acordo.idAcordo)
            .replace(":valor", acordo.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }))
            .replace(":funcao", "Freelancer")
            .replace(":estado", acordo.estado);
        historicoContainer.innerHTML += card;
    }

    for (const acordo of acordosFinalizadosCliente?.data || []) {
        const freelancer = await get_freelancer(acordo.Freelancer);
        const endereco = await get_endereco(freelancer.data.Endereco);
        const card = cardHistorico
            .replace(":titulo", acordo.titulo)
            .replace(":ofertaImg", `../../uploads/${acordo.Freelancer}/${acordo.foto}`)
            .replace(":profileImg", `../../uploads/${acordo.Freelancer}/${freelancer.data.foto}`)
            .replace(":nome", freelancer.data.nome)
            .replace(":local", `${endereco.data.cidade}/${endereco.data.bairro}`)
            .replace(":idAcordo", acordo.idAcordo)
            .replace(":descricao", acordo.descricao)
            .replace(":valor", acordo.valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }))
            .replace(":funcao", "Contratante")
            .replace(":estado", acordo.estado);
        historicoContainer.innerHTML += card;
    }

}

// Função para configurar a seção de Atualizar perfil
async function configureUpdatePerfil() {
    const containerPerfil = document.querySelector("section.perfil");
    const { idPessoa } = await get_login_data();
    const userData = await get_freelancer(idPessoa).then(value => value.data);
    const userTelefone = await get_telefone(userData.idPessoa);
    const endereco = await get_endereco(userData.Endereco);
    const dataNascimento = new Date(userData.data_nasc.replace("-", "/"));
    console.log(dataNascimento);
    const editarHTML = editarPerfilTemplate
        .replace(":nome", userData.nome)
        .replace(":data_nasc", dataNascimento.toLocaleDateString('pt-br'))
        .replace(":cpf", userData.cpf)
        .replace(":usuario", userData.usuario)
        .replace(":email", userData.email)
        .replace(":telefone", userTelefone.data.telefone)
        .replace(":bairro", endereco.data.bairro)
        .replace(":idPessoa", userData.idPessoa)
        .replace(":profileImg", `../../uploads/${userData.idPessoa}/${userData.foto}`)
        .replace(":endereco", endereco.data.endereco);

    containerPerfil.innerHTML = editarHTML;

    const cidade = document.querySelector("#cidade");
    const check_senha_display = document.querySelector(".input-wrapper > span");
    check_senha_display.style.display = 'none';
    submit_form.setAttribute('disabled', true);
    var cidade_id;

    //mascaras de input
    Inputmask({"mask": "(99) 9 9999-9999"}).mask(telefone);
    Inputmask({"mask": "999.999.999-99"}).mask(cpf);
    Inputmask({"mask": "99/99/9999"}).mask(data_nasc);


    //dados da API do IBGE sobre as localidades
    const estados = await fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados/')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json()
        })
        .then(data => {
            return data.sort((a, b) => a.nome.localeCompare(b.nome));
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });

    estados.forEach(item => {
        const option = document.createElement('option');
        option.textContent = item.nome;
        option.setAttribute('value', item.nome);
        option.setAttribute('id', item.id)
        if (item.nome === endereco.data.estado) {
            option.setAttribute('selected', true);
        }
        estado.appendChild(option);
    });

    const selected_id = Array.from(estado.children).filter(option => option.selected)[0].attributes.id.value;
    const cidades = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${selected_id}/municipios`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json()
        })
        .then(data => {
            return data.sort((a, b) => a.nome.localeCompare(b.nome));
        })
        .catch(error => {
            console.error('Error during fetch:', error);
        });

        cidades.forEach(item => {
            const option = document.createElement('option');
            option.textContent = item.nome;
            option.setAttribute('value', item.nome);
            option.setAttribute('id', item.id)
            if (item.nome === endereco.data.cidade) {
                option.setAttribute('selected', true);
            }
            cidade.appendChild(option);
        });
    estado.addEventListener('change', async function(event) {
        const selected_id = Array.from(event.target).filter(option => option.selected)[0].attributes.id.value;
        cidade_id = selected_id;
        cidade.innerHTML = "";
        bairro.value = "";
        const cidades = await fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${selected_id}/municipios`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json()
            })
            .then(data => {
                return data.sort((a, b) => a.nome.localeCompare(b.nome));
            })
            .catch(error => {
                console.error('Error during fetch:', error);
            });

            cidades.forEach(item => {
                const option = document.createElement('option');
                option.textContent = item.nome;
                option.setAttribute('value', item.nome);
                option.setAttribute('id', item.id)
                if (item.nome === endereco.data.cidade) {
                    option.setAttribute('selected', true);
                }
                cidade.appendChild(option);
            });
    });

    cidade.addEventListener('change', () => {
        bairro.removeAttribute('disabled');
    })


    //verificação de senha
    check_senha.addEventListener('input', (e) => {
        if (check_senha.value === senha.value) {
            check_senha_display.style.display = "none";
            submit_form.removeAttribute('disabled');
        } else {
            check_senha_display.style.display = "block";
        }
    });
}

async function showDetails(card) {
    const idAcordo = card.querySelector("#idAcordo").attributes[1].value;
    const { idPessoa } = await get_login_data();
    const acordo = await get_acordo(idAcordo);
    const modal = document.querySelector(".modal");

    let periodos = "";
    for (const periodo of acordo.data?.periodos || []) {
        periodos += `<span>${periodo.dia}, ${periodo.hora_inicio} - ${periodo.hora_final}</span>`;
    }

    const ofertaHTML = sectionsModal.oferta
        .replace(":ofertaImg", `../../uploads/${acordo.data.Freelancer}/${acordo.data.foto}`)
        .replace(":titulo", acordo.data.titulo)
        .replace(":area", acordo.data.nome)
        .replace(":descricao", acordo.data.descricao)
        .replace(":periodos", periodos)
        .replace(":valor", acordo.data.valor);

    console.log(acordo.data.Freelancer === idPessoa ? acordo.data.Contratante : acordo.data.Freelancer);
    const freelancer = await get_freelancer(
        acordo.data.Freelancer === idPessoa ? acordo.data.Contratante : acordo.data.Freelancer
    );
    const endereco = await get_endereco(freelancer.data.Endereco);
    const telefone = await get_telefone(freelancer.data.idPessoa);

    const parceiroHTML = sectionsModal.parceiro
        .replace(":profileImg", `../../uploads/${freelancer.data.idPessoa}/${freelancer.data.foto}`)
        .replace(":nome", freelancer.data.nome)
        .replace(":cidade", endereco.data.cidade)
        .replace(":bairro", endereco.data.bairro)
        .replace(":idade", getYearDifference(new Date(freelancer.data.data_nasc), new Date()))
        .replace(":email", freelancer.data?.email || "Nenhum E-mail cadastrado")
        .replace(":telefone", telefone.data?.telefone || "Nenhum número cadastrado");

    const avaliacao = await get_avaliacao(idAcordo);
    let estrelas = "";
    for (let c = 0; c < parseInt(avaliacao.data?.grau || 0); c++) {
        estrelas += '<img src="../../assets/icons/star.svg" alt="estrela">';
    }
    const avaliacaoHTML = avaliacao.data ? sectionsModal.avaliacao
        .replace(":estrelas", estrelas)
        .replace(":avaliacao", avaliacao.data.comentario)
        : "<h3>Nenhuma avaliação</h3>";

    modal.querySelector(".parceiros").innerHTML = parceiroHTML;
    modal.querySelector(".acordos").innerHTML = ofertaHTML;
    modal.querySelector(".avaliacoes").innerHTML = avaliacaoHTML;
    modal.style.display = "block";
}
window.showDetails = showDetails;
