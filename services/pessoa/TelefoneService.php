<?php
class TelefoneService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($telefone): array {
        extract($telefone);
        if ($id) {
            $query = "UPDATE Telefone SET
                telefone = :telefone,
                Pessoa = :Pessoa,
            WHERE idTelefone = :id";
        } else {
            $query = "INSERT INTO Telefone (telefone, Pessoa)
                VALUES (:telefone, :Pessoa)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':Pessoa', $pessoa_id);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                return ["msg" => "Telefone atualizado com sucesso."];
            } else {
                return ["msg" => "Telefone criado com sucesso"];
            }
        } else {
            return ["msg" => "Erro na execução da query."];
        }
    }

    public function get($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar telefone."];
        }

        $query = "SELECT * FROM Telefone WHERE idTelefone = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Telefone recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["msg" => "O telefone não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar telefone."];
        }

        $query = "DELETE FROM Telefone WHERE idTelefone = :id";

        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Telefone deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o telefone"];
        }
    }
}
