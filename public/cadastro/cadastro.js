import Inputmask from "/node_modules/inputmask/dist/inputmask.es6.js";
const cidade_dafault = cidade.innerHTML;
var cidade_id;
Inputmask({"mask": "(99) 9 9999-9999"}).mask(telefone);
Inputmask({"mask": "999.999.999-99"}).mask(cpf);
Inputmask({"mask": "99/99/9999"}).mask(data_nasc);

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
    estado.appendChild(option);
});

estado.addEventListener('change', async function(event) {
    const selected_id = Array.from(event.target).filter(option => option.selected)[0].attributes.id.value;
    cidade_id = selected_id;
    cidade.removeAttribute('disabled');
    cidade.innerHTML = cidade_dafault;
    bairro.setAttribute('disabled', true);
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
        cidade.appendChild(option);
    });
});

cidade.addEventListener('change', () => {
    bairro.removeAttribute('disabled');
})
