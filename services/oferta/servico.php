<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/oferta/area.php';

class Servico {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $nome, $Area) {

        if ($id) {
            $query = "UPDATE Servico SET 
                nome = :nome, 
                Area = :Area
            WHERE idServico = :id";
        } else {
            $query = "INSERT INTO Servico (nome, Area)
                VALUES (:nome, :Area)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':Area', $Area);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Serviço atualizada com sucesso."];
            } else {
                return ["msg" => "Serviço criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar serviço."];
        }

        $query = "SELECT * FROM Servico WHERE idServico = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Serviço recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O serviço não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar serviço."];
        }

        $query = "DELETE FROM Servico WHERE idServico = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Serviço deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o serviço"];
        }
    }
}

$servico = new Servico($pdo);

$servico->upsert('construção', 1);

$servico->upsert(1, 'demolição', 1);

$servico->get(1);