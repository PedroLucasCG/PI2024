<?php
require __DIR__ . '../../services/pessoa/TelefoneService.php';
class Telefone {
    private $pdo;
    private $id;
    private $pessoa_id;
    private $telefone;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function setTelefone(string $telefone, int $pessoa_id): ?array {
        $query = 'SELECT idTelefone AS id
        FROM Telefone
        WHERE telefone = :telefone AND Pessoa = :pessoa_id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':pessoa_id', $pessoa_id);
        try {
            $stmt->execute();
            $data = $stmt->fetch();
        }catch (PDOException $ex) {
            echo 'Erro ao executar a verificação da existência do telefone. Erro: ' . $ex->getMessage();
        }
        if (isset($data['id'])) {
            return [ 'msg' => "Esse telefone já está cadastrado." ];
        }
        $this->telefone = $telefone;
        $this->pessoa_id = $pessoa_id;
    }

    public function getAllAttributes(): array {
        return [
            'id' => $this->id,
            'telefone' => $this->telefone,
            'pessoa_id' => $this->pessoa_id,
        ];
    }

    public function create(): void {
        $service = new TelefoneService($this->pdo);
        $service->upsert($this->getAllAttributes());
    }

    public function from(string $telefone, int $id, int $pessoa_id): void {
        $this->id = $id;
        $this->telefone = $telefone;
        $this->pessoa_id = $pessoa_id;
    }
}
