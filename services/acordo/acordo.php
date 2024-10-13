<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Acordo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $valor, $descricao = null, $estado, $modalidade, $Cliente, $Oferta) {

        if ($id) {
            $query = "UPDATE Acordo SET 
                valor = :valor, 
                descricao = :descricao, 
                modalidade = :modalidade,
                estado = :estado
                Cliente = :Clienre,
                Oferta = :Oferta,
            WHERE idAcordo = :id";
        } else {
            $query = "INSERT INTO Acordo (valor, descricao, estado, modalidade, Cliente, Oferta)
                VALUES (:valor, :descricao, :estado,  :modalidade, :Cliente, :Oferta,)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':modalidade', $modalidade);
        $stmt->bindParam(':Cliente', $Cliente);
        $stmt->bindParam(':Oferta', $Oferta);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Acordo atualizado com sucesso."];
            } else {
                return ["msg" => "Acordo criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar acordo."];
        }

        $query = "SELECT * FROM Acordo WHERE idAcordo = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Acordo recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O Acordo não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar acordo."];
        }

        $query = "DELETE FROM Acordo WHERE idAcordo = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Acordo deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o acordo"];
        }
    }
}

$Acordo = new Acordo($pdo);

$Acordo->upsert('Bahia');

$Acordo->upsert(1, 'São Paulo');

$Acordo->get(1);