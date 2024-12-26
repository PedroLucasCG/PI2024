<?php
class Pessoa
{
    private PDO $pdo;
    private ?int $id;
    private string $nome;
    private string $data_nasc;
    private string $cpf;
    private string $senha;
    private string $email;
    private string $usuario;
    private array | Endereco $endereco;
    private array $telefones;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->id = null;
    }
    public function setPessoa(string $nome, string $data_nasc, string $cpf, string $senha, string $email, array $telefones, string $estado, string $cidade, string $bairro, string $usuario, string $cep = null, int $id = null): ?array
    {
        if (isset($id)) {
            $query = "SELECT COUNT(*) AS count FROM Pessoa WHERE id = :id";
            $stmt = $this->pdo->prepare(query: $query);
            $stmt->bindParam(param: ':id', var: $id);
            try {
                $stmt->execute();
                $data = $stmt->fetch();
            }catch (PDOException $ex) {
                echo 'Erro ao executar a verificação da existência do registro de pessoa. Erro: ' . $ex->getMessage();
            }

            if($data['count'] == 0) {
                return [ 'msg' => 'O id passado não corresponde a nenhum registro de pessoa no sistema.'];
            }
            $this->id = $id;
        }
        $this->nome = $nome;
        $this->data_nasc = $data_nasc;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->email = $email;
        $this->usuario = $usuario;
        $this->endereco = ['estado' => $estado, 'cidade' => $cidade, 'bairro' => $bairro, 'cep' => $cep];
        $this->telefones = $telefones;

        return null;
    }

    public function getAllAttributes(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'data_nasc' => $this->data_nasc,
            'cpf' => $this->cpf,
            'senha' => $this->senha,
            'email' => $this->email,
            'usuario' => $this->usuario,
            'endereco' => $this->endereco,
            'telefones' => $this->telefones
        ];
    }

    public function create(): void {
        $service = new PessoaService(pdo: $this->pdo);
        $service->upsert(pessoa: $this->getAllAttributes());
    }
    public function from(string $nome, string $data_nasc, string $cpf, string $senha, string $email, array $telefones, string $estado, string $cidade, string $bairro, string $cep = null, int $id = null): void {
        $this->id = $id;
        $this->nome = $nome;
        $this->data_nasc = $data_nasc;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->email = $email;
        $this->endereco = new Endereco($this->pdo);
        $this->endereco->setEndereco(estado: $estado, cidade: $cidade, bairro: $bairro, cep: $cep);
        foreach ($telefones as $key => $tel) {
            $telefone = new Telefone($this->pdo);
            array_push(array: $this->telefones, values: $telefone->setTelefone(telefone: $tel, pessoa_id: $id));
        }
        $this->telefones = $telefones;
    }
}
