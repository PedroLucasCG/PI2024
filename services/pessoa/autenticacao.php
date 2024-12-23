<?php
class Autenticacao
{
    private PDO $pdo;

    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    function login(string $email, string $senha): array
    {
        $query = "SELECT * FROM Pessoa WHERE email = :email AND senha = :senha";

        $stmt = $this->pdo->prepare(query: $query);
        $stmt->bindParam(param: ":email", var: $email, type: PDO::PARAM_STR);
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
            $_SESSION['login'] = $data;
            return ['msg' => 'Login realizado com sucesso.', 'userId' => $data['idPessoa']];
        }
    }
}
