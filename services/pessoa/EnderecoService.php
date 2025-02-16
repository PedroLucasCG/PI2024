<?php
class EnderecoService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert(array $endereco): array {
        extract($endereco);
        if (isset($id)) {
            $query = "UPDATE Endereco SET
                cep = :cep,
                estado = :estado,
                cidade = :cidade,
                bairro = :bairro
            WHERE idEndereco = :id";
        } else {
            $query = "INSERT INTO Endereco (cep, estado, cidade, bairro)
                VALUES (:cep, :estado, :cidade, :bairro)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);

        if (isset($id)) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if (isset($id)) {
                return ["msg" => "Endereço atualizado com sucesso."];
            } else {
                return ["msg" => "Endereço criado com sucesso", "id" => $this->pdo->lastInsertId()];
            }
        } else {
            return ["error" => "Erro na execução da query."];
        }
    }

    public function get($id): array {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para recuperar endereço."];
        }

        $query = "SELECT * FROM Endereco WHERE idEndereco = :id";

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':id', $id);

        $stmt->execute();
        $data = $stmt->fetch();

        if ($data) {
            return [
                "msg" => "Endereço recuperado com sucesso",
                "data" => $data,
            ];
        } else {
            return ["error" => "O endereço não foi encontrado."];
        }
    }

    public function delete($id) {
        if (!isset($id)) {
            return ["msg" => "O id é necessário para deletar endereço."];
        }

        $query = "DELETE FROM Endereco WHERE idEndereco = :id";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return ["msg" => "Endereço deletado com sucesso"];
        } else {
            return ["msg" => "Ocorreu um erro ao deletar o endereço"];
        }
    }
}
