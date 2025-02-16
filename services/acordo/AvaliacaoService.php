<?php

class AvaliacaoService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($Acordo, $comentario, $grau, $id = null) {

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

        $query = "SELECT * FROM avaliacao WHERE Acordo = :id";

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

    public function getByFreelancer($idPessoa) {
        if (!isset($idPessoa)) {
            return ["msg" => "O id é necessário para recuperar avaliação."];
        }

        $query = "SELECT * FROM avaliacao
         JOIN acordo ON idAcordo = Acordo
         JOIN oferta ON idOferta = Oferta
         JOIN pessoa ON idPessoa = Freelancer
         WHERE Freelancer = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $idPessoa);

        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data) {
            return [
                "msg" => "Avaliação recuperada com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "A avaliação não foi encontrada."];
        }
    }

    public function getALL($freelancer_id, $contratante_id): array {
        if (!isset($freelancer_id) || !isset($contratante_id)) {
            return ["msg" => "O id do freelancer ou contrante é necessário para recuperar avaliação."];
        }
        $query = "SELECT * FROM avaliacao ";

        $conditions = [];

        if ($freelancer_id) {
            $conditions[] = "Freelancer = $freelancer_id";
        }

        if ($contratante_id) {
            $conditions[] = "Contratante = $contratante_id";
        }

        if (!empty($conditions)) {
            $query .= "WHERE " . implode(" AND ", $conditions) . " ";
        }

        $query .= " JOIN acordo ON Acordo = idAcordo JOIN oferta ON Oferta = idOferta";

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Acordo recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "Nenhum Acordo não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar avaliação."];
        }

        $query = "DELETE FROM Avaliação WHERE idAvaliacao = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Avaliação deletada com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar a avaliação"];
        }
    }
}
