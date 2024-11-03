<?php

class Telefone {
    private $pdo;
    private $id;
    private $telefone;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function setTelefone($telefone): void {
        $query = 'SELECT idTelefone AS id
        FROM Endereco
        WHERE telefone = :telefone';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':telefone', $telefone);
        try {
            $stmt->execute();
            $data = $stmt->fetch();
        }catch (PDOException $ex) {
            echo 'Erro ao executar a verificação da existência do telefone. Erro: ' . $ex->getMessage();
        }
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->telefone = $telefone;
    }

    public function getAttribute(): array {
        return [
            'id' => $this->id,
            'telefone' => $this->telefone,
        ];
    }
}
