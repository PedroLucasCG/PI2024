<?php

class Telefone {
    private $id;
    private $telefone;

    public function  __construct($pdo, $telefone) {
        $query = 'SELECT idTelefone AS id
        FROM Endereco
        WHERE telefone = :telefone';
        $stmt = $pdo->prepare($query);
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

    public function getAttribute() {
        return [
            'id' => $this->id,
            'telefone' => $this->telefone,
        ];
    }
}
