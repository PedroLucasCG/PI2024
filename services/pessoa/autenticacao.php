<?php

class Autenticacao
{
    private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function login(string $usuario, string $senha): array
    {
        $this->usuario = $usuario;
        $this->senha = $senha;

        $query = "SELECT idPessoa FROM Pessoa WHERE usuario = :usuario AND senha = :senha";

        $stmt = $this->pdo->prepare(query: $query);
        $stmt->bindParam(param: ":usuario", var: $usuario, type: PDO::PARAM_STR);
        $stmt->bindParam(param: ":senha", var: $senha, type: PDO::PARAM_STR);

        try {
            $stmt->execute();
            $data = $stmt->fetch(mode: PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return ['msg' => 'Ocorreu um erro ao realizar o login do usuário. ' . $ex->getMessage()];
        }
        if (!$data) {
            return ['msg' => 'O login do usuário não existe.'];
        } else {
            session_start();
            $_SESSION['usuario'] = $usuario;
            $_SESSION['idPessoa'] = $data['idPessoa'];
            return ['msg' => 'Login realizado com sucesso.', 'userId' => $data['idPessoa']];
        }
    }
}
