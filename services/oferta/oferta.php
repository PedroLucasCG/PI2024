<?php
include __DIR__ . '/../../config/databaseConfig.php';
include __DIR__ . '../pessoa/freelancer.php';
include __DIR__ . '/servico.php';

class Oferta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $Freelancer, $Servico, $descricao, $preco = 0.0) {

        $servico = new Servico($this->pdo);
        if(!isset($servico->get($Servico)["data"])) {
            return ["msg" => "Serviço não consta no sistema."];
        }

        $freelancer = new Freelancer($this->pdo);
        if(!isset($freelancer->get($Freelancer)["data"])) {
            return ["msg" => "Freelancer não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Oferta SET 
                Freelancer = :Freelancer, 
                Servico = :Servico, 
                descricao = :descricao,
                preco = :preco
            WHERE idOferta = :id";
        } else {
            $query = "INSERT INTO Oferta (Freelancer, Servico, descricao, preco)
                VALUES (:Freelancer, :Servico, :descricao, :preco)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':Freelancer', $Freelancer);
        $stmt->bindParam(':Servico', $Servico);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco', $preco);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Oferta atualizada com sucesso."];
            } else {
                return ["msg" => "Oferta criada com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar oferta."];
        }

        $query = "SELECT * FROM Oferta WHERE idOferta = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Oferta recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A oferta não foi encontrada."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar oferta."];
        }

        $query = "DELETE FROM Oferta WHERE idOferta = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Oferta deletada com sucesso."];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a oferta."];
        }
    }
}

$oferta = new Oferta($pdo);

$oferta->upsert(1, 1, "Ofera Braba", 10.98);

$oferta->upsert(1, 1, 1, "Ofera Braba", 10.98));

$oferta->get(1);


