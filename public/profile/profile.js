import Inputmask from "/node_modules/inputmask/dist/inputmask.es6.js";

function loadStatusAndReviews() {};
function loadProposalsAndProjects() {};
function loadOffers() {};
function suporte() {};

function deleteHorario(element) {
    element.parentElement.remove();
}

window.deleteHorario = deleteHorario;

const horarioTemplate = '<span>{0} <img onclick="deleteHorario(this)" src="../../assets/icons/delete.svg" alt="deletar horário"> <input type="hidden" value="{0}" name="horarios[]"></span>';

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

const main = document.getElementsByTagName("main")[0];
const panelSelect = document.querySelectorAll("li[panel]");
for (const panel of panelSelect) {
    panel.addEventListener("click", async function () {
        const panelItem = this.getAttribute("panel");
        const section = sections[panelItem];
        document.querySelectorAll("li[selected]")[0].removeAttribute("selected");
        if (section) {
            try {
                const response = await fetch(section.html);
                const content = await response.text();
                main.innerHTML = content;
                section.function();
                this.setAttribute("selected", true);
            } catch (error) {
                console.error('Erro ao carregar a seção:', error);
            }
            if (sections[panelItem].panel === "oferta") {
                const horarioInput = document.getElementById("horario");
                if (horarioInput) {
                    Inputmask({"mask": "99:99 - 99:99"}).mask(horarioInput);
                }

                const adicionar = document.getElementById("adicionar");
                adicionar.addEventListener("click", (event) => {
                    event.preventDefault();
                    const dia = document.getElementById("dia").value;
                    const horario = horarioInput.value;

                    if (dia && horario) {
                        const horarioHTML = horarioTemplate.replace(/\{0\}/g, `${dia}, ${horario}`);
                        document.getElementById("horariosContainer").innerHTML += horarioHTML;
                    } else {
                        alert("Por favor, preencha o dia e o horário.");
                    }
                });
            }

        } else {
            console.error('Seção não achada para o painel:', panelItem);
        }
    });
}
