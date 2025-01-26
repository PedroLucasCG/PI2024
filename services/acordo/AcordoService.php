<?php
class AcordoService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $valor, $descricao = null, $estado, $modalidade, $Contratante, $Oferta) {

        $oferta = new Oferta($this->pdo);
        if(!isset($oferta->get($Oferta)['data'])) {
            return ["msg" => "Oferta não consta no sistema."];
        }

        $contratante = new Contratante($this->pdo);
        if(!isset($contratante->get($Contratante)['data'])) {
            return ["msg" => "Contratante não consta no sistema."];
        }

        if ($id) {
            $query = "UPDATE Acordo SET
                valor = :valor,
                descricao = :descricao,
                modalidade = :modalidade,
                estado = :estado
                Contratante = :Contratante,
                Oferta = :Oferta,
            WHERE idAcordo = :id";
        } else {
            $query = "INSERT INTO Acordo (valor, descricao, estado, modalidade, Contratante, Oferta)
                VALUES (:valor, :descricao, :estado,  :modalidade, :Contratante, :Oferta,)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':modalidade', $modalidade);
        $stmt->bindParam(':Contratante', $Contratante);
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

    public function get($id): array {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar acordo."];
        }

        $query = "SELECT *
                FROM acordo
                JOIN oferta ON Oferta = idOferta
                JOIN area ON Area = idArea
                WHERE idAcordo = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        $id = $data['idOferta'];
        $queryPeriodos = "SELECT * FROM periodo WHERE Oferta = :id";
        $stmtPeriodos = $this->pdo->prepare($queryPeriodos);
        $stmtPeriodos->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtPeriodos->execute();

        $periodos = $stmtPeriodos->fetchAll(PDO::FETCH_ASSOC);

        $result = array_merge($data, ['periodos' => $periodos]);

        if ($result) {
            return [
                "msg" => "Acordo recuperado com sucesso",
                "data" => $result,
            ];
        } else {
            return ["msg" => "O Acordo não foi encontrado."];
        }
    }

    public function getAll(?int $freelancer_id, ?int $contratante_id, ?string $status): array {
        $query = "SELECT * FROM acordo JOIN oferta ON Oferta = idOferta";

        $conditions = [];

        if ($freelancer_id) {
            $conditions[] = "Freelancer = $freelancer_id";
        }

        if ($contratante_id) {
            $conditions[] = "Contratante = $contratante_id";
        }

        if ($status) {
            if ($status == "finalizado") {
                $conditions[] = "estado = '$status' OR estado = 'quebrado'";
            } else {
                $conditions[] = "estado = '$status'";
            }
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions) . " ";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();
        $data = $stmt->fetchAll();

        if ($data) {
            return [
                "msg" => "Acordo recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "Nenhum Acordo não foi encontrado."];
        }
    }

    public function setEstado(int $idAcordo, string $estado): array {
        $query = "UPDATE acordo SET estado = '$estado' WHERE idAcordo = $idAcordo";
        $stmt = $this->pdo->prepare($query);

        if($stmt->execute()) {
            return ["msg" => "Estado atualizado com sucesso."];
        } else {
            return ["error" => "Ocorreu um erro na alteração do estado"];
        }
    }

    public function delete($id): array {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar acordo."];
        }

        $query = "DELETE FROM Acordo WHERE idAcordo = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Acordo deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o acordo"];
        }
    }
}
