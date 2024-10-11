<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Area {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $nome) {

        if ($id) {
            $query = "UPDATE Area SET 
                nome = :nome, 
            WHERE idArea = :id";
        } else {
            $query = "INSERT INTO Area (nome)
                VALUES (:nome,)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Área atualizada com sucesso."];
            } else {
                return ["msg" => "Área criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar área."];
        }

        $query = "SELECT * FROM Area WHERE idArea = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Área recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A área não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar área."];
        }

        $query = "DELETE FROM Area WHERE idArea = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Área deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o área"];
        }
    }
}

$area = new Area($pdo);

$area->upsert('Centro');

$area->upsert(1, '87654321', 'RJ', 'Rio de Janeiro', 'Zona Sul');

$area->get(1);


