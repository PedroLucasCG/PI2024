<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Estado {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $nome) {

        if ($id) {
            $query = "UPDATE Estado SET 
                nome = :nome, 
            WHERE idEstado = :id";
        } else {
            $query = "INSERT INTO Estado (nome)
                VALUES (:nome,)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Estado atualizado com sucesso."];
            } else {
                return ["msg" => "Estado criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar estado."];
        }

        $query = "SELECT * FROM Estado WHERE idEstado = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Estado recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O Estado não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar estado."];
        }

        $query = "DELETE FROM Estado WHERE idEstado = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Estado deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o estado"];
        }
    }
}

$Estado = new Estado($pdo);

$Estado->upsert('Bahia');

$Estado->upsert(1, 'São Paulo');

$Estado->get(1);


