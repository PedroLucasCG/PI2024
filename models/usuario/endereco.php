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
    public function setEndereco(string $estado, string $cidade, string $bairro, string $cep = null): void {
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

    public function getAllAttributes(): array {
        return [
            'id' => $this->id,
            'cep' => $this->cep,
            'estado' => $this->estado,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
        ];
    }

    public function create(): ?int {
        if(isset($this->id)) {
            return $this->id;
        };
        $service = new EnderecoService(pdo: $this->pdo);
        $service_data = $service->upsert(endereco: $this->getAllAttributes());
        return $service_data["id"];
    }

    public function from(string $estado, string $cidade, string $bairro, int $id, string $cep = null) :void {
        $this->id = $id;
        $this->cep = $cep;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
    }
}
