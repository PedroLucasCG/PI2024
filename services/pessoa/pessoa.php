<?php
include __DIR__ . '/../../config/databaseConfig.php';
include __DIR__ . '/endereco.php';

class Pessoa {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($nome, $data_nasc, $cpf, $rg, $senha, $usuario, $email, $data_registro, $endereco_id, $id = null) {

        $endereco = new Endereco($this->pdo);
        if(!isset($endereco->get($endereco_id)["data"])) {
            return ["msg" => "Endereço não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE pessoa SET 
                nome = :nome, 
                email = :email, 
                data_nasc = :data_nasc, 
                cpf = :cpf,
                rg = :rg,
                senha = :senha,
                usuario = :usuario,
                data_registro = :data_registro,
                endereco_id = :endereco_id
                WHERE idPessoa = :id";
        } else {
            $query = "INSERT INTO pessoa (nome, email, data_nasc, cpf, rg, senha, usuario, data_registro, Endereco)
                VALUES (:nome, :email, :data_nasc, :cpf, :rg, :senha, :usuario, :data_registro, :endereco_id)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':data_registro', $data_registro);
        $stmt->bindParam(':endereco_id', $endereco_id);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Pessoa atualizada com sucesso."];
            } else {
                return ["msg" => "Pessoa criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar pessoa."];
        }

        $query = "SELECT * FROM Pessoa WHERE idPessoa = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Pessoa recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A pessoa não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar pessoa."];
        }

        $query = "DELETE FROM Endereco WHERE idPessoa = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Pessoa deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o pessoa"];
        }
    }
}

$pessoa = new Pessoa($pdo);

$pessoa->upsert('John Doe', '1990-01-01', '12345678900', '12345678', 'password123', 'johndoe', 'john@example.com', '2024-09-30', 1);
?>