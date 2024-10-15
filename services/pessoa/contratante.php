<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/pessoa/pessoa.php';

class Contratante {
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
            $query = "UPDATE Contratante SET 
                Pessoa = :Pessoa, 
            WHERE idContratante = :id";
        } else {
            $query = "INSERT INTO Contratante (Pessoa)
                VALUES (:Pessoa)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':Pessoa', $Pessoa);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Contratante atualizado com sucesso."];
            } else {
                return ["msg" => "Contratante criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar contratante."];
        }

        $query = "SELECT * FROM Contratante WHERE idContratante = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Contratante recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O contratante não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar contratante."];
        }

        $query = "DELETE FROM Contratante WHERE idContratante = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Contratante deletado com sucesso."];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o contratante."];
        }
    }
}

$contratante = new Contratante($pdo);

$contratante->upsert(1);

$contratante->upsert(1, 1);

$contratante->get(1);


