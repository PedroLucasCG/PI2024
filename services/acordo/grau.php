<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Grau {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $nome) {

        if ($id) {
            $query = "UPDATE Grau SET 
                nome = :nome, 
            WHERE idGrau = :id";
        } else {
            $query = "INSERT INTO Grau (nome)
                VALUES (:nome,)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Grau atualizado com sucesso."];
            } else {
                return ["msg" => "Grau criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar Grau."];
        }

        $query = "SELECT * FROM Grau WHERE idGrau = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Grau recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O Grau não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar Grau."];
        }

        $query = "DELETE FROM Grau WHERE idGrau = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Grau deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o Grau"];
        }
    }
}

$Grau = new Grau($pdo);

$Grau->upsert('Bom');

$Grau->upsert(1, 'Ruim');

$Grau->get(1);


