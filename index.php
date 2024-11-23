<?php
require __DIR__ . '/services/pessoa/autenticacao.php';
require __DIR__ . '/configs/databaseConfig.php';

if (empty($_POST)) {
    header(header: "Location: /public/login/login.html");
    exit;
} else {
    extract(array: $_POST);
    if ($form === 'login') {
        $auth = new Autenticacao(pdo: $pdo);
        print_r($auth->login($usuario, $senha));
    }
}
