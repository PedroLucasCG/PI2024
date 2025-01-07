import Inputmask from "/node_modules/inputmask/dist/inputmask.es6.js";

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
        <input type="hidden" value="{0}" name="horarios[]">
    </span>
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
function configureOfertaSection() {
    const horarioInput = document.getElementById("horario");
    const adicionarButton = document.getElementById("adicionar");

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
                document.getElementById("horariosContainer").innerHTML += horarioHTML;
            } else {
                alert("Por favor, preencha o dia e o horário corretamente.");
            }
        });
    }
}
