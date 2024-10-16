<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/acordo/acordo.php';

class Avaliacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $comentario = null, $grau, $Acordo) {

        $acordo = new Acordo($this->pdo);
        if(!isset($acordo->get($Acordo)['data'])) {
            return ["msg" => "Acordo não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Avaliacao SET 
                comentario = :comentario, 
                grau = :grau, 
                Acordo = :Acordo,
            WHERE idAvaliacao = :id";
        } else {
            $query = "INSERT INTO Avaliacao (comentario, grau, Acordo)
                VALUES (:comentario, :grau, :Acordo)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':comentario', $comentario);
        $stmt->bindParam(':grau', $grau);
        $stmt->bindParam(':Acordo', $Acordo);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Avaliação atualizada com sucesso."];
            } else {
                return ["msg" => "Avaliação criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar avaliação."];
        }

        $query = "SELECT * FROM Avaliação WHERE idAvaliacao = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Avaliação recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A avaliação não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar avaliação."];
        }

        $query = "DELETE FROM Avaliação WHERE idAvaliacao = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Avaliação deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a avaliação"];
        }
    }
}

$avaliacao = new Avaliacao($pdo);

$avaliacao->upsert("trabalho muito mal feito", 3, 1);

$avaliacao->upsert("trabalho muito mau feito", 3, 1, id: 1);

$avaliacao->get(1);