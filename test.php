<?php
require __DIR__ . '/services/pessoa/PessoaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';
require __DIR__ . '/models/usuario/pessoa.php';
require __DIR__ . '/models/usuario/endereco.php';
require __DIR__ . '/configs/databaseConfig.php';

$pessoa = new Pessoa(pdo: $pdo);
$err = $pessoa->setPessoaUpdate(
    data_nasc: "2023-09-09",
    senha: "pedro",
    email: "a@a.a",
    telefones: ["(74) 3 3333-3333"],
    estado: "Pará",
    cidade: "Aurora do Pará",
    bairro: "juca",
    usuario: "luconis",
    id: 9,
);
if (isset($err['error'])) {
    echo $err['error'];
}

$err = $pessoa->update();
if (isset($err['error'])) {
    echo $err['error'];
}

//todos
/**
 * trazer os periodos das ofertas
 * criar os cards de oferta de maneira dinamica
 *      recuperar os periosdos em spans
 */
