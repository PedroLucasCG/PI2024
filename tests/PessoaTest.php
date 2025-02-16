<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../services/pessoa/PessoaService.php';
require __DIR__ . '/../services/pessoa/EnderecoService.php';
require __DIR__ . '/../services/pessoa/TelefoneService.php';
require __DIR__ . '/../models/usuario/endereco.php';
require __DIR__ . '/../models/usuario/telefone.php';

class PessoaTest extends TestCase
{
    private $pdo;
    private $pessoaService;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
        $this->pessoaService = new PessoaService($this->pdo);
    }

    public function testUpsertInsertsNewPessoa()
    {
        $pessoa = [
            'nome' => 'John Doe',
            'email' => 'john@example.com',
            'data_nasc' => '1990-01-01',
            'cpf' => '12345678900',
            'senha' => 'password123',
            'usuario' => 'johndoe',
            'endereco' => [
                'estado' => 'SP',
                'cidade' => 'São Paulo',
                'bairro' => 'Centro'
            ],
            'telefones' => ['11987654321']
        ];

        $this->stmt->method('execute')->willReturn(true);
        $this->pdo->method('lastInsertId')->willReturn(1);

        $result = $this->pessoaService->upsert($pessoa);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Pessoa criada com sucesso.', $result['msg']);
    }

    public function testGetPessoaReturnsData()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('fetch')->willReturn([
            'idPessoa' => $id,
            'nome' => 'John Doe'
        ]);

        $result = $this->pessoaService->get($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Pessoa recuperada com sucesso', $result['msg']);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($id, $result['data']['idPessoa']);
    }

    public function testDeletePessoaReturnsSuccessMessage()
    {
        $id = 1;
        $this->stmt->method('execute')->willReturn(true);

        $result = $this->pessoaService->delete($id);

        $this->assertArrayHasKey('msg', $result);
        $this->assertEquals('Pessoa deletada com sucesso', $result['msg']);
    }
}
