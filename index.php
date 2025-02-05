<?php
session_start();
require __DIR__ . '/configs/databaseConfig.php';

//models
require __DIR__ . '/models/usuario/pessoa.php';
require __DIR__ . '/models/usuario/telefone.php';
require __DIR__ . '/models/usuario/endereco.php';

require __DIR__ . '/models/oferta/oferta.php';

//services
require __DIR__ . '/services/pessoa/autenticacao.php';
require __DIR__ . '/services/pessoa/TelefoneService.php';
require __DIR__ . '/services/pessoa/PessoaService.php';
require __DIR__ . '/services/pessoa/EnderecoService.php';

require __DIR__ . '/services/oferta/OfertaService.php';

if (isset($_POST['form'])) {
    extract(array: $_POST);
    switch ($form) {
        case "login":
            $auth = new Autenticacao(pdo: $pdo);
            $err = $auth->login($email, $senha);
            if (isset($err['error'])) {
                if (isset($_SESSION['error'])) {
                    array_push($_SESSION['error'], $err['error']);
                } else {
                    $_SESSION['error'] = [$err['error']];
                }
                header(header: "Location: /public/login/login.html");
                break;
            }
           header(header: "Location: /public/landing_page/landing_page.html");
            break;
        case "cadastro":
            $date = DateTimeImmutable::createFromFormat("d/m/Y", $data_nasc);
            $date_formatted = $date->format("Y-m-d");
            $pessoa = new Pessoa(pdo: $pdo);
            $err = $pessoa->setPessoa(
                nome: $nome,
                data_nasc: $date_formatted,
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
            if (isset($err['error'])) {
                if (isset($_SESSION['error'])) {
                    array_push($_SESSION['error'], $err['error']);
                } else {
                    $_SESSION['error'] = [$err['error']];
                }
                header(header: "Location: /public/cadastro/cadastro.html");
            }

            $err = $pessoa->create();
            if (isset($err['error'])) {
                if (isset($_SESSION['error'])) {
                    array_push($_SESSION['error'], $err['error']);
                } else {
                    $_SESSION['error'] = [$err['error']];
                }
                header(header: "Location: /public/cadastro/cadastro.html");
            }
            header(header: "Location: /public/login/login.html");
            break;
        case "oferta":
            $oferta = new Oferta($pdo);
            $oferta->setOferta(descricao: $descricao, preco: $preco, Freelancer: $Freelancer, Area: $Area, periodos: $periodos, titulo: $titulo, files: $_FILES);
            $oferta->create();
            header(header: "Location: /public/profile/profile.html");
            break;
        case "atualizarPerfil":
            $date = DateTimeImmutable::createFromFormat("d/m/Y", $data_nasc);
            $date_formatted = $date->format("Y-m-d");
            $pessoa = new Pessoa(pdo: $pdo);
            $err = $pessoa->setPessoaUpdate(
                data_nasc: $date_formatted,
                senha: $senha,
                email: $email,
                telefones: [$telefone],
                estado: $estado,
                cidade: $cidade,
                bairro: $bairro,
                usuario: $usuario,
                id: $idPessoa,
            );
            if (isset($err['error'])) {
                if (isset($_SESSION['error'])) {
                    array_push($_SESSION['error'], $err['error']);
                } else {
                    $_SESSION['error'] = [$err['error']];
                }
            }

            $err = $pessoa->update();
            if (isset($err['error'])) {
                if (isset($_SESSION['error'])) {
                    array_push($_SESSION['error'], $err['error']);
                } else {
                    $_SESSION['error'] = [$err['error']];
                }
            }
            header(header: "Location: /public/profile/profile.html");
            break;
    }
    exit;
} else {
    header(header: "Location: /public/landing_page/landing_page.html");
    exit;
}
