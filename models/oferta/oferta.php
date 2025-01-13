<?php
class Oferta
{
    private PDO $pdo;
    private ?int $id;
    private string $descricao;
    private string $titulo;
    private ?float $preco;
    private int $Freelancer;
    private int $Area;
    private array $periodos;
    private ?array $files;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->id = null;
    }

    public function setOferta(string $descricao, ?float $preco, int $Freelancer, int $Area, array $periodos, string $titulo, $files): ?array
    {
        $this->descricao = $descricao;
        $this->files = $files;
        $this->preco = $preco;
        if ($this->count('idPessoa', $Freelancer, 'pessoa') == 0) {
            return ['error' => 'O Freelancer não existe existe.'];
        }
        $this->Freelancer = $Freelancer;
        echo $this->Freelancer;
        if ($this->count('idArea', $Area, 'area') == 0) {
            return ['error' => 'A área não existe existe.'];
        }
        $this->Area = $Area;
        if(empty($periodos))
            return ['error'=> "É necessário passar periodos para sua oferta."];
        $this->periodos = $periodos;
        $this->titulo = $titulo;

        return null;
    }

    public function getAllAttributes(): array
    {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'Freelancer' => $this->Freelancer,
            'Area' => $this->Area,
            'titulo' => $this->titulo,
            'periodos' => $this->periodos,
            'file' => $this->files,
        ];
    }

    public function create(): ?array
    {
        $service = new OfertaService(pdo: $this->pdo);
        $err = $service->upsert(oferta: $this->getAllAttributes());
        if (isset($err['error']))
            return $err;
        return null;
    }

    private function count(string $field, int|string $value, string $table): ?int
    {
        $query = "SELECT COUNT(*) AS count FROM $table WHERE $field = :$field";
        $stmt = $this->pdo->prepare(query: $query);
        $stmt->bindParam(param: ":$field", var: $value);
        try {
            $stmt->execute();
            $data = $stmt->fetch();
        } catch (PDOException $ex) {
            echo 'Erro ao executar a verificação da existência do registro de pessoa. Erro: ' . $ex->getMessage();
            return null;
        }

        return $data['count'];
    }
}
