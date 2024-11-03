<?php

class Endereco {
    private $pdo;
    private $id;
    private $cep;
    private $estado;
    private $cidade;
    private $bairro;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function setEndereco($estado, $cidade, $bairro, $cep = null): void {
        $query = 'SELECT idEndereco AS id
        FROM Endereco
        WHERE estado = :estado
          AND cidade = :cidade
          AND bairro = :bairro';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);
        try {
            $stmt->execute();
            $data = $stmt->fetch();
        }catch (PDOException $ex) {
            echo 'Erro ao executar a verificação existência do endereço. Erro: ' . $ex->getMessage();
        }
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->cep = $cep;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
    }

    public function getAllAttributes() {
        return [
            'id' => $this->id,
            'cep' => $this->cep,
            'estado' => $this->estado,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
        ];
    }
}
