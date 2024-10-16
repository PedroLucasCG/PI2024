<?php
include __DIR__ . '/config/databaseConfig.php';
include __DIR__ . '/services/acordo/acordo.php';

class Quebra {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $parte, $descricao, $Acordo) {

        $acordo = new Acordo($this->pdo);
        if(!isset($acordo->get($Acordo)['data'])) {
            return ["msg" => "Acordo não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Quebra SET 
                parte = :parte, 
                descricao = :descricao, 
                Acordo = :Acordo,
            WHERE idQuebra = :id";
        } else {
            $query = "INSERT INTO Quebra (parte, descricao, Acordo)
                VALUES (:parte, :descricao, :Acordo)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':parte', $parte);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':Acordo', $Acordo);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Quebra atualizada com sucesso."];
            } else {
                return ["msg" => "Quebra criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar quebra."];
        }

        $query = "SELECT * FROM Quebra WHERE idQuebra = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Quebra recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A Quebra não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar quebra."];
        }

        $query = "DELETE FROM Quebra WHERE idQuebra = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Quebra deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a quebra"];
        }
    }
}

$quebra = new Quebra($pdo);

$quebra->upsert("freelancer", "O vei não sabe capinar direito", 1);

$quebra->upsert("contratante", "O vei não sabe capinar direito", 1, id: 1);

$quebra->get(1);