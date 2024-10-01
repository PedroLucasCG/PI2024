<?php
include __DIR__ . '/../../config/databaseConfig.php';

class Pessoa {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function upsert($nome, $data_nasc, $cpf, $rg, $senha, $usuario, $email, $data_registro, $endereco_id, $id = null) {
        if ($id) {
            $query = "UPDATE pessoa SET 
                nome = :nome, 
                email = :email, 
                data_nasc = :data_nasc, 
                cpf = :cpf,
                rg = :rg,
                senha = :senha,
                usuario = :usuario,
                data_registro = :data_registro,
                endereco_id = :endereco_id
                WHERE idPessoa = :id";
        } else {
            $query = "INSERT INTO pessoa (nome, email, data_nasc, cpf, rg, senha, usuario, data_registro, Endereco)
                VALUES (:nome, :email, :data_nasc, :cpf, :rg, :senha, :usuario, :data_registro, :endereco_id)";
        }

        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':rg', $rg);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':data_registro', $data_registro);
        $stmt->bindParam(':endereco_id', $endereco_id);

        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        if ($stmt->execute()) {
            if ($id) {
                echo "Pessoa atualizada com sucesso.";
            } else {
                echo "Pessoa criada com sucesso";
            }
        } else {
            echo "Erro na execução da query.";
        }
    }
}

$pessoa = new Pessoa($pdo);

$pessoa->upsert('John Doe', '1990-01-01', '12345678900', '12345678', 'password123', 'johndoe', 'john@example.com', '2024-09-30', 1);
?>