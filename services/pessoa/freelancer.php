<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/pessoa/pessoa.php';

class Freelancer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $Pessoa) {

        $pessoa = new Pessoa($this->pdo);
        if(!isset($pessoa->get($Pessoa)["data"])) {
            return ["msg" => "Pessoa não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Freelancer SET 
                Pessoa = :Pessoa, 
            WHERE idFreelancer = :id";
        } else {
            $query = "INSERT INTO Freelancer (Pessoa)
                VALUES (:Pessoa)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':Pessoa', $Pessoa);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Freelancer atualizado com sucesso."];
            } else {
                return ["msg" => "Freelancer criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar freelancer."];
        }

        $query = "SELECT * FROM Freelancer WHERE idFreelancer = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Freelancer recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O freelancer não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar freelancer."];
        }

        $query = "DELETE FROM Freelancer WHERE idFreelancer = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Freelancer deletado com sucesso."];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o freelancer."];
        }
    }
}

$freelancer = new Freelancer($pdo);

$freelancer->upsert(1);

$freelancer->upsert(1, 1);

$freelancer->get(1);