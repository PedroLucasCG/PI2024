<?php
require __DIR__ . '/services/pessoa/autenticacao.php';
require __DIR__ . '/configs/databaseConfig.php';

session_start();

if (isset($_POST['form'])) {
    header(header: "Location: /public/login/login.html");
    exit;
} else {
    extract(array: $_POST);
    if ($form === 'login') {
        $auth = new Autenticacao(pdo: $pdo);
        print_r($auth->login($usuario, $senha));
    }
    header(header: "Location: /public/cadastro/cadastro.html");
    exit;
}
