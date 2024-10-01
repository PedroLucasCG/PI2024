<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Endereco {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($id = null, $cep = null, $estado, $cidade, $bairro) {
        if ($id) {
            $query = "UPDATE Endereco SET 
                cep = :cep, 
                estado = :estado, 
                cidade = :cidade, 
                bairro = :bairro
            WHERE idEndereco = :id";
        } else {
            $query = "INSERT INTO Endereco (cep, estado, cidade, bairro)
                VALUES (:cep, :estado, :cidade, :bairro)
                ON DUPLICATE KEY UPDATE 
                    cep = VALUES(cep), 
                    estado = VALUES(estado), 
                    cidade = VALUES(cidade), 
                    bairro = VALUES(bairro)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                echo "Endereço atualizado com sucesso.";
            } else {
                echo "Endereço criado com sucesso";
            }
        } else {
            echo "Erro na execução da query.";
        }
    }
}

$endereco = new Endereco($pdo);

echo $endereco->upsert(null, null, 'SP', 'São Paulo', 'Centro');

echo $endereco->upsert(1, '87654321', 'RJ', 'Rio de Janeiro', 'Zona Sul');