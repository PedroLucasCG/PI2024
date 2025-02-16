<?php

class AreaService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {

        $query = "SELECT * FROM area";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data) {
            return [
                "msg" => "Área recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["error" => "A área não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar área."];
        }

        $query = "DELETE FROM Area WHERE idArea = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Área deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a área"];
        }
    }
}
