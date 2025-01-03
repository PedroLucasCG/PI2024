<?php
require __DIR__ . '/configs/databaseConfig.php';

//models
require __DIR__ . '/models/usuario/pessoa.php';
require __DIR__ . '/models/usuario/telefone.php';
require __DIR__ . '/models/usuario/endereco.php';

//services
require __DIR__ . '/services/pessoa/autenticacao.php';
require __DIR__ . '/services/pessoa/TelefoneService.php';
require __DIR__ . '/services/pessoa/PessoaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';

if (isset($_POST['form'])) {
    extract(array: $_POST);
    switch ($form) {
        case "login":
            $auth = new Autenticacao(pdo: $pdo);
            $err = $auth->login($email, $senha);
            if (isset($err['erro'])) {
                echo $err['erro'];
                break;
            }
            header(header: "Location: /public/landing_page/landing_page.html");
            break;
        case "cadastro":
            $pessoa = new Pessoa(pdo: $pdo);
            $err = $pessoa->setPessoa(
                nome: $nome,
                data_nasc: $data_nasc,
                cpf: $cpf,
                senha: $senha,
                email: $email,
                telefones: [$telefone],
                estado: $estado,
                cidade: $cidade,
                bairro: $bairro,
                usuario: $usuario,
                cep: isset($cep) ?? $cep,
                terms: $terms
            );
            if (isset($err['msg'])) {
                echo $err['msg'];
                break;
            }

            $err = $pessoa->create();
            if (isset($err['msg'])) {
                echo $err['msg'];
                break;
            }
            header(header: "Location: /public/login/login.html");
            break;
    }
    exit;
} else {
    header(header: "Location: /public/landing_page/landing_page.html");
    exit;
}
